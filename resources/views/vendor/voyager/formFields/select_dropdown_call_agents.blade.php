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
    $role = app('App\Role')::where('name', 'call_agent')->first();
    $users = $model::where('role_id', $role->id)->get();
@endphp  
@if (!$noRoles) 
    @foreach ($roles as $readonlyUserRole)
        @if ($currentUserRole == strtolower($readonlyUserRole))
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
                <option value="{{ $user->id }}"@if((string)$selected_value == (string)$user->id){{ 'selected="selected"' }}@endif>{{ $user->user_name }}</option>
            @endforeach
            
        </select>
    @endif
@else 
    <select class="form-control select2" name="{{ $row->field }}">
        <option disabled selected value> -- Select an agent -- </option>
        @foreach($users as $key => $user)
            <option value="{{ $user->id }}"@if( (string)$selected_value == (string)$user->id){{ 'selected="selected"' }}@endif>{{ $user->user_name }}</option>
        @endforeach
    </select>
@endif
