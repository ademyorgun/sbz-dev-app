<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController as BaseVoyagerBaseController;

class AppointmentsFilter extends BaseVoyagerBaseController
{
    /**
     * Filter data and send back a rendered
     * view 
     * 
     * @param Illuminate\Http\Request
     */
    public function index(Request $request)
    {
        // GET THE REQUEST DATA to use as filter
        $isAgentView = $request->input('isAgentView');
    
        // GET THE SLUG
        $slug = 'appointments';
        
        // Different data for different views
        if ($isAgentView) {
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
                        ->where('meeting_date', '<', $now)
                        ->FilterAppointments($request);
                } else {
                    $query = $model::whereNull('comment_status')
                        ->where('meeting_date', '<', $now)
                        ->FilterAppointments($request);
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
                    $query = $model->{$dataType->scope}()
                        ->where('meeting_date', '>=', $now)
                        ->whereNull('comment_status')
                        ->orWhere('comment_status', 'open')
                        ->FilterAppointments($request);
                } else {
                    $query = $model::where('meeting_date', '>=', $now)
                        ->whereNull('comment_status')
                        ->orWhere('comment_status', 'open')
                        ->FilterAppointments($request);
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
                    $query = $model->{$dataType->scope}()
                        ->where('comment_status', '!=', 'open')
                        ->FilterAppointments($request);
                } else {
                    $query = $model::where('comment_status', '!=', 'open')
                        ->FilterAppointments($request);
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

            $view = 'vendor.voyager.inc.browse_agent_table';

            // render the table
            $disableActions = false;
            $dataTypeContent = $appointmentsGroupFeedbackPending;
            $feedbackOpen = Voyager::view($view, compact(
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
                'disableActions'
            ))->render();

            // render the table
            $dataTypeContent = $appointmentsGroupOpen;
            $openAppointments = Voyager::view($view, compact(
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
                'disableActions'
            ))->render();

            // render the table
            $disableActions = true;
            $dataTypeContent = $appointmentsGroupClosed;
            $closedAppointments = Voyager::view($view, compact(
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
                'disableActions'
            ))->render();


            return response()->json([
                'feedbackOpen' => $feedbackOpen,
                'openAppointments' => $openAppointments,
                'closedAppointments' => $closedAppointments,
                'feedbackOpenPaginator' => $appointmentsGroupFeedbackPending,
                'openAppointmentsPaginator' => $appointmentsGroupOpen,
                'closedAppointmentsPaginator' => $appointmentsGroupClosed
            ]); 

        // Not sales agent view
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

                // If we are using model scoping
                if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
                    $query = $model->{$dataType->scope}()->FilterAppointments($request);
                } else {
                    $query = $model::FilterAppointments($request);
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

            $view = 'vendor.voyager.appointments.table';

            // render the table
            $table = Voyager::view($view, compact(
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
                'users'
            ))->render();

            // render the paginator
            $paginator = view('vendor.voyager.inc.paginator', compact(
                'dataTypeContent',
                'search',
                'orderBy',
                'sortOrder',
                'showSoftDeleted'
            ))->render();

            return response()->json([
                'table' => $table,
                'paginator' => $paginator,
                'dataTypeContent' => $dataTypeContent
            ]);
        }
    }
}
