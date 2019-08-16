<textarea 
    rows="5"
    @if($row->required == 1) required @endif 
    class="form-control" name="{{ $row->field }}"
    placeholder="{{ old($row->field, $options->placeholder ?? $row->display_name) }}"
    {{-- {!! isBreadSlugAutoGenerator($options) !!} --}}
    value="{{ isset($dataTypeContent->{$row->field}) ? $dataTypeContent->{$row->field} : '' }}"
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

    {{--   we want the users with role sales agents to be able to edit this field for one time only --}}
    @if(isset($edit))
        @if($edit)
            @if(strtolower(auth()->user()->role->name) == 'sales_agent' && !isset($dataTypeContent->{$row->field}))
                // editable
            @else
                {{ 'readonly' }}
            @endif
        @endif
    @endif
       >{{ isset($dataTypeContent->{$row->field}) ? $dataTypeContent->{$row->field} : '' }}
</textarea>
