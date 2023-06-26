<textarea class="form-control" name="text_comment" rows="{{ $options->display->rows ?? 5 }}">{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}</textarea>
