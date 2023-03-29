@php
    $required = $row->required ? true : false;
@endphp

<div id="file-formfield">
    <cloud_file-formfield inline-template>
        <voyager-formfields-cloud_file
            :csrf-token="'{{ csrf_token() }}'"
            name="{{ $row->field }}"
            :options='{"required": @json($required)}'
            :required='@json($required)'
            :row='@json($row)'
            thumbnail='{{ isset($dataTypeContent) ? $dataTypeContent->thumbnail : null }}'
            old='{{ $dataTypeContent->{$row->field} ?? old($row->field) }}'
            thumbnail-url='{{ isset($dataTypeContent) && $dataTypeContent->exists ? route('files.thumbnail', [$dataTypeContent->id]) : null }}'
            file-url='{{ isset($dataTypeContent) && $dataTypeContent->exists ? route('files.file', [$dataTypeContent->id]) : null }}'
        />
    </cloud_file-formfield>
</div>

@push('javascript')
    <script>
        Vue.component('cloud_file-formfield', {
            props: {
            },
        });

        var fileVm = new Vue({ el: '#file-formfield' });
    </script>
@endpush
