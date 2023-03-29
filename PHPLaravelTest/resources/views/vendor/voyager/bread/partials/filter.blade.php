@php
    $id = 'filters['.$filter->field.']';
@endphp

<label for="{{ $id }}">{{ $filter->label }}</label>

@if ($filter->type === 'select_dropdown')
    <select id="{{ $id }}" name="{{ $id }}" class="form-control input-sm">
        @foreach ($filter->options as $value => $label)
            <option value="{{ $value }}" @if (request()->input('filters.'.$filter->field, $filter->defaultValue) === $value) selected @endif>{{ $label }}</option>
        @endforeach
    </select>
@elseif ($filter->type === 'timestamp')
    <input
        id="{{ $id }}"
        name="{{ $id }}"
        type="datetime"
        class="form-control datepicker"
        value="{{ request()->input('filters.'.$filter->field, $filter->defaultValue) }}">
@endif
