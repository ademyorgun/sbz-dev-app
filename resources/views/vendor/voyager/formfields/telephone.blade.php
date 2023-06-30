<input type="telephone"
class="form-control"
name="{{ $row->field }}"
type="tel"
@if($row->required == 1) required @endif
placeholder="{{ old($row->field, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
