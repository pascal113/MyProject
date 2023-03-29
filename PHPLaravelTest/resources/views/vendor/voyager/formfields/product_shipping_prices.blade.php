<div id="product-shipping-prices-formfield">
    <product-shipping-prices-formfield inline-template>
        <voyager-formfields-product-shipping-prices
            :csrf-token="'{{ csrf_token() }}'"
            name="{{ $row->name }}"
            :options='@json($row->options)'
            :row='@json($row)'
            :prices='@json($dataTypeContent->shipping_prices)'
        />
    </product-shipping-prices-formfield>
</div>

@push('javascript')
    <script>
        Vue.component('product-shipping-prices-formfield', {
            props: {
            },
        });

        var productShippingPricesVm = new Vue({ el: '#product-shipping-prices-formfield' });
    </script>
@endpush
