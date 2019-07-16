@php
    $options = (array)$options;
    $roleOptions = (isset($options['roleOptions']) ? (array)$options['roleOptions'] : '');
    if($roleOptions != '') {
        $roles = (array)$roleOptions['readonly'];
        $noRoles = false;
    } else {
        $noRoles = true;
    }
@endphp
<textarea 
    @if($row->required == 1) required @endif 
    class="form-control" 
    name="{{ $row->field }}" 
    rows="{{ $options->display->rows ?? 5 }}"
    @if (!$noRoles) 
        @foreach ($roles as $readonlyUserRole)
            @if ($currentUserRole == strtolower($readonlyUserRole))
                {{ 'readonly' }}
            @endif      
        @endforeach
    @endif
    >{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}</textarea>
