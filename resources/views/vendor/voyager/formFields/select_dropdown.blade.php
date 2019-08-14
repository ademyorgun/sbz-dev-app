@php
    $optionsArray = isset($options) ? clone $options: null;
    if($optionsArray != null) {
        $optionsArray = (array)$optionsArray;
        $roleOptions = (isset($optionsArray['roleOptions']) ? (array)$optionsArray['roleOptions'] : '');
        $currentUserRoleIsNotListed = false;
        if($roleOptions != '') {
            $roles = (array)$roleOptions['readonly'];
            $noRoles = false;
        } else {
            $noRoles = true;
        }
    }  else {
        $noRoles = true;
    }
    
@endphp

@php
    
@endphp
@if(isset($options->relationship))

    {{-- If this is a relationship and the method does not exist, show a warning message --}}
    @if( !method_exists( $dataType->model_name, camel_case($row->field) ) )
        <p class="label label-warning"><i class="voyager-warning"></i> {{ __('voyager::form.field_select_dd_relationship', ['method' => camel_case($row->field).'()', 'class' => $dataType->model_name]) }}</p>
    @endif

    @if( method_exists( $dataType->model_name, camel_case($row->field) ) )
        @if(isset($dataTypeContent->{$row->field}) && !is_null(old($row->field, $dataTypeContent->{$row->field})))
            <?php $selected_value = old($row->field, $dataTypeContent->{$row->field}); ?>
        @else
            <?php $selected_value = old($row->field); ?>
        @endif

        @if (!$noRoles) 
        <?php $default = (isset($options->default) && !isset($dataTypeContent->{$row->field})) ? $options->default : null; ?>
            @foreach ($roles as $readonlyUserRole)
                @if ($currentUserRole == strtolower($readonlyUserRole))
                    <select name="{{ $row->field }}" class="form-control" disabled="disabled">
                        @if(isset($options->options))
                            <optgroup label="{{ __('voyager::generic.custom') }}">
                            @foreach($options->options as $key => $option)
                                @if ((string)$selected_value == (string)$key)
                                    <option value="{{ ($key == '_empty_' ? '' : $key) }}" @if($default == $key && $selected_value === NULL){{ 'selected="selected"' }}@endif @if((string)$selected_value == (string)$key){{ 'selected="selected"' }}@endif>{{ $option }}</option>
                                @endif
                            @endforeach
                            </optgroup>
                        @endif   
                    </select>
                @endif      
            @endforeach
        @else 
            <select class="form-control select2" name="{{ $row->field }}">
                <?php $default = (isset($options->default) && !isset($dataTypeContent->{$row->field})) ? $options->default : null; ?>

                @if(isset($options->options))
                    <optgroup label="{{ __('voyager::generic.custom') }}">
                    @foreach($options->options as $key => $option)
                        <option value="{{ ($key == '_empty_' ? '' : $key) }}" @if($default == $key && $selected_value === NULL){{ 'selected="selected"' }}@endif @if((string)$selected_value == (string)$key){{ 'selected="selected"' }}@endif>{{ $option }}</option>
                    @endforeach
                    </optgroup>
                @endif
                {{-- Populate all options from relationship --}}
                <?php
                $relationshipListMethod = camel_case($row->field) . 'List';
                if (method_exists($dataTypeContent, $relationshipListMethod)) {
                    $relationshipOptions = $dataTypeContent->$relationshipListMethod();
                } else {
                    $relationshipClass = $dataTypeContent->{camel_case($row->field)}()->getRelated();
                    if (isset($options->relationship->where)) {
                        $relationshipOptions = $relationshipClass::where(
                            $options->relationship->where[0],
                            $options->relationship->where[1]
                        )->get();
                    } else {
                        $relationshipOptions = $relationshipClass::all();
                    }
                }

                // Try to get default value for the relationship
                // when default is a callable function (ClassName@methodName)
                if ($default != null) {
                    $comps = explode('@', $default);
                    if (count($comps) == 2 && method_exists($comps[0], $comps[1])) {
                        $default = call_user_func([$comps[0], $comps[1]]);
                    }
                }
                ?>

                <optgroup label="{{ __('voyager::database.relationship.relationship') }}">
                @foreach($relationshipOptions as $relationshipOption)
                    <option value="{{ $relationshipOption->{$options->relationship->key} }}" @if($default == $relationshipOption->{$options->relationship->key} && $selected_value === NULL){{ 'selected="selected"' }}@endif @if($selected_value == $relationshipOption->{$options->relationship->key}){{ 'selected="selected"' }}@endif>{{ $relationshipOption->{$options->relationship->label} }}</option>
                @endforeach
                </optgroup>
            </select>
        @endif
        
    @else
        <select class="form-control select2" name="{{ $row->field }}"></select>
    @endif
@else
    <?php $selected_value = (isset($dataTypeContent->{$row->field}) && !is_null(old($row->field, $dataTypeContent->{$row->field}))) ? old($row->field, $dataTypeContent->{$row->field}) : old($row->field); ?>
    <?php $default = (isset($options->default) && !isset($dataTypeContent->{$row->field})) ? $options->default : null; ?>
    @if (!$noRoles) 
        @foreach ($roles as $readonlyUserRole)
            @if ($currentUserRole == strtolower($readonlyUserRole))
                @php
                    $fieldValue = null;
                    foreach($options->options  as $key => $option) {
                        if((string)$selected_value == (string)$key) {
                            $fieldValue = ($key == '_empty_' ? '' : $key);
                        }
                    }
                @endphp

                {{-- we add the input type text because when the select field is disabled the 
                value is deleted from our db --}}
                <input type="hidden" name="{{ $row->field }}" value="{{ $fieldValue }}">

                {{-- then we show the disabled field --}}
                <select name="{{ $row->field }}" class="form-control" disabled="disabled">
                    @if(isset($options->options))
                        <optgroup label="{{ __('voyager::generic.custom') }}">
                        @foreach($options->options as $key => $option)
                            @if ((string)$selected_value == (string)$key)
                                <option value="{{ ($key == '_empty_' ? '' : $key) }}" @if($default == $key && $selected_value === NULL){{ 'selected="selected"' }}@endif @if((string)$selected_value == (string)$key){{ 'selected="selected"' }}@endif>{{ $option }}</option>
                            @endif
                        @endforeach
                        </optgroup>
                    @endif   
                </select>
                @php
                    $currentUserRoleIsNotListed = false;
                @endphp
                @break
            @else
                @php
                    $currentUserRoleIsNotListed = true;
                @endphp
            @endif        
        @endforeach
        @if ($currentUserRoleIsNotListed)
            <select class="form-control select2" name="{{ $row->field }}">
                @if(isset($options->options))
                    @foreach($options->options as $key => $option)
                        <option value="{{ $key }}" @if($default == $key && $selected_value === NULL){{ 'selected="selected"' }}@endif @if($selected_value == $key){{ 'selected="selected"' }}@endif>{{ $option }}</option>
                    @endforeach
                @endif
            </select>
        @endif
    @else 
        <select class="form-control select2" name="{{ $row->field }}">
            @if(isset($options->options))
                @foreach($options->options as $key => $option)
                    <option value="{{ $key }}" @if($default == $key && $selected_value === NULL){{ 'selected="selected"' }}@endif @if($selected_value == $key){{ 'selected="selected"' }}@endif>{{ $option }}</option>
                @endforeach
            @endif
        </select>
    @endif
@endif
