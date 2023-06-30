<input
       class="form-control"
       name="number_positive"
       type="number"
       @if($row->required == 1) required @endif
       min="0"
       step="{{ $options->step ?? 'any' }}"
       placeholder="{{ old($row->field, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
       value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
