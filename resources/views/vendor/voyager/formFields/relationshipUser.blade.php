@if(isset($options->model) && isset($options->type))

    @if(class_exists($options->model))

        @php $relationshipField = $row->field; @endphp

        @if($options->type == 'belongsTo')
    
            @if(isset($view) && ($view == 'browse' || $view == 'read'))

                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;
                    $model = app($options->model);
                    $query = $model::where($options->key,$relationshipData->{$options->column})->first();
                @endphp

                @if(isset($query))
                    <p>{{ $query->{$options->label} }}</p>
                @else
                    <p>{{ __('voyager::generic.no_results') }}</p>
                @endif

            @else
                
                @php
                    $currentUserRole = auth()->user()->role->name;

                    $model = app($options->model);
                    $query = $model::where($options->key, $dataTypeContent->{$options->column})->get();
                @endphp

                @if ($currentUserRole == 'SuperAdmin')
                    <select
                        class="form-control select2-ajax" name="{{ $options->column }}"
                        data-get-items-route="{{route('voyager.' . $dataType->slug.'.relation')}}"
                        data-get-items-field="{{$row->field}}"
                        data-method="{{ isset($dataTypeContent) ? 'edit' : 'add' }}"
                    >
                        @if(!$row->required)
                            <option value="">{{__('voyager::generic.none')}}</option>
                        @endif

                        @foreach($query as $relationshipData)
                            <option value="{{ $relationshipData->{$options->key} }}" @if($dataTypeContent->{$options->column} == $relationshipData->{$options->key}){{ 'selected="selected"' }}@endif>{{ $relationshipData->{$options->label} }}</option>
                        @endforeach
                    
                    </select>
                    
                @else
                    <select
                    class="form-control " name="{{ $options->column }}"
                    data-get-items-field="{{$row->field}}"
                >
                    @foreach($query as $relationshipData)
                        @if ($dataTypeContent->{$options->column} == $relationshipData->{$options->key})
                            <option value="{{ $relationshipData->{$options->key} }}" @if($dataTypeContent->{$options->column} == $relationshipData->{$options->key}){{ 'selected="selected"' }}@endif>{{ $relationshipData->{$options->label} }}</option>
                        @endif
                    @endforeach
                </select>
                @endif
                
            @endif

        @endif

    @else

        cannot make relationship because {{ $options->model }} does not exist.

    @endif

@endif
