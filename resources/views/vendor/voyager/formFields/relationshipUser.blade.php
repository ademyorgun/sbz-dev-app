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
                
                <select
                    class="form-control " name="{{ $options->column }}"
                    data-get-items-field="{{$row->field}}"
                >
                    <option value="{{ auth()->user()->id }}">{{ auth()->user()->user_name }}</option>

                </select>


            @endif

        @endif

    @else

        cannot make relationship because {{ $options->model }} does not exist.

    @endif

@endif
