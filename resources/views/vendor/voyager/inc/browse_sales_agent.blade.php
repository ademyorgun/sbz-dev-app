<div class="page-content browse container-fluid" id="app">    
    {{-- comments modal --}}
    @include('voyager::alerts')
    <div class="row" >
        <div class="col-md-12">
            <div class="panel panel-primary panelbordered">
                <div class="panel-heading">
                    {{-- appointments filter --}}
                    <h3 class="panel-title panel-icon"><i class="voyager-search"></i>Suche nach</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body mt-2">
                    <appointment-filter @filter="getResults">
                        @foreach ($dataType->addRows as $row)
                            @if ($row->field == 'wanted_expert')
                                <template v-slot:experts>
                                    @foreach ($row->details->options as $key => $option)
                                        <option value="{{$key}}">{{ $option }}</option> 
                                    @endforeach
                                </template>
                            @elseif($row->field == 'canton_city')
                                <template v-slot:cities>
                                    @foreach ($row->details->options as $key => $option)
                                        <option value="{{$key}}">{{ $option }}</option> 
                                    @endforeach
                                </template>
                            @elseif($row->field == 'appointment_belongsto_user_relationship_1')
                                <template v-slot:users>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->user_name }}</option>
                                    @endforeach
                                </template>
                            @endif
                        @endforeach
                    </appointment-filter>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary panel-bordered">
                    <div class="panel-heading">
                        {{-- results --}}
                        <h3 class="panel-title panel-icon"><i class="voyager-list"></i>Resultate</h3>
                        <div class="panel-actions">
                            <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive" id="table">
                            <table id="dataTable" class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        @foreach($dataType->browseRows as $row)
                                            @if ($loop->index == 1)
                                                @can('delete',app($dataType->model_name))
                                                    <th>
                                                        <input type="checkbox" class="select_all">
                                                    </th>
                                                @endcan
                                                <th class="actions text-right">{{ __('voyager::generic.actions') }}</th>
                                            @endif
                                            @if ($row->field == 'call_date')
                                                @continue
                                            @endif
                                            <th>
                                                @if ($isServerSide)
                                                    <div>
                                                @endif
                                                
                                                {{ $row->display_name }}

                                                @if ($isServerSide)
                                                    @if ($row->isCurrentSortField($orderBy))
                                                        @if ($sortOrder == 'asc')
                                                            <i class="voyager-angle-up pull-right"></i>
                                                        @else
                                                            <i class="voyager-angle-down pull-right"></i>
                                                        @endif
                                                    @endif
                                                    </div>
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dataTypeContent as $data)
{{-- 
                                    @php
                                        $appointmentsGroupFeedbackPending = [];
                                        $appointmentsGroupOpen = [];
                                        $appointmentsGroupClosed = [];

                                        foreach ($dataTypeContent as $key => $data) { // data is an appointment
                                            // group feedback pending
                                            $status = $data->appointment_status;
                                            if($status == 'planned' ) {
                                                array_push($appointmentsGroupFeedbackPending, $data);
                                            };

                                            // group appointment open 
                                            if($status == 'zugeteilt' || $status == 'ungeplant') {

                                            }
                                        }
                                    @endphp --}}

                                    <tr>
                                        @foreach($dataType->browseRows as $row)
                                            {{-- To alter the chebox selector to the second column --}}
                                            @if ($loop->index == 1)
                                                @can('delete',app($dataType->model_name))
                                                    <td>
                                                        <input type="checkbox" name="row_id" id="checkbox_{{ $data->getKey() }}" value="{{ $data->getKey() }}">
                                                    </td>
                                                @endcan
                                                <td class="no-sort no-click" id="bread-actions">
                                                    @foreach(Voyager::actions() as $action)
                                                        @if (!method_exists($action, 'massAction'))
                                                            @php $action = new $action($dataType, $data); @endphp
                                                            @if ($action->shouldActionDisplayOnDataType())
                                                                @if ($data)
                                                                    @can ($action->getPolicy(), $data)
                                                                        <a href="{{ $action->getRoute($dataType->name) }}" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                                                                            <i class="{{ $action->getIcon() }}"></i> <span class="hidden-xs hidden-sm">Anruf datum</span> <!-- edit -->
                                                                        </a>
                                                                    @endcan
                                                                @elseif (method_exists($action, 'massAction'))
                                                                    <form method="post" action="{{ route('voyager.'.$dataType->slug.'.action') }}" style="display:inline">
                                                                        {{ csrf_field() }}
                                                                        <button type="submit" {!! $action->convertAttributesToHtml() !!}><i class="{{ $action->getIcon() }}"></i>Anruf datum</button> <!-- edit -->
                                                                        <input type="hidden" name="action" value="{{ get_class($action) }}">
                                                                        <input type="hidden" name="ids" value="" class="selected_ids">
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endif

                                            @if ($row->field == 'call_date')
                                                @continue
                                            @endif

                                            @php
                                            if ($data->{$row->field.'_browse'}) {
                                                $data->{$row->field} = $data->{$row->field.'_browse'};
                                            }
                                            @endphp
                                            <td class="no-sort no-click" @if ($row->field == "canton_city" or $row->field == 'meeting_time')
                                                {!! 'bgcolor="#62a8ea" style="color: #fff"'  !!}
                                            @endif>
                                                {{-- @if (isset($row->details->view))
                                                    @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'action' => 'browse']) --}}
                                                @if($row->type == 'image')
                                                    <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:100px">
                                                @elseif($row->type == 'relationship')
                                                    @include('voyager::formfields.relationship', ['view' => 'browse','options' => $row->details])
                                                @elseif($row->type == 'select_multiple')
                                                    @if(property_exists($row->details, 'relationship'))

                                                        @foreach($data->{$row->field} as $item)
                                                            {{ $item->{$row->field} }}
                                                        @endforeach

                                                    @elseif(property_exists($row->details, 'options'))
                                                        @if (!empty(json_decode($data->{$row->field})))
                                                            @foreach(json_decode($data->{$row->field}) as $item)
                                                                @if (@$row->details->options->{$item})
                                                                    {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ __('voyager::generic.none') }}
                                                        @endif
                                                    @endif

                                                    @elseif($row->type == 'multiple_checkbox' && property_exists($row->details, 'options'))
                                                        @if (@count(json_decode($data->{$row->field})) > 0)
                                                            @foreach(json_decode($data->{$row->field}) as $item)
                                                                @if (@$row->details->options->{$item})
                                                                    {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ __('voyager::generic.none') }}
                                                        @endif

                                                @elseif(($row->type == 'select_dropdown' || $row->type == 'radio_btn') && property_exists($row->details, 'options'))
                                                    {!! $row->details->options->{$data->{$row->field}} ?? '' !!}
                                                @elseif($row->type == 'date' || $row->type == 'timestamp')
                                                    @if (isset($data->{$row->field}))
                                                        {{ property_exists($row->details, 'format') ? \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($row->details->format) : $data->{$row->field} }}
                                                    @endif
                                                @elseif($row->type == 'checkbox')
                                                    @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                                        @if($data->{$row->field})
                                                            <span class="label label-info">{{ $row->details->on }}</span>
                                                        @else
                                                            <span class="label label-primary">{{ $row->details->off }}</span>
                                                        @endif
                                                    @else
                                                    {{ $data->{$row->field} }}
                                                    @endif
                                                @elseif($row->type == 'color')
                                                    <span class="badge badge-lg" style="background-color: {{ $data->{$row->field} }}">{{ $data->{$row->field} }}</span>
                                                @elseif($row->type == 'text')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                @elseif($row->type == 'text_area')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                @elseif($row->type == 'file' && !empty($data->{$row->field}) )
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    @if(json_decode($data->{$row->field}) !== null)
                                                        @foreach(json_decode($data->{$row->field}) as $file)
                                                            <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}" target="_blank">
                                                                {{ $file->original_name ?: '' }}
                                                            </a>
                                                            <br/>
                                                        @endforeach
                                                    @else
                                                        <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($data->{$row->field}) }}" target="_blank">
                                                            Download
                                                        </a>
                                                    @endif
                                                @elseif($row->type == 'rich_text_box')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( strip_tags($data->{$row->field}, '<b><i><u>') ) > 200 ? mb_substr(strip_tags($data->{$row->field}, '<b><i><u>'), 0, 200) . ' ...' : strip_tags($data->{$row->field}, '<b><i><u>') }}</div>
                                                @elseif($row->type == 'coordinates')
                                                    @include('voyager::partials.coordinates-static-image')
                                                @elseif($row->type == 'multiple_images')
                                                    @php $images = json_decode($data->{$row->field}); @endphp
                                                    @if($images)
                                                        @php $images = array_slice($images, 0, 3); @endphp
                                                        @foreach($images as $image)
                                                            <img src="@if( !filter_var($image, FILTER_VALIDATE_URL)){{ Voyager::image( $image ) }}@else{{ $image }}@endif" style="width:50px">
                                                        @endforeach
                                                    @endif
                                                @elseif($row->type == 'media_picker')
                                                    @php
                                                        if (is_array($data->{$row->field})) {
                                                            $files = $data->{$row->field};
                                                        } else {
                                                            $files = json_decode($data->{$row->field});
                                                        }
                                                    @endphp
                                                    @if ($files)
                                                        @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                            @foreach (array_slice($files, 0, 3) as $file)
                                                            <img src="@if( !filter_var($file, FILTER_VALIDATE_URL)){{ Voyager::image( $file ) }}@else{{ $file }}@endif" style="width:50px">
                                                            @endforeach
                                                        @else
                                                            <ul>
                                                            @foreach (array_slice($files, 0, 3) as $file)
                                                                <li>{{ $file }}</li>
                                                            @endforeach
                                                            </ul>
                                                        @endif
                                                        @if (count($files) > 3)
                                                            {{ __('voyager::media.files_more', ['count' => (count($files) - 3)]) }}
                                                        @endif
                                                    @elseif (is_array($files) && count($files) == 0)
                                                        {{ trans_choice('voyager::media.files', 0) }}
                                                    @elseif ($data->{$row->field} != '')
                                                        @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                            <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:50px">
                                                        @else
                                                            {{ $data->{$row->field} }}
                                                        @endif
                                                    @else
                                                        {{ trans_choice('voyager::media.files', 0) }}
                                                    @endif
                                                
                                                @elseif($row->type == 'time')
                                                    @if (isset($data->{$row->field}))
                                                        {{ property_exists($row->details, 'format') ? \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($row->details->format) : $data->{$row->field} }}
                                                    @endif
                                                @else
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    @if ($row->field == 'sales_agent_id')
                                                        @php
                                                            $model = app('App\User');
                                                            $users2 = $model::where('id' , '=', $data->{$row->field})->get();
                                                        @endphp 
                                                        @foreach ($users2 as $user)
                                                        <span>{{ $user->user_name }}</span>
                                                        @endforeach
                                                    @else 
                                                        <span>{{ $data->{$row->field} }}</span>
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($isServerSide)
                            <div class="pull-right">
                                <appointments-paginator :pagination-data="paginationData" @get-results="paginatorChangePage" v-if="isResultsFiltered"></appointments-paginator>
                                {{-- show the server generated paginator before filtering --}}
                                <div v-if="!isResultsFiltered">
                                    {{ $dataTypeContent->appends([
                                        's' => $search->value,
                                        'filter' => $search->filter,
                                        'key' => $search->key,
                                        'order_by' => $orderBy,
                                        'sort_order' => $sortOrder,
                                        'showSoftDeleted' => $showSoftDeleted,
                                    ])->links() }}    
                                </div>

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if ( strtolower(auth()->user()->role->name) == 'superadmin')
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary panelbordered">
                    <div class="panel-heading">
                        <!-- Multiple appointments assignment -->
                        <h3 class="panel-title panel-icon"><i class="voyager-external"></i>Zuteilung der Termine</h3>
                        <div class="panel-actions">
                            <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('voyager.appointment.assign') }}" id="agentAssignementForm" style="margin-top: 1.6em;" method="POST">
                            {{ method_field("POST") }}
                            <!-- CSRF TOKEN -->
                            {{ csrf_field() }}
                            <div class="form-group">
                                {{-- choose an agent --}}
                                <label class="control-lab">Agent auswählen</label>
                                <select
                                    class="form-control"
                                    name="selected_agent_id"
                                    aria-hidden="true"
                                >
                                    <option disabled value selected>Bitte auswählen</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->user_name }}</option>  
                                    @endforeach
                                </select>
                                <input type="hidden" name="selected_ids" value="" class="selected_ids">
                                <div class="invalid-feedback" style="display: none; color: #dc3545;">Select an agent and an appointment(s)</div>
                                <div class="valid-feedback" style="display: none; color: #28a745;">Changes done successfuly, refresh the page to see them</div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary save pull-right">Zuteilen</button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->display_name_singular) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>