@if ($view ?? null === 'read')
    @if ($dataTypeContent->shipping_full_name.$dataTypeContent->shipping_address.$dataTypeContent->shipping_city.$dataTypeContent->shipping_state.$dataTypeContent->shipping_zip.$dataTypeContent->shipping_phone)
        <p>
            {{ $dataTypeContent->shipping_full_name }}<br />
            {{ $dataTypeContent->shipping_address }}<br />
            {{ $dataTypeContent->shipping_city }}, {{ $dataTypeContent->shipping_state }} {{ $dataTypeContent->shipping_zip }}
        </p>
        <p>{{ $dataTypeContent->shipping_phone }}</p>
    @else
        <p>No shipping address.</p>
    @endif
@endif
