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
<input
    class="form-control"
    name="{{ $row->field }}"
    pattern="[0-9]*"
    type="tel"
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
