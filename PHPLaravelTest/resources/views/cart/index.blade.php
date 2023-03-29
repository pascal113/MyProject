@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--short',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        <div class="wrapper wrapper--gray">
            <section class="section">
                <div class="
                    section-row
                    section-row--top
                ">
                    <h2 class='page-title page-title--no-margin'>{{ "It's in the Bag" }}</h2>
                </div>
            </section>
        </div>

        <div class="wrapper">
            <div class="section">
                <div class="section-row section-row--center">
                    @include('components.blocks.notifications')
                </div>
            </div>
        </div>

        <noscript style="display: block; width: 100%; padding: 50px 0; text-align: center;">Please enable javascript. This page requires it in order to work properly.</noscript>

        <shopping-cart
            inline-template
            :rows='@json($rows)'
            :pre-discount-sub-total='{{ $preDiscountSubTotal }}'
            :discount='{{ $discountFloat }}'
            :sub-total='{{ $subTotalFloat }}'
            :shipping-price='{{ $shippingPrice }}'
        >
            <div>
                <div class="wrapper wrapper--gray wrapper--bordered" v-if="loading">
                    <span class="loading-indicator loading centered" style="padding: 100px 0;"></span>
                </div>

                <div id="shopping-cart" data-test-id="cart" hidden>
                    <div v-if="state.rows.length">
                        <div class="wrapper wrapper--gray wrapper--bordered">
                            <section class="section">
                                <div class="
                                    section-row
                                    section-row--padded
                                ">
                                    <div
                                        v-for="row in state.rows"
                                        v-bind:key="row.rowId"
                                        class="item-row"
                                    >
                                        <div>
                                            <h3 class="section-subtitle make-trademarks-superscript" data-test-class="shopping-cart:item:name">@{{ row.name }}</h3>
                                        </div>
                                        <div>
                                            <span class="item-row__header">Quantity</span>
                                            <span v-if="row.options.modifies_membership_wash_connect_id || row.options.reactivates_membership_wash_connect_id" class="item-row__content">@{{ row.qty }}</span>
                                            <div v-else class="input-custom-number">
                                                <button type="button" :disabled="row.qty <= 1" @click="incrementRowQty(row.rowId, -1)">-</button>
                                                <input type="number" step="1" min="1" :name="'qtys['+row.rowId+']'" @change="setRowQty(row.rowId, $event.target.value)" v-model="row.qty" data-test-class="shopping-cart:item:qty" />
                                                <button type="button" @click="incrementRowQty(row.rowId, 1)">+</button>
                                            </div>
                                            <button class="a item-row__remove link-black" @click="removeRow(row.rowId)">Remove</button>
                                        </div>
                                        <div>
                                            <span class="item-row__header" v-if="row.options.variant_name">Type</span>
                                            <span class="item-row__content" v-if="row.options.variant_name" data-test-class="shopping-cart:item:type">@{{ row.options.variant_name.charAt(0).toUpperCase()+row.options.variant_name.slice(1) }}</span>
                                        </div>
                                        <div>
                                            <span class="item-row__header">Price</span>
                                            <span v-if="updating === row.rowId" class="item-row__content loading-indicator loading" style="height: 30px;"></span>
                                            <span class="item-row__content" v-if="updating !== row.rowId && row.discount">
                                                <strike v-if="updating !== row.rowId && row.preDiscountPrice > row.price">@{{ formatCurrency(row.preDiscountPrice * row.qty) }}</strike>
                                                <strike v-else >@{{ formatCurrency(row.price * row.qty) }}</strike>
                                                @{{ formatCurrency((row.price - row.discount) * row.qty) }}
                                            </span>
                                            <span class="item-row__content" v-else-if="updating !== row.rowId" data-test-class="shopping-cart:item:price">
                                                <strike v-if="row.preDiscountPrice > row.price">@{{ formatCurrency(row.preDiscountPrice * row.qty) }}</strike>
                                                @{{ formatCurrency(row.price * row.qty) }}
                                            </span>
                                        </div>
                                    </div>

                                    <hr>
                                    <div v-if="state.discount > 0" class="item-total">
                                        <span class="item-total__title">Merchandise Total</span>
                                        <span v-if="updating" class="item-total__cost loading-indicator loading" style="height: 30px;"></span>
                                        <span v-if="!updating" class="item-total__cost"><strike>@{{ formatCurrency(state.preDiscountSubTotal) }}</strike></span>
                                    </div>
                                    <div  v-if="state.discount > 0"  class="item-total">
                                        <span class="item-total__title">Discount Total</span>
                                        <span v-if="updating" class="item-total__cost loading-indicator loading" style="height: 30px;"></span>
                                        <span v-if="!updating && state.discount > 0" class="item-total__cost">-@{{ formatCurrency(state.discount) }}</span>
                                    </div>
                                    <div class="item-total">
                                        <span class="item-total__title">Subtotal</span>
                                        <span v-if="updating" class="item-total__cost loading-indicator loading" style="height: 30px;"></span>
                                        <span v-if="!updating" class="item-total__cost">@{{ formatCurrency(state.subTotal) }}</span>
                                    </div>
                                    <div class="item-total">
                                        <span class="item-total__title">Shipping</span>
                                        <span v-if="updating" class="item-total__cost loading-indicator loading" style="height: 30px;"></span>
                                        <span v-if="!updating" class="item-total__cost">@{{ formatCurrency(state.shippingPrice) }}</span>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="wrapper">
                            @include('components.blocks.footer-return', [
                                'returnLink' => [
                                    'text' => 'Continue Shopping',
                                    'url' => '#',
                                    'tags' => '@click="onContinueShoppingClick"',
                                ],
                                'button' => [
                                    'text' => 'Checkout',
                                    'url' => route('checkout.index'),
                                    'tags' => 'onclick="fbq(\'track\', \'InitiateCheckout\');"',
                                    'class' => 'button--wide',
                                    'testId' => 'cart:checkout',
                                ],
                            ])
                        </div>
                    </div>
                    <div v-else>
                        <div class="wrapper wrapper--gray wrapper--bordered">
                            <section class="section">
                                <div class="
                                section-row
                                section-row--padded
                                ">
                                    <h3 class="section-subtitle">Your Cart is Empty</h3>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </shopping-cart>

        @include('components.blocks.ask-a-question', (array)$askAQuestion)
    </main>
@endsection

@push('js')
    <script>
        Vue.component('shopping-cart', {
            props: {
                preDiscountSubTotal: {
                    type: Number,
                    default: 0
                },
                discount: {
                    type: Number,
                    default: 0
                },
                subTotal: {
                    type: Number,
                    default: 0
                },
                rows: {
                    type: Object,
                    required: true
                },
                shippingPrice: {
                    type: Number,
                    default: 0
                },
            },

            data: function() {
                return {
                    loading: true,
                    state: {
                        preDiscountSubTotal: 0,
                        discount: 0,
                        subTotal: 0,
                        rows: [],
                        shippingPrice: 0,
                    },
                    updating: false
                }
            },

            computed: {
                count: function() {
                    return this.state.rows.reduce(function(count, row) {
                        count += parseInt(row.qty);

                        return count;
                    }, 0);
                },
            },

            mounted: function() {
                this.state.preDiscountSubTotal = this.preDiscountSubTotal;
                this.state.discount = this.discount;
                this.state.subTotal = this.subTotal;
                this.setRows(this.rows);
                this.state.shippingPrice = this.shippingPrice;
                this.loading = false;
                document.querySelector('#shopping-cart').hidden = false;
            },

            methods: {
                setRows: function(data) {
                    if (Object.values(data)) {
                        this.state.rows = Object.values(data);
                    }
                },

                formatCurrency: function(number, options = null) {
                    return window.formatCurrency(number, options);
                },

                incrementRowQty: function(rowId, delta) {
                    var row = this.state.rows.find(function(row) {
                        return row.rowId === rowId;
                    });

                    if (!row) {
                        return;
                    }

                    var newQty = parseInt(row.qty) + delta;
                    newQty = newQty > 0 ? newQty : 0;

                    if (newQty === row.qty) {
                        // No change
                        return;
                    }

                    if (!newQty) {
                        this.removeRow(rowId);
                    } else {
                        this.setRowQty(rowId, newQty);
                    }
                },

                onContinueShoppingClick: function() {
                    var allProductsRoute = "{{cms_route('shop.all-products')}}";
                    var prevRoutePath = document.referrer.replace(window.origin, '');
                    if (prevRoutePath.match(/^\/(shop|wash-clubs)/)){
                        window.location = document.referrer;
                    } else {
                        window.location = allProductsRoute;
                    }
                },

                setRowQty: async function(rowId, qty) {
                    var row = this.state.rows.find(function(row) {
                        return row.rowId === rowId;
                    });

                    if (!row) {
                        return;
                    }

                    await this.postRowQty(rowId, qty);

                    if (qty <= 0) {
                        return this.removeRow(rowId);
                    }

                    row.qty = qty;
                },

                removeRow: function(rowId) {
                    this.state.rows = this.state.rows.filter(function(row) {
                        return row.rowId !== rowId;
                    });

                    this.postRowQty(rowId, 0);
                },

                postRowQty: async function(rowId, qty) {
                    this.updating = rowId;

                    var self = this;
                    var result = await window.axios.post('{{ route('cart.update-row') }}', {
                        _token: '{{ csrf_token() }}',
                        rowId: rowId,
                        qty: qty
                    })
                        .then(function(resp) {
                            if (!resp && resp.data && resp.data.success && resp.data.data) {
                                Vue.toasted.error('Oops! There was a problem saving your cart.');
                            }

                            self.state.preDiscountSubTotal = resp.data.data.preDiscountSubTotal
                            self.state.subTotal = resp.data.data.subTotal
                            self.state.discount = resp.data.data.discount
                            self.setRows(resp.data.data.content);
                            self.state.shippingPrice = resp.data.data.shippingPrice

                            window.updateNumCartItems(self.count);

                            if (resp.data.data.couponsRemoved) {
                                resp.data.data.couponsRemoved.map(function(couponRemoved) {
                                    Vue.toasted.info('Coupon '+couponRemoved+' has been removed from your cart because you no longer meet the minimum requirement.');
                                });
                            }
                        })
                        .catch(function() {
                            Vue.toasted.error('Oops! There was a problem updating your cart.');
                        });

                    this.updating = false;

                    return result;
                }
            },
        });
    </script>
@endpush
