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
                @if ($add)
                    @php
                    $currentUserRole = auth()->user()->role->name;

                    $model = app($options->model);
                    $query = $model::where($options->key, $dataTypeContent->{$options->column})->get();
                    @endphp

                    <input type="hidden" name="{{ $options->column }}" value="{{ auth()->user()->id }}">
                    <select
                        class="form-control " name="{{ $options->column }}"
                        data-get-items-field="{{$row->field}}"
                        disabled
                    >
                        <option value="{{ auth()->user()->id }}">{{ auth()->user()->user_name }}</option>
                    </select> 
                @else 
                    @if (!is_null($dataTypeContent->{$options->column}))
                        @php
                            $model = app($options->model);
                            $query = $model::where($options->key, $dataTypeContent->{$options->column})->first();
                        @endphp
                        <input type="hidden" name="{{ $options->column }}" value="{{ $dataTypeContent->{$options->column} }}">
                            <select
                            class="form-control " name="{{ $options->column }}"
                            data-get-items-field="{{$row->field}}"
                            disabled
                        >
                            <option value="{{ $dataTypeContent->{$options->column} }}">{{ $query->user_name }}</option>
                        </select> 
                    @endif
                @endif
                
                
            @endif

        @endif

    @else

        cannot make relationship because {{ $options->model }} does not exist.

    @endif

@endif

