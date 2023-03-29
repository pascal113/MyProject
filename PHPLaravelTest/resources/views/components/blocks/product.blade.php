@if ($product)
    @php
        $product->model = \App\Models\Product::find($product->id);

        $isForModification = $isForModification ?? false;

        if ($isForModification) {
            $membershipVariants = collect($product->variants);
        } else {
            $membershipVariants = collect($product->model->variants)->filter(function($variant) use ($isForModification) {
                if ($variant->is_bip || $variant->is_promo || $variant->is_limited) {
                    return false;
                }

                return true;
            });
        }
    @endphp

    <div class="card card--product">
        <div class="product-box">
            <div class="product-box__content">
                @if ($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ htmlspecialchars($product->name) }}" class="img-border">
                @endif
                <h3 class="product-box__title make-trademarks-superscript" data-test-id="product:header:{{ $product->id }}">{{ $product->name }}</h3>
                @if ($product->description)
                    <div class="product-box__description">
                        @include('components.base.wysiwyg', ['content' => $product->description])
                    </div>
                @endif
                <ul class="product-box__cost">
                    @if ($product->model->prices->count())
                        @foreach ($product->model->prices as $price)
                            <li>
                                <span class="product-box__price">${{ number_format($price->price_each, 2) }}{{ $product->num_washes === 1 ? ' /' : '' }}</span>
                                @php
                                    $unit = '';

                                    if (is_null($product->num_washes)) {
                                        $unit = '/ each';
                                    } else {
                                        $unit = $product->num_washes > 1 ? '($'. number_format($product->model->getPriceEach(null, $price->qty_from) / $product->num_washes, 2) . ' / wash)'  : 'wash';
                                    }

                                    if ($product->model->prices->count() > 1) {
                                        $unitLabel = $product->category->slug === 'wash-cards-ticket-books' ? 'book' : 'unit';

                                        if ($price->qty_from === 1 && $price->qty_to === 1) {
                                            $unit .= ' (1 '.$unitLabel.')';
                                        } else {
                                            $unit .= ' (' .$price->qty_from.($price->qty_to ? '–'.$price->qty_to : '+').' '.$unitLabel.'s)';
                                        }
                                    }
                                @endphp
                                <span class="product-box__item">{{ $unit }} </span>
                            </li>
                        @endforeach
                    @endif
                    @if ($monthlyVariant = $membershipVariants->firstWhere('name', 'Monthly') ?? null)
                        <li>
                            <span class="product-box__price">${{ number_format($monthlyVariant->price, 2) }} /</span>
                            <span class="product-box__date">month</span>
                            <span class="product-box__tax">plus tax</span>
                        </li>
                    @endif
                    @if ($yearishVariant = $membershipVariants->first(function($variant) {
                        return $variant->name === 'Yearly' || $variant->name === 'One Year';
                    }) ?? null)
                        <li>
                            <span class="product-box__price">${{ number_format($yearishVariant->price, 2) }} /</span>
                            <span class="product-box__date">year</span>
                            <span class="product-box__tax">plus tax</span>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="product-box__footer">
                <form method="post" action="{{ route('cart.add-product') }}" id="js-add-to-cart-{{ $product->id }}">
                    @csrf
                    @if ($membershipVariants->count() > 1 || $isForModification)
                        <div class="
                            field-wrapper
                            field-wrapper--flex
                            field-wrapper--sm
                        ">
                            <label>Term</label>
                            <select name="options[variant_id]" data-test-id="product:term:{{ $product->id }}">
                                @foreach ($membershipVariants as $variant)
                                    <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif ($membershipVariants->count() === 1)
                        <input name="options[variant_id]" type="hidden" value="{{ $membershipVariants->first()->id }}" />
                    @endif

                    @if ($isForModification)
                        <input type="hidden" name="options[is_modification_flow]" value="1" />
                        <input type="hidden" name="options[modifies_membership_id]" value="{{ $modifiesMembershipId }}" />
                        <input type="hidden" name="options[modifies_membership_wash_connect_id]" value="{{ $modifiesMembershipWashConnectId }}" />
                        <input type="hidden" name="qty" value="1" />
                    @else
                        <div class="
                            field-wrapper
                            field-wrapper--flex
                            field-wrapper--sm
                        ">
                            <label for="{{ $product->id }}">Quantity</label>
                            <select class="has-placeholder" name="qty" id="{{ $product->id }}" data-test-id="product:qty:{{ $product->id }}">
                                @for ($i = 1; $i <= 25; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    @endif
                    <input type="hidden" name="productId" value="{{ $product->id }}" />
                    <button type="submit" class="button" data-test-id="product:add:{{ $product->id }}">{{ $isForModification ? 'Add to Bag & Checkout' : 'Add to Bag' }}</button>
                </form>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function() {
                // Submit to cart via ajax
                $('#js-add-to-cart-{{ $product->id }}').submit(function(e) {
                    e.preventDefault();

                    var addToCartForm = $(this);
                    var formData = addToCartForm.serializeArray().reduce(function(acc, cur) {
                        acc[cur.name] = cur.value;
                        return acc;
                    }, {});

                    if ($(this).find('[name="options[variant_id]"]').length) {
                        var prices = {!! $membershipVariants ? json_encode($membershipVariants->reduce(function($acc, $variant) {
                            $acc->{$variant->id} = $variant->price;

                            return $acc;
                        }, (object)[])) : [] !!};
                        var price = prices[$(this).find('[name="options[variant_id]"]').val()] || undefined;
                    }
                    else {
                        var price = {{ $product->model->getPriceEach(null, 1) ?? 'undefined' }};
                    }

                    // Make Ajax request
                    $.ajax({
                        url: addToCartForm.attr('action'),
                        data: formData,
                        type: 'POST',
                        dataType: 'JSON',
                        success: function (xhr) {
                            if (xhr.success) {
                                fbq('track', 'AddToCart', {
                                    content_name: '{{ $product->name }}',
                                    {!! $product->category ? "content_category: '" : '' !!}{{ $product->category->name }}{!! $product->category ? "'," : '' !!}
                                    content_ids: [{{ $product->id }}],
                                    content_type: 'product',
                                    value: price,
                                    currency: 'USD'
                                });

                                @if ($isForModification)
                                    location.href = '{{ route('cart.index') }}';
                                @else
                                    addToCartForm.find('.button')
                                        .addClass('button--clicked')
                                        .text('Added');

                                    setTimeout(function () {
                                        addToCartForm.find('.button')
                                            .removeClass('button--clicked')
                                            .text('Add to Bag');
                                    }, 2500);

                                    window.showHeader();
                                    window.updateNumCartItems(xhr.data.count);
                                    window.highlightNumCartItems();
                                @endif
                            }
                        },
                        error: function(xhr) {
                            Vue.toasted.error('Oops! There was a problem adding this item to you cart.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endif
