@php
    $currentLogedInUserRole =strtolower(auth()->user()->role->name);
@endphp
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
                    <th>Feedback</th>
                    @if ($currentLogedInUserRole == 'superadmin')
                        <th>Call center</th>
                    @endif
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
                                @include('voyager::bread.partials.actions', ['action' => $action])
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <appointments-modal-btn :appointment-id="{{ $data->getKey() }}" @open-comments-modal="openCommentsModal"></appointments-modal-btn>
                    </td>
                    @if ($currentLogedInUserRole == 'superadmin')
                        <td>
                            @php
                                $model = app('App\User');
                                if(isset($data->created_by)) {
                                    $createdBy = $model::where('id' , '=', $data->created_by)->first();
                                    if(isset($createdBy->role)) {
                                        if(strtolower($createdBy->role->name) == 'call_agent' || strtolower($createdBy->role->name) == 'call_center_manager') {
                                            if(isset($createdBy->callCenter->name)) {
                                            echo $createdBy->callCenter->name;
                                            }
                                        }
                                    }
                                }
                            @endphp 
                        </td>
                    @endif
                @endif
                @php
                if ($data->{$row->field.'_browse'}) {
                    $data->{$row->field} = $data->{$row->field.'_browse'};
                }
                @endphp
                <td class="no-sort no-click" @if ($row->field == "canton_city" or $row->field == 'meeting_time')
                    {!! 'bgcolor="#62a8ea" style="color: #fff"'  !!}
                @endif>

                    @if ($row->field == 'sales_agent_id')
                        @php
                            $model = app('App\User');
                            $users2 = $model::where('id' , '=', $data->{$row->field})->get();
                        @endphp 
                        @foreach ($users2 as $user)
                            <span>{{ $user->user_name }}</span>
                        @endforeach
                        @continue
                    @endif 

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
                            <span>{{ $data->{$row->field} }}</span>
                    @endif
                </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>