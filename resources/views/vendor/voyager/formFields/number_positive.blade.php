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
    type="number"
    class="form-control"
    name="{{ $row->field }}"
    type="number"
    min="0" 
    oninput="validity.valid||(value='');"
    @if($row->required == 1) required @endif
    @if(isset($options->min)) min="{{ $options->min }}" @endif
    @if(isset($options->max)) max="{{ $options->max }}" @endif
    step="{{ $options->step ?? 'any' }}"
    placeholder="{{ old($row->field, $options->placeholder ?? $row->display_name) }}"
    value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}"
    @if (!$noRoles) 
        @foreach ($roles as $readonlyUserRole)
            @if ($currentUserRole == strtolower($readonlyUserRole))
                {{ 'readonly' }}
            @endif      
        @endforeach
    @endif
    >
