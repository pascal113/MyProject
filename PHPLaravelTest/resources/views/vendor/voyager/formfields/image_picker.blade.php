@php
    $required = $row->required ? true : false;
@endphp

<div id="image-formfield">
    <image-formfield inline-template>
        <voyager-formfields-images
            :csrf-token="'{{ csrf_token() }}'"
            name="{{ $row->field }}"
            :options='{"required": @json($required)}'
            :required='@json($required)'
            :row='@json($row)'
            old='{{ $dataTypeContent->{$row->field} ?? old($row->field) }}'
        />
    </image-formfield>
</div>

@push('javascript')
    <script>
        Vue.component('image-formfield', {
            props: {
            },
        });

        var imagePickerVm = new Vue({ el: '#image-formfield' });
    </script>
@endpush
