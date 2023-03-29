@php $relationshipField = $row->field; @endphp

@if(isset($view) && ($view === 'browse' || $view === 'read'))
    @php
        $applicableProducts = $data->products;
        $isAllProducts = \App\Models\Product::all()->every(function($product) use ($applicableProducts) {
            return $applicableProducts->first(function($applicableProduct) use ($product) {
                return $applicableProduct->id === $product->id;
            });
        });
    @endphp

    @if ($isAllProducts)
        <p>All</p>
    @else
        @if($view === 'browse')
            @php
                if ($hasMore = $applicableProducts->count() > 2) {
                    $applicableProducts = $applicableProducts->take(2);
                }
            @endphp
        @endif

        @forelse ($applicableProducts as $product)
            <li>{{ $product->name }}</li>
        @empty
            <p>None</p>
        @endforelse
        @if ($hasMore)
            <p>...and more</p>
        @endif
    @endif
@else
    <div>
        <input type="checkbox" name="coupon_products_all" id="coupon_products_all" @if (!$dataTypeContent['products']->count()) checked="checked" @endif data-toggle="toggle" data-on="All" data-off="Some">
    </div>

    <div id="{{ $relationshipField }}-wrapper">
        <p class="form-text text-muted" style="margin-top: 10px;">Add all applicabale products</p>

        <select
            class="form-control select2-ajax"
            name="{{ $relationshipField }}[]"
            multiple
            data-get-items-route="{{route('voyager.' . $dataType->slug.'.relation')}}"
            data-get-items-field="{{$row->field}}"
        >
                @php
                    $selectedValues = isset($dataTypeContent) ? (!$dataTypeContent->exists ? $possibleValues : $dataTypeContent->belongsToMany($options->model, $options->pivot_table)->withPivot([ 'product_variant_id' ])->get())->map(function ($item) {
                        if ($item instanceof \App\Models\Product) {
                            return 'product.'.$item->id.($item->pivot->product_variant_id ? '.variant.'.$item->pivot->product_variant_id : '');
                        }

                        return $item->id;
                    })->all() : [];
                @endphp

                @if(!$row->required)
                    <option value="">{{__('voyager::generic.none')}}</option>
                @endif

                @foreach($possibleValues as $possibleValue)
                    <option value="{{ $possibleValue->id }}" @if(in_array($possibleValue->id, $selectedValues)){{ 'selected="selected"' }}@endif>{{ $possibleValue->text }}</option>
                @endforeach
        </select>
    </div>

    @push('javascript')
        <script>
            $(document).ready(function() {
                @if (!$dataTypeContent['products']->count())
                    $('#{{ $relationshipField }}-wrapper').hide();
                @endif

                $('#coupon_products_all').change(function() {
                    if ($(this).prop('checked')) {
                        $('#{{ $relationshipField }}-wrapper').hide();
                    }else{
                        $('#{{ $relationshipField }}-wrapper').show();
                    }
                });
            });
        </script>
    @endpush
@endif
