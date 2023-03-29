<section class="block-wash-card-balance {{ $class ?? null }}" data-test-id="wash-card-balance">
    <wash-card-balance
        inline-template
        card-number-prop='{{ $cardNumber ?? '' }}'
    >
        <div class="block-wash-card-balance__content-wrap">
            <div class="block-wash-card-balance__header">
                <i class="icon icon--card-balance"></i>
                <h2 class="section-title section-title--margin-top make-trademarks-superscript">Wash Card Balance</h2>
                <p class="section-intro make-trademarks-superscript">Please enter the ten-digit number found on the back of your card to check your balance.</p>
            </div>

            <div class="block-wash-card-balance__content">
                <div class="block-wash-card-balance__content--left">
                    <img src="{{ asset('images/digital-wash-card.svg') }}" alt="Balance Wash cards">
                </div>
                <div class="block-wash-card-balance__content--right">
                    <form
                        v-if="!washCard"
                        action="{{ url()->current() }}"
                        method="post"
                        @submit.prevent="submit"
                        class="block-wash-card-balance__enter-number"
                    >
                        @csrf

                        <div class="field-wrapper">
                            <label>Wash Card Number</label>
                            <input
                                v-model="cardNumber"
                                type="text"
                                value="{{ old('cardNumber') }}"
                                :class="error ? 'has-error' : ''"
                            />

                            <span
                                v-if="error"
                                class="error-text invalid-feedback"
                                role="alert"
                            >
                                <strong>@{{ error }}</strong>
                            </span>
                        </div>

                        <div class="field-wrapper">
                            <div class="button-row">
                                <button class="button">
                                    <span v-if="loading" class="loading-indicator loading-indicator__dark-bg loading" style="height: 20px; width: 214px;"></span>
                                    <span v-if="!loading">Check Card Balance</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div
                        v-if="washCard"
                        class="block-wash-card-balance__results"
                    >
                        <h4>Found It!</h4>
                        <span class="block-wash-card-balance__results__card">Card @{{ washCard.number.replace(/^%/, '', ) }} has:</span>
                        <ul>
                            <li>
                                <span>Beary Clean Washes</span>
                                @{{ washCard.washes_remaining.clean || '0' }} washes remaining
                            </li>
                            <li>
                                <span>Beary Bright Washes</span>
                                @{{ washCard.washes_remaining.bright || '0' }} washes remaining
                            </li>
                            <li>
                                <span>Beary Best Washes</span>
                                @{{ washCard.washes_remaining.best || '0' }} washes remaining
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div
                v-if="washCard"
                class="block-wash-card-balance__footer"
            >
{{--
Blocking until Wash Card Purchasing is in place

                <h2 class="section-title section-title--margin-top make-trademarks-superscript">Did you know...</h2>
                <p>If you sign in, or create a Brown Bear Digital Account you can attach this card to your account and access the bar code from the My Account area, or, email it to yourself. There is no need to keep track of the physical card.</p>
                <div class="button-row">
                    <a href="{{ route('login') }}" class="button">Sign In & Add Card</a>
                    <a href="{{ route('register') }}" class="button">Join Brown Bear Digital</a>
                </div>
--}}
                <hr>
                <p><a href="#" @click.prevent="reset">Check Another Wash Card &gt;</a></p>
            </div>
        </div>
    </wash-card-balance>
</section>


@push('js')
    <script>
    Vue.component('wash-card-balance', {
        props: {
            cardNumberProp: {
                type: String,
                default: undefined,
            },
        },

        data() {
            return {
                cardNumber: undefined,
                error: undefined,
                loading: false,
                washCard: undefined,
            }
        },

        mounted: function() {
            this.cardNumber = this.cardNumberProp;

            if (this.cardNumber) {
                this.submit();
            }
        },

        methods: {
            submit() {
                if (this.loading) {
                    return;
                }

                if (!this.cardNumber.trim()) {
                    Vue.toasted.error('Please enter a wash card number.');

                    return;
                }

                this.loading = true;

                const self = this;

                return window.axios.get(`{{ GatewayService::url('') }}v2/wash-cards/${this.cardNumber}`)
                    .then(function(resp) {
                        self.washCard = resp.data.data;

                        self.error = undefined;

                        self.loading = false;
                    })
                    .catch(function(error) {
                        var message = error.response.status === 404 ? 'The card number you entered could not be found.' : 'Oops! An error occurred. Please try again.';

                        self.error = message;

                        self.loading = false;
                    });
            },

            reset() {
                this.cardNumber = undefined;
                this.washCard = undefined;
            },
        },
    });
    </script>
@endpush
