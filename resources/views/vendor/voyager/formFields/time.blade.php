<input 
    @if($row->required == 1) required @endif 
    type="time"  
    data-name="{{ $row->display_name }}"  
    class="form-control datepicker--time-only" 
    name="{{ $row->field }}"
    placeholder="{{ old($row->field, $row->details->placeholder ?? $row->display_name) }}"
        {!! isBreadSlugAutoGenerator($row->details) !!}
    value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $row->details->default ?? '') }}"
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
    @if (!$noRoles) 
        @foreach ($roles as $readonlyUserRole)
            @if ($currentUserRole == strtolower($readonlyUserRole))
                {{ 'readonly' }}
            @endif      
        @endforeach
    @endif
>
