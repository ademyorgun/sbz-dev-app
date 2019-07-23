@php
    $options = isset($options) ? (array)$options: null;
    $roleOptions = (isset($options['roleOptions']) ? (array)$options['roleOptions'] : '');
    if($roleOptions != '') {
        $roles = (array)$roleOptions['readonly'];
        $noRoles = false;
    } else {
        $noRoles = true;
    }
@endphp
<input 
    type="date" 
    class="form-control datepicker--date-only" name="{{ $row->field }}"
    placeholder="{{ $row->display_name }}"
    
    value="@if(isset($dataTypeContent->{$row->field})){{ \Carbon\Carbon::parse(old($row->field, $dataTypeContent->{$row->field}))->format('d-m-y') }}@else{{old($row->field)}}@endif"
    @if (!$noRoles) 
        @foreach ($roles as $readonlyUserRole)
            @if ($currentUserRole == strtolower($readonlyUserRole))
                {{ 'readonly' }}
            @endif      
        @endforeach
    @endif>
