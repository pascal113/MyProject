@php
    use App\Facades\Cart;
    use App\Models\Product;
    $content = Cart::content();

    if (!$content->count()) {
        return redirect()->route('cart.index');
    }
    $hasRecurringItems = Cart::hasWashClubProducts();

    $preDiscountSubTotalFloat = Cart::preDiscountSubTotalFloat();
    $discountFloat = Cart::discountFloat();
    $subTotalFloat = Cart::subTotalFloat();
    $shippingPriceFloat = Cart::shippingPriceFloat();
    $taxFloat = Cart::taxFloat();
    $totalFloat = Cart::totalFloat();

    $isTaxFullyCalculated = Cart::isTaxFullyCalculated();

    $content = Cart::serializableContent();
@endphp

<noscript style="display: block; width: 100%; padding: 50px 0; text-align: center;">Please enable javascript. This page requires it in order to work properly.</noscript>

<order-summary
    inline-template
    :content='@json($content)'
    :is-tax-fully-calculated='@json($isTaxFullyCalculated)'
    :pre-discount-sub-total='{{ $preDiscountSubTotalFloat }}'
    :discount='{{ $discountFloat }}'
    :sub-total='{{ $subTotalFloat }}'
    :shipping-price='{{ $shippingPriceFloat }}'
    :tax='{{ $taxFloat }}'
    :total='{{ $totalFloat }}'
>
    <div>
        <div class="wrapper wrapper--gray wrapper--bordered" v-if="loading">
            <span class="loading-indicator loading centered" style="padding: 100px 0;"></span>
        </div>

        <div id="summary" class="wrapper wrapper--gray wrapper--border-bottom" hidden>
            <section class="section">
                <div class="
                    section-row
                    section-row--top
                ">
                    <h2 class='page-title page-title--no-margin'>Order Summary</h2>
                    <div class="summary-top">
                        <a class="summary-top__trigger" href="?">Show Summary</a>
                        <span class="item-total__cost">@{{ formatCurrency(state.total) }}</span>
                    </div>
                </div>
            </section>
        </div>
        <div id="summary-content" class="wrapper wrapper--gray wrapper--border-bottom" data-test-id="summary" hidden>
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                ">
                    <div
                        v-for="row in state.content"
                        v-bind:key="row.rowId"
                        class="item-row"
                    >
                        <div>
                            <h3 class="section-subtitle">@{{ row.name }}</h3>
                            <span class="item-row__content" v-if="row.options.couponCode"><span class="coupon-code">@{{ row.options.couponCode }} (-@{{ formatCurrency(row.discount * row.qty) }})</span></span>
                        </div>
                        <div>
                            <span class="item-row__header">Quantity</span>
                            <span class="item-row__content">@{{ row.qty }}</span>
                        </div>
                        <div>
                            <span class="item-row__header" v-if="row.options.variant_name">Type</span>
                            <span class="item-row__content" v-if="row.options.variant_name">@{{ row.options.variant_name.charAt(0).toUpperCase()+row.options.variant_name.slice(1) }}</span>
                        </div>
                        <div>
                            <span class="item-row__header">Price</span>
                            <span class="item-row__content">
                                <span v-if="row.discount">
                                    <strike v-if="row.preDiscountPrice > row.price">@{{ formatCurrency(row.preDiscountPrice * row.qty) }}</strike>
                                    <strike v-else >@{{ formatCurrency(row.price * row.qty) }}</strike>
                                    @{{ formatCurrency((row.price - row.discount) * row.qty) }}
                                </span>
                                <span v-else>
                                    <strike v-if="row.preDiscountPrice > row.price">@{{ formatCurrency(row.preDiscountPrice * row.qty) }}</strike>
                                    @{{ formatCurrency(row.price * row.qty) }}
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="item-discounts">
                        <div class="item-discounts__field-row">
                            <input
                                type="text"
                                name="coupon_code"
                                v-model="couponCode"
                                v-on:keypress="onCouponCodeKeypress($event)"
                                class="item-discounts__field"
                                placeholder="Gift card or discount code"
                            />
                            <button type="button" @click="addCoupon()" class="button">
                            <span
                                v-if="couponLoading"
                                class="loading-indicator loading-indicator__dark-bg loading-indicator__small loading"
                                style="height: 27px;"
                            ></span>
                                Apply
                            </button>
                        </div>
                        <ul v-if="this.activeCoupons.length">
                            <li v-for="activeCoupon in this.activeCoupons" v-bind:key="activeCoupon.id">@{{ activeCoupon.code }} <button class="a" @click="removeCoupon(activeCoupon.id)">X</button></li>
                        </ul>
                    </div>
                    <div class="item-subtotal">
                        <div v-if="state.discount > 0" class="item-subtotal__row  item-subtotal__taxes">
                            <span>Merchandise Total</span>
                            <span><strike>@{{ formatCurrency(state.preDiscountSubTotal) }}</strike></span>
                        </div>
                        <div v-if="state.discount > 0" class="item-subtotal__row  item-subtotal__taxes">
                            <span>Discount Total</span>
                            <span>-@{{ formatCurrency(state.discount) }}</span>
                        </div>
                        <div class="item-subtotal__row">
                            <span class="item-subtotal__title">Subtotal</span>
                            <span class="item-subtotal__cost">@{{ formatCurrency(state.subTotal) }}</span>
                        </div>
                        <div class="item-subtotal__row item-subtotal__taxes">
                            <span class="">Shipping</span>
                            <span class="">@{{ formatCurrency(state.shippingPrice) }}</span>
                        </div>
                        <div class="item-subtotal__row item-subtotal__taxes">
                            <span class="">Taxes</span>
                            <span v-if="isTaxFullyCalculated" class="">@{{ formatCurrency(state.tax) }}</span>
                            <span v-if="!isTaxFullyCalculated" class="">Calculation Pending</span>
                        </div>
                    </div>
                    <hr>
                    <div v-if="isTaxFullyCalculated" class="item-total">
                        <span class="item-total__title">Total</span>
                        <span class="item-total__cost">@{{ formatCurrency(state.total) }}</span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</order-summary>

@push('js')
    <script>
        $(document).ready(function() {
            function toggleOrderSummary(elem) {
                elem.toggleClass('is-open');
                elem.text(elem.hasClass('is-open') ? 'Hide Summary' : 'Show Summary');
                elem.attr('href', elem.hasClass('is-open') ? '?hide-summary' : '?');
                $('#summary-content').slideToggle('slow');

                window.history.pushState(
                    null,
                    null,
                    window.location.href.split('?')[0] + (elem.hasClass('is-open') ? '' : '?hide-summary'),
                );
            }

            @if (!request()->query->has('hide-summary') && !(isset($hideSummary) && $hideSummary))
                toggleOrderSummary($('.summary-top__trigger'));
            @endif

            $('.summary-top__trigger').click(function(e) {
                e.preventDefault();

                toggleOrderSummary($(this));
            });
        });

        Vue.component('order-summary', {
            props: {
                content: {
                    type: Object,
                    required: true
                },
                shippingPrice: {
                    type: Number,
                    default: 0
                },
                isTaxFullyCalculated: {
                    type: Boolean,
                    default: false,
                },
                preDiscountSubTotal: {
                    type: Number,
                    default: 0,
                },
                discount: {
                    type: Number,
                    default: 0
                },
                subTotal: {
                    type: Number,
                    default: 0
                },
                tax: {
                    type: Number,
                    default: 0
                },
                total: {
                    type: Number,
                    default: 0
                },
            },

            data: function() {
                return {
                    couponCode: '',
                    couponLoading: false,
                    loading: true,
                    state: {
                        content: [],
                        subTotal: 0,
                        shippingPrice: 0,
                        tax: 0,
                        total: 0,
                        preDiscountSubTotal: 0,
                        discount: 0,
                    },
                };
            },

            mounted: function() {
                this.state.content = this.content;
                this.state.preDiscountSubTotal = this.preDiscountSubTotal;
                this.state.discount = this.discount;
                this.state.subTotal = this.subTotal;
                this.state.shippingPrice = this.shippingPrice;
                this.state.tax = this.tax;
                this.state.total = this.total;
                this.loading = false;

                document.querySelector('#summary').hidden = false;
                document.querySelector('#summary-content').hidden = false;
            },

            computed: {
                activeCoupons: function() {
                    return Object.values(this.state.content).reduce(function(activeCoupons, row) {
                        if (row.options.couponId && !activeCoupons.find(function(activeCoupon) {
                            return activeCoupon.id === row.options.couponId;
                        })) {
                            activeCoupons.push({
                                id: row.options.couponId,
                                code: row.options.couponCode || '',
                            });
                        }

                        return activeCoupons;
                    }, []);
                },

                count: function() {
                    return this.content.reduce(function(count, row) {
                        count += parseInt(row.qty);

                        return count;
                    }, 0);
                }
            },

            methods: {
                formatCurrency: function(number, options = null) {
                    return window.formatCurrency(number, options);
                },

                onCouponCodeKeypress: function(event) {
                    if (event.which === 13) {
                        this.addCoupon();
                    }
                },

                addCoupon: function() {
                    if (!this.couponCode.trim()) {
                        Vue.toasted.error('Please enter a code.');

                        return;
                    }

                    this.couponLoading = true;

                    const self = this;

                    return window.axios.post('{{ route('cart.add-coupon') }}', {
                        _token: '{{ csrf_token() }}',
                        code: this.couponCode
                    })
                        .then(function(resp) {
                            self.updateTotalsFromApiResponse(resp)

                            self.couponCode = '';

                            Vue.toasted.success('Coupon applied.');

                            self.couponLoading = false;
                        })
                        .catch(function(error) {
                            var message = (error.response && error.response.data && error.response.data.message) || 'Oops! There was a problem adding that code to your order.';

                            Vue.toasted.error(message);

                            self.couponLoading = false;
                        });
                },

                removeCoupon: function(couponId) {
                    this.couponLoading = true;

                    const self = this;

                    return window.axios.post('{{ route('cart.remove-coupon') }}', {
                        _token: '{{ csrf_token() }}',
                        id: couponId
                    })
                        .then(function(resp) {
                            self.updateTotalsFromApiResponse(resp)

                            Vue.toasted.success('Coupon removed.');

                            self.couponLoading = false;
                        })
                        .catch(function(error) {
                            var message = (error.response && error.response.data && error.response.data.message) || 'Oops! There was a problem removing that code from your order.';

                            Vue.toasted.error(message);

                            self.couponLoading = false;
                        });
                },

                updateTotalsFromApiResponse: function(resp) {
                    this.state.content = resp.data.data.content;
                    this.state.preDiscountSubTotal = resp.data.data.preDiscountSubTotal;
                    this.state.discount = resp.data.data.discount;
                    this.state.subTotal = resp.data.data.subTotal;
                    this.state.shippingPrice = resp.data.data.shippingPrice;
                    this.state.tax = resp.data.data.tax;
                    this.state.total = resp.data.data.total;
                }
            },
        });
    </script>
@endpush
