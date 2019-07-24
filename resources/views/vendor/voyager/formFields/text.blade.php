<input 
    @if($row->required == 1) required @endif 
    type="text" class="form-control" name="{{ $row->field }}"
    placeholder="{{ old($row->field, $options->placeholder ?? $row->display_name) }}"
    {{-- {!! isBreadSlugAutoGenerator($options) !!} --}}
    value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}"
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
            @if (strtolower($currentUserRole) == strtolower($readonlyUserRole))
                {{ 'readonly' }}
            @endif      
        @endforeach
    @endif
       >
