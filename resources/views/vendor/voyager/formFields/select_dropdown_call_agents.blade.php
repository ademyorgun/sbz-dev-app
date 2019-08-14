@php
    $options = isset($options) ? $options: null;
    if(isset($options)) {
        $optionsArray = clone $options;
        $optionsArray = (array)$optionsArray;
        $roleOptions = (isset($optionsArray['roleOptions']) ? (array)$optionsArray['roleOptions'] : '');
        $currentUserRoleIsNotListed = false;
        if($roleOptions != '') {
            $roles = (array)$roleOptions['readonly'];
            $noRoles = false;
        } else {
            $noRoles = true;
        }
    } else {
        $noRoles = true;
    }
@endphp

<?php $selected_value = (isset($dataTypeContent->{$row->field}) && !is_null(old($row->field, $dataTypeContent->{$row->field}))) ? old($row->field, $dataTypeContent->{$row->field}) : old($row->field); ?>
@php
    $model = app('App\User');
    $users = $model::all();
@endphp  
@if (!$noRoles) 
    @if (strtolower(auth()->user()->role->name) == 'call_agent')
        <input type="hidden" name="{{ $row->field }}" value="{{ auth()->user()->id }}">
        <select
            class="form-control" 
            disabled
        >
            <option value="{{ auth()->user()->id }}">{{ auth()->user()->user_name }}</option>
        </select> 
    @else 
        @foreach ($roles as $readonlyUserRole)
            @if ($currentUserRole == strtolower($readonlyUserRole))
                @php
                    $fieldValue = null;
                    foreach($users as $key => $user) {
                        if((string)$selected_value == (string)$user->id) {
                            $fieldValue = ($user->id == '_empty_' ? '' : $user->id);
                        }
                    }
                @endphp

                {{-- we add the input type text because when the select field is disabled the 
                value is deleted from our db --}}
                <input type="hidden" name="{{ $row->field }}" value="{{ $fieldValue }}">

                {{-- then we show the disabled field --}}
                <select name="{{ $row->field }}" class="form-control" disabled="disabled">
                    <optgroup label="{{ __('voyager::generic.custom') }}">
                    @foreach($users as $key => $user)
                        @if ((string)$selected_value == (string)$user->id)
                            <option value="{{ ($user->id == '_empty_' ? '' : $user->id) }}"  @if((int)$selected_value == (int)$user->id){{ 'selected="selected"' }}@endif>{{ $user->user_name }}</option>
                        @endif
                    @endforeach
                    </optgroup> 
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
                <option disabled selected value> -- Select an agent -- </option>
                @foreach($users as $key => $user)
                    @if (strtolower($user->role->name) == 'call_agent')
                        <option value="{{ $user->id }}"@if((string)$selected_value == (string)$user->id){{ 'selected="selected"' }}@endif>{{ $user->user_name }}</option>
                    @endif
                @endforeach
            </select>
        @endif
    @endif

@else 
    <select class="form-control select2" name="{{ $row->field }}">
        <option disabled selected value> -- Select an agent -- </option>
        @foreach($users as $key => $user)
            @if (strtolower($user->role->name) == 'call_agent')
                <option value="{{ $user->id }}"@if( (string)$selected_value == (string)$user->id){{ 'selected="selected"' }}@endif>{{ $user->user_name }}</option>
            @endif
        @endforeach
    </select>
@endif

