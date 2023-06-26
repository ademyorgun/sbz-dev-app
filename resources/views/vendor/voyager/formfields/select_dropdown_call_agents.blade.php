<?php $selected_value = isset($dataTypeContent->{$row->field}) && !is_null(old($row->field, $dataTypeContent->{$row->field})) ? old($row->field, $dataTypeContent->{$row->field}) : old($row->field); ?>
<select class="form-control select2" name="{{ $row->field }}">
    <?php $default = isset($options->default) && !isset($dataTypeContent->{$row->field}) ? $options->default : null; ?>
    @if (isset($options->options))
        @foreach ($options->options as $key => $option)
            <option value="{{ $key }}" @if ($default == $key && $selected_value === null) selected="selected" @endif
                @if ($selected_value == $key) selected="selected" @endif>{{ $option }}</option>
        @endforeach
    @endif
</select>
