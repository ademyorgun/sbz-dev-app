<?php

namespace App\Http\Controllers\Voyager;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Database\Schema\SchemaManager;
use Illuminate\Pagination\LengthAwarePaginator;
use TCG\Voyager\Http\Controllers\VoyagerBaseController as BaseVoyagerBaseController;

class VoyagerAppointmentController extends BaseVoyagerBaseController
{
    //***************************************
    //               ____
    //              |  _ \
    //              | |_) |
    //              |  _ <
    //              | |_) |
    //              |____/
    //
    //      Browse our Data Type (B)READ
    //
    //****************************************

    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // different data depending on views
        if(strtolower(auth()->user()->role->name) == 'sales_agent') {
            $now = now()->toDateString();
            // ***************************feedback open
            // GET THE DataType based on the slug
            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // GET ALL the Users
            $users = User::all();

            // Check permission
            $this->authorize('browse', app($dataType->model_name));

            $getter = $dataType->server_side ? 'paginate' : 'get';

            // Row data and options
            foreach ($dataType->addRows as $key => $row) {
                $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
            };

            $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];
            $searchable = $dataType->server_side ? array_keys(SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray()) : '';
            $orderBy = $request->get('order_by', $dataType->order_column);
            $sortOrder = $request->get('sort_order', null);
            $usesSoftDeletes = false;
            $showSoftDeleted = false;
            $orderColumn = [];
            if ($orderBy) {
                $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + 1;
                $orderColumn = [[$index, 'desc']];
                if (!$sortOrder && isset($dataType->order_direction)) {
                    $sortOrder = $dataType->order_direction;
                    $orderColumn = [[$index, $dataType->order_direction]];
                } else {
                    $orderColumn = [[$index, 'desc']];
                }
            }

            // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
            if (strlen($dataType->model_name) != 0) {
                $model = app($dataType->model_name);

                if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
                    $query = $model->{$dataType->scope}()
                        ->whereNull('comment_status')
                        ->where('meeting_date', '<', $now);
                } else {
                    $query = $model::select('*')
                        ->whereNull('comment_status')
                        ->where('meeting_date', '<', $now);
                }

                // Use withTrashed() if model uses SoftDeletes and if toggle is selected
                if ($model && in_array(SoftDeletes::class, class_uses($model)) && app('VoyagerAuth')->user()->can('delete', app($dataType->model_name))) {
                    $usesSoftDeletes = true;

                    if ($request->get('showSoftDeleted')) {
                        $showSoftDeleted = true;
                        $query = $query->withTrashed();
                    }
                }

                // If a column has a relationship associated with it, we do not want to show that field
                $this->removeRelationshipField($dataType, 'browse');

                if ($orderBy && in_array($orderBy, $dataType->fields())) {
                    $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                    $dataTypeContent = call_user_func([
                        $query->orderBy($orderBy, $querySortOrder),
                        $getter,
                    ]);
                } elseif ($model->timestamps) {
                    $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
                } else {
                    $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
                }

                // Replace relationships' keys for labels and create READ links if a slug is provided.
                $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
            } else {
                // If Model doesn't exist, get data from table name
                $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
                $model = false;
            }

            // Check if BREAD is Translatable
            if (($isModelTranslatable = is_bread_translatable($model))) {
                $dataTypeContent->load('translations');
            }

            // Check if server side pagination is enabled
            $isServerSide = isset($dataType->server_side) && $dataType->server_side;

            // Check if a default search key is set
            $defaultSearchKey = $dataType->default_search_key ?? null;

            $appointmentsGroupFeedbackPending = $dataTypeContent;


            

            // ******************* open appointment
            // GET THE DataType based on the slug
            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // GET ALL the Users
            $users = User::all();

            // Check permission
            $this->authorize('browse', app($dataType->model_name));

            $getter = $dataType->server_side ? 'paginate' : 'get';

            // Row data and options
            foreach ($dataType->addRows as $key => $row) {
                $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
            };

            $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];
            $searchable = $dataType->server_side ? array_keys(SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray()) : '';
            $orderBy = $request->get('order_by', $dataType->order_column);
            $sortOrder = $request->get('sort_order', null);
            $usesSoftDeletes = false;
            $showSoftDeleted = false;
            $orderColumn = [];
            if ($orderBy) {
                $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + 1;
                $orderColumn = [[$index, 'desc']];
                if (!$sortOrder && isset($dataType->order_direction)) {
                    $sortOrder = $dataType->order_direction;
                    $orderColumn = [[$index, $dataType->order_direction]];
                } else {
                    $orderColumn = [[$index, 'desc']];
                }
            }

            // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
            if (strlen($dataType->model_name) != 0) {
                $model = app($dataType->model_name);

                if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
                    $query = $model->{$dataType->scope}();
                } else {
                    $query = $model::whereNotIn('comment_status', ['positive', 'negative', 'not_home', 'processing', 'multi_year_contract', 'wollte k.t'])
                        ->where('meeting_date', '>=', $now);
                }

                // Use withTrashed() if model uses SoftDeletes and if toggle is selected
                if ($model && in_array(SoftDeletes::class, class_uses($model)) && app('VoyagerAuth')->user()->can('delete', app($dataType->model_name))) {
                    $usesSoftDeletes = true;

                    if ($request->get('showSoftDeleted')) {
                        $showSoftDeleted = true;
                        $query = $query->withTrashed();
                    }
                }

                // If a column has a relationship associated with it, we do not want to show that field
                $this->removeRelationshipField($dataType, 'browse');

                if ($orderBy && in_array($orderBy, $dataType->fields())) {
                    $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                    $dataTypeContent = call_user_func([
                        $query->orderBy($orderBy, $querySortOrder),
                        $getter,
                    ]);
                } elseif ($model->timestamps) {
                    $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
                } else {
                    $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
                }

                // Replace relationships' keys for labels and create READ links if a slug is provided.
                $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
            } else {
                // If Model doesn't exist, get data from table name
                $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
                $model = false;
            }

            // Check if BREAD is Translatable
            if (($isModelTranslatable = is_bread_translatable($model))) {
                $dataTypeContent->load('translations');
            }

            // Check if server side pagination is enabled
            $isServerSide = isset($dataType->server_side) && $dataType->server_side;

            // Check if a default search key is set
            $defaultSearchKey = $dataType->default_search_key ?? null;

            $appointmentsGroupOpen = $dataTypeContent;





            //*************************************  closed appointment
            // GET THE DataType based on the slug
            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // GET ALL the Users
            $users = User::all();

            // Check permission
            $this->authorize('browse', app($dataType->model_name));

            $getter = $dataType->server_side ? 'paginate' : 'get';

            // Row data and options
            foreach ($dataType->addRows as $key => $row) {
                $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
            };

            $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];
            $searchable = $dataType->server_side ? array_keys(SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray()) : '';
            $orderBy = $request->get('order_by', $dataType->order_column);
            $sortOrder = $request->get('sort_order', null);
            $usesSoftDeletes = false;
            $showSoftDeleted = false;
            $orderColumn = [];
            if ($orderBy) {
                $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + 1;
                $orderColumn = [[$index, 'desc']];
                if (!$sortOrder && isset($dataType->order_direction)) {
                    $sortOrder = $dataType->order_direction;
                    $orderColumn = [[$index, $dataType->order_direction]];
                } else {
                    $orderColumn = [[$index, 'desc']];
                }
            }

            // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
            if (strlen($dataType->model_name) != 0) {
                $model = app($dataType->model_name);

                if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
                    $query = $model->{$dataType->scope}();
                } else {
                    $query = $model::select('*')
                        ->whereNotNull('comment_status');
                }

                // Use withTrashed() if model uses SoftDeletes and if toggle is selected
                if ($model && in_array(SoftDeletes::class, class_uses($model)) && app('VoyagerAuth')->user()->can('delete', app($dataType->model_name))) {
                    $usesSoftDeletes = true;

                    if ($request->get('showSoftDeleted')) {
                        $showSoftDeleted = true;
                        $query = $query->withTrashed();
                    }
                }

                // If a column has a relationship associated with it, we do not want to show that field
                $this->removeRelationshipField($dataType, 'browse');

                if ($orderBy && in_array($orderBy, $dataType->fields())) {
                    $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                    $dataTypeContent = call_user_func([
                        $query->orderBy($orderBy, $querySortOrder),
                        $getter,
                    ]);
                } elseif ($model->timestamps) {
                    $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
                } else {
                    $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
                }

                // Replace relationships' keys for labels and create READ links if a slug is provided.
                $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
            } else {
                // If Model doesn't exist, get data from table name
                $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
                $model = false;
            }

            // Check if BREAD is Translatable
            if (($isModelTranslatable = is_bread_translatable($model))) {
                $dataTypeContent->load('translations');
            }

            // Check if server side pagination is enabled
            $isServerSide = isset($dataType->server_side) && $dataType->server_side;

            // Check if a default search key is set
            $defaultSearchKey = $dataType->default_search_key ?? null;

            $appointmentsGroupClosed = $dataTypeContent;

            $view = 'voyager::bread.browse';

            if (view()->exists("voyager::$slug.browse")) {
                $view = "voyager::$slug.browse";
            }

            return Voyager::view($view, compact(
                'dataType',
                'dataTypeContent',
                'isModelTranslatable',
                'search',
                'orderBy',
                'orderColumn',
                'sortOrder',
                'searchable',
                'isServerSide',
                'defaultSearchKey',
                'usesSoftDeletes',
                'showSoftDeleted',
                'users',
                'appointmentsGroupFeedbackPending',
                'appointmentsGroupOpen',
                'appointmentsGroupClosed'
            ));







        } else {
            // GET THE DataType based on the slug
            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // GET ALL the Users
            $users = User::all();

            // Check permission
            $this->authorize('browse', app($dataType->model_name));

            $getter = $dataType->server_side ? 'paginate' : 'get';

            // Row data and options
            foreach ($dataType->addRows as $key => $row) {
                $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
            };

            $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];
            $searchable = $dataType->server_side ? array_keys(SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray()) : '';
            $orderBy = $request->get('order_by', $dataType->order_column);
            $sortOrder = $request->get('sort_order', null);
            $usesSoftDeletes = false;
            $showSoftDeleted = false;
            $orderColumn = [];
            if ($orderBy) {
                $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + 1;
                $orderColumn = [[$index, 'desc']];
                if (!$sortOrder && isset($dataType->order_direction)) {
                    $sortOrder = $dataType->order_direction;
                    $orderColumn = [[$index, $dataType->order_direction]];
                } else {
                    $orderColumn = [[$index, 'desc']];
                }
            }

            // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
            if (strlen($dataType->model_name) != 0) {
                $model = app($dataType->model_name);

                if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
                    $query = $model->{$dataType->scope}();
                } else {
                    $query = $model::select('*');
                }

                // Use withTrashed() if model uses SoftDeletes and if toggle is selected
                if ($model && in_array(SoftDeletes::class, class_uses($model)) && app('VoyagerAuth')->user()->can('delete', app($dataType->model_name))) {
                    $usesSoftDeletes = true;

                    if ($request->get('showSoftDeleted')) {
                        $showSoftDeleted = true;
                        $query = $query->withTrashed();
                    }
                }

                // If a column has a relationship associated with it, we do not want to show that field
                $this->removeRelationshipField($dataType, 'browse');

                if ($orderBy && in_array($orderBy, $dataType->fields())) {
                    $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                    $dataTypeContent = call_user_func([
                        $query->orderBy($orderBy, $querySortOrder),
                        $getter,
                    ]);
                } elseif ($model->timestamps) {
                    $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
                } else {
                    $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
                }

                // Replace relationships' keys for labels and create READ links if a slug is provided.
                $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
            } else {
                // If Model doesn't exist, get data from table name
                $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
                $model = false;
            }

            // Check if BREAD is Translatable
            if (($isModelTranslatable = is_bread_translatable($model))) {
                $dataTypeContent->load('translations');
            }

            // Check if server side pagination is enabled
            $isServerSide = isset($dataType->server_side) && $dataType->server_side;

            // Check if a default search key is set
            $defaultSearchKey = $dataType->default_search_key ?? null;

            $view = 'voyager::bread.browse';

            if (view()->exists("voyager::$slug.browse")) {
                $view = "voyager::$slug.browse";
            }

            $appointmentsGroupFeedbackPending = [];
            $appointmentsGroupOpen = [];
            $appointmentsGroupClosed = [];
            if (strtolower(auth()->user()->role->name) == 'sales_agent') {

                foreach ($dataTypeContent as $key => $appointment) {
                    $now = now()->toDateString();

                    if (isset($appointment->meeting_date)) {
                        $meeting_date = $appointment->meeting_date->toDateString();
                        // group feedback pending

                        if (!isset($appointment->comment_status) && ($meeting_date < $now)) {
                            array_push($appointmentsGroupFeedbackPending, $appointment);
                        } // group appointment open 
                        elseif (!isset($appointment->comment_status) && ($meeting_date >= $now)) {
                            array_push($appointmentsGroupOpen, $appointment);
                        } elseif (isset($appointment->comment_status)) {
                            if ($appointment->comment_status == 'open' && ($meeting_date >= $now)) {
                                // group appointment open 
                                array_push($appointmentsGroupOpen, $appointment);
                            } else {
                                // group closed appointments
                                array_push($appointmentsGroupClosed, $appointment);
                            }
                        }
                    }
                }
            }

            return Voyager::view($view, compact(
                'dataType',
                'dataTypeContent',
                'isModelTranslatable',
                'search',
                'orderBy',
                'orderColumn',
                'sortOrder',
                'searchable',
                'isServerSide',
                'defaultSearchKey',
                'usesSoftDeletes',
                'showSoftDeleted',
                'users',
                'appointmentsGroupFeedbackPending',
                'appointmentsGroupOpen',
                'appointmentsGroupClosed'
                // 'paginatedItemsFeedback',
                // 'paginatedItemsOpen',
                // 'paginatedItemsClosed'
            ));
        }
       
    }

    //***************************************
    //
    //                   /\
    //                  /  \
    //                 / /\ \
    //                / ____ \
    //               /_/    \_\
    //
    //
    // Add a new item of our Data Type BRE(A)D
    //
    //****************************************

    public function create(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = (strlen($dataType->model_name) != 0)
            ? new $dataType->model_name()
            : false;

        foreach ($dataType->addRows as $key => $row) {
            $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        // Get all users 
        $users = User::all();

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'users'));
    }

    /**
     * Bulk assignement of an agent to appointments
     * 
     * @param Request, 
     * @return Request
     */
    public function update_bulk(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        event(new BreadDataUpdated($dataType, $data));

        return redirect()
            ->route("voyager.{$dataType->slug}.index")
            ->with([
                'message'    => __('voyager::generic.successfully_updated') . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        if(strtolower(auth()->user()->role->name) == 'sales_agent') {
            $request->validate([
                'comment_status' => 'required',
                'sales_visit_location' => 'required'
            ]);
        };
        
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // agents can only save an appoitment if geolocation 
        // is performed and conversion status is made
        if (strtolower(auth()->user()->role->name) == 'sales_agent') {
            $validatedData = $request->validate([
                'comment_status' => 'required',
                'sales_visit_location' => 'required'
            ]);
        }

        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        }

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        event(new BreadDataUpdated($dataType, $data));

        return redirect()
            ->route("voyager.{$dataType->slug}.index")
            ->with([
                'message'    => __('voyager::generic.successfully_updated') . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }

}
