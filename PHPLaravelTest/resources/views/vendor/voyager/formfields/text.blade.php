<input @if($row->required == 1) required @endif type="text" class="form-control" name="{{ $row->field }}"
        placeholder="{{ old($row->field, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
       {!! isBreadSlugAutoGenerator($options) !!}
       {!! outputAriaForHelpterText($row) !!}
       value="{{ old($row->field, $dataTypeContent->{$row->field.'_edit'} ?? $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
