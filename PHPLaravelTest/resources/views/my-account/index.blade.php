@extends('layouts.web')

@if (session()->get('isNewlyRegistered'))
    @include('components.blocks.fbq-complete-registration')
@endif

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => 'Hi, '.$user->first_name.'!',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    @include('components.blocks.notifications')

                    @if (!$user->email_verified_at)
                        @include('components.popups.email-verification', [
                            'onSuccess' => 'location.href = "'.route('my-account.index', [ 'expandedSection' => 'verified' ]).'";',
                            'messageType' => 'reminder',
                        ])

                        @include('components.blocks.notifications', [
                            'id' => 'email-verification-notification',
                            'message' => 'Hi '.$user->first_name.'! Before you can have the complete Brown Bear Digital experience we need to verify your email address. Please check <strong>'.$user->email.'</strong> and click the link or enter the 6-digit code we send you below.',
                            'level' => 'warning',
                            'buttons' => [
                                [
                                    'text' => 'Verify Now',
                                    'tags' => 'data-featherlight="#email-verification" data-featherlight-persist="true"',
                                ],
                            ],
                        ])
                    @endif

                    <i class='bb-digital-icon'></i>

                    <div class="page-intro">
                        <p>Hi {{ $user->first_name }}! How can we help today?</p>
                    </div>
                </div>
            </section>
        </div>
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-bottom
                    section-row--center
                    section-row--sm
                ">
                    <div class="block-expandable-section">
                        <div>
                            <h2 class="block-expandable-section__section section-title section-trigger @if ($expandedSection === 'my-orders') is-active @endif" name="my-orders">My Orders</h2>
                            <div class="section-content @if ($expandedSection === 'my-orders') is-active @endif">
                                @forelse ($orders as $order)
                                    <ul class="order-list">
                                        <li>
                                            {{ \Carbon\Carbon::parse($order->created_at)->format(config('format.date')) }} – &num;{{ $order->id }}
                                        </li>
                                        @php
                                            $products = $order->products;
                                            if ($hasMore = $products->count() > 2) {
                                                $products = $products->take(2);
                                            }
                                        @endphp
                                        @foreach($products as $product)
                                            <li class="order-list__title">{{ $product->pivot->qty }}x {{ \App\Models\Product::getNameWithTerm($product, $product->pivot->variant ?? null) }}</li>
                                        @endforeach
                                        @if ($hasMore)
                                            <li class="order-list__title">...and more</li>
                                        @endif
                                        <li class="order-list__button"><a href="{{url(route('my-account.orders.show', ['id' => $order->id]))}}" class="button">Order Details</a></li>
                                    </ul>
                                @empty
                                    <p>You have not placed any orders.</p>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <h2 class="block-expandable-section__section section-title section-trigger @if ($expandedSection === 'memberships') is-active @endif" name="memberships">Wash Club Memberships</h2>
                            <div class="section-content @if ($expandedSection === 'memberships') is-active @endif">
                                @forelse ($memberships as $membership)
                                    <ul class="order-list">
                                        @php
                                            if ($membership->is_gift && !$membership->wash_connect->membership) {
                                                $target = 'Unclaimed Gift';
                                            } else {
                                                $target = ($membership->vehicle->label ?? 'Unknown vehicle').(!$membership->wash_connect->membership ? ' (Unclaimed)' : '');
                                            }
                                            $id = $membership->purchase->id ?? $membership->wash_connect->membership->id ?? null;
                                        @endphp
                                        @if ($id)
                                            <li class="order-list__title"><a href="{{ route('my-account.memberships.show', [ 'id' => $id ]) }}">{{ $membership->club->display_name_with_term ?? 'Unknown' }}</a></li>
                                        @endif
                                        <li class="order-list__wash-type">{{ $membership->club->term ?? null }}{{ (($membership->club->term ?? null) && $target) ? ' • ' : ''}}{{ $target }}</li>
                                    </ul>

                                    @if ($membership->pending_modification)
                                        @component('components.blocks.pending-membership-modification-notification', [
                                            'membership' => $membership,
                                            'buttons' => [
                                                [
                                                    'text' => 'View Details',
                                                    'class' => ($membership->id_from_purchase_or_wash_connect || $user->email_verified_at) ? 'show-hide__trigger-'.$id : '',
                                                    'tags' => ($membership->id_from_purchase_or_wash_connect || $user->email_verified_at) ? '' : 'data-featherlight="#email-verification" data-featherlight-persist="true"',
                                                ],
                                            ],
                                        ])
                                            @if ($membership->id_from_purchase_or_wash_connect || $user->email_verified_at)
                                                @component ('components.blocks.show-hide', [ 'id' => $id ])
                                                    @include('components.blocks.modification-summary', [
                                                        'modification' => $membership->pending_modification,
                                                    ])
                                                @endcomponent
                                            @endif
                                        @endcomponent
                                    @endif
                                @empty
                                    <p>You do not have any wash club memberships.</p>
                                @endforelse

                                <p class="margined-top text-center font-size--16 font-color--gray-medium">Something missing? If you have a club membership which is not showing here, or you are unable to update your card on file, it may be associated with a different email address or not be synced up with Brown Bear Digital. Please <a href="{{ cms_route('support.contact-us') }}">contact us</a> for assistance.</p>
                            </div>
                        </div>
                        <div>
                            <h2 class="block-expandable-section__section section-title section-trigger @if ($expandedSection === 'wash-card-balance') is-active @endif" name="wash-card-balance">Wash Card Balance</h2>
                            <div class="section-content @if ($expandedSection === 'wash-card-balance') is-active @endif">
                                @include('components.blocks.wash-card-balance', [ 'cardNumber' => $cardNumber ?? null ])
                            </div>
                        </div>
                        <div>
                            <h2 class="block-expandable-section__section section-title section-trigger @if ($expandedSection === 'contact-info-shipping-address') is-active @endif" name="contact-info-shipping-address">Contact Info & Shipping Address</h2>
                            <div class="section-content @if ($expandedSection === 'contact-info-shipping-address') is-active @endif">
                                <h3 class="section-intro section-intro--bold">Contact Information</h3>
                                <p><strong>Name</strong><br>{{ $user->full_name }}</p>
                                <p><strong>Email Address</strong><br>{{ $user->email }}</p>
                                <p>
                                    <strong>Shipping Address</strong><br>
                                    @if ($user->shipping->full_name) {{ $user->shipping->full_name }}<br>@endif
                                    @if ($user->shipping->address) {{ $user->shipping->address }}<br>@endif
                                    @if ($user->shipping->city && $user->shipping->state && $user->shipping->zip) {{ $user->shipping->city }}, {{ $user->shipping->state }} {{ $user->shipping->zip }}@endif
                                </p>
                                <div class="button-row">
                                    <a href="{{ route('my-account.contact-info-shipping-address.edit') }}" class="button">Edit</a>
                                </div>
                            </div>
                        </div>
                        @if ($paymentMethod)
                            <div>
                                <h2 class="block-expandable-section__section section-title section-trigger @if ($expandedSection === 'payment-method') is-active @endif" name="payment-method">Payment Method</h2>
                                <div class="section-content @if ($expandedSection === 'payment-method') is-active @endif">
                                    <h3 class="section-intro section-intro--bold">Card on File</h3>
                                    <p>
                                        @if ($paymentMethod->is_payeezy)
                                            <span>{{ $paymentMethod->brand }} ending in {{ $paymentMethod->last4 }}</span>
                                        @else
                                            <span>Update required.</span>
                                        @endif
                                    </p>

                                    <div class="button-row">
                                        <a href="{{ route('my-account.payment-methods.show') }}" class="button">Details</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{--
                            <div>
                                <h2 class="block-expandable-section__section section-title section-trigger @if ($expandedSection === 'home-car-wash') is-active @endif" name="home-car-wash">Home Car Wash</h2>
                                <div class="section-content @if ($expandedSection === 'home-car-wash') is-active @endif">
                                    @if ($homeCarWash)
                                        <p>
                                            <strong>{{ $homeCarWash->title }}</strong><br>
                                            {{ $homeCarWash->address_line_1 }}<br>
                                            {{ $homeCarWash->address_line_2 }}
                                        </p>
                                    @endif
                                    <div class="button-row">
                                        <a href="{{ route('my-account.home-car-wash.edit') }}" class="button">Edit</a>
                                    </div>
                                </div>
                            </div>
                        --}}
                        <div>
                            <h2 class="block-expandable-section__section section-title section-trigger @if ($expandedSection === 'notification-preferences') is-active @endif" name="notification-preferences">Notification Preferences</h2>
                            <div class="section-content @if ($expandedSection === 'notification-preferences') is-active @endif">
                                <h3 class="section-intro section-intro--bold">Email Settings</h3>
                                <dl class="description-float no-margin">
                                    <dt>Order Receipts & Updates</dt>
                                    <dd>{{ $user->notification_pref_orders ? 'On' : 'Off' }}</dd>
                                </dl>
                                @if (1 === 2) @php //disabled as per https://bitbucket.org/flowerpress/brownbear.com/issues/317/hide-irrelevant-notification-settings @endphp
                                    <dl class="description-float no-margin">
                                        <dt>Discounts & Special Promotions</dt>
                                        <dd>{{ $user->notification_pref_promotions ? 'On' : 'Off' }}</dd>
                                    </dl>
                                    <dl class="description-float no-margin">
                                        <dt>Marketing Emails</dt>
                                        <dd>{{ $user->notification_pref_marketing ? 'On' : 'Off' }}</dd>
                                    </dl>
                                @endif
                                <div class="button-row">
                                    <a href="{{ route('my-account.notification-preferences.edit') }}" class="button">Edit</a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="block-expandable-section__section section-title section-trigger @if ($expandedSection === 'rewards') is-active @endif" name="rewards">Rewards</h2>
                            <div class="section-content @if ($expandedSection === 'rewards') is-active @endif">
                                <h3 class="section-intro section-intro--bold">Join Brown Bear Rewards</h3>
                                <div class="flex-row">
                                    <div class="flex-row__col">
                                        <p>Did you know that Brown Bear has a rewards program? Join our App today and start earning rewards. You’ll get one free car wash for just signing up, and another for each $100 you spend.</p>
                                        <div class="button-row">
                                            <a href="https://apps.apple.com/us/app/brown-bear-car-wash/id1216372042" target="_blank"><img src="{{ asset('images/badge-appStore.svg') }}" alt="Logo" class="badge-apple"></a>
                                            <a href="https://play.google.com/store/apps/details?id=com.thanx.brownbearcarwash&hl=en_US" target="_blank"><img src="{{ asset('images/badge-playStore.svg') }}" alt="Google Play" class="badge-google"></a>
                                        </div>
                                    </div>
                                    <div class="flex-row__col">
                                        <img class="img-framed" src="{{ asset('images/my-account/phone-reward.jpg') }}" alt="Brown Bear Rewards">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="block-expandable-section__section section-title section-trigger @if ($expandedSection === 'feedback-support') is-active @endif" name="feedback-support">Feedback & Support</h2>
                            <div class="section-content @if ($expandedSection === 'feedback-support') is-active @endif">
                                <h3 class="section-intro section-intro--bold">Account Questions</h3>
                                <p>Your Brown Bear Digital Account is free and keeps you connected to all things Brown Bear.</p>
                                <p>If you are having an issue we can help resolve, please contact support.</p>
                                <div class="button-row">
                                    <a href="{{ cms_route('support/contact-us').'?show=email' }}" class="button">Contact Support</a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="block-expandable-section__section section-title section-trigger @if ($expandedSection === 'account') is-active @endif" name="account">Account & Password</h2>
                            <div class="section-content @if ($expandedSection === 'account') is-active @endif">
                                <h3 class="section-intro section-intro--bold">Update Password</h3>
                                <p>{{ 'To update your Brown Bear Digital Password, use the button below.' }}</p>
                                <div class="button-row">
                                    <a href="{{ route('my-account.password.edit') }}" class="button">Update Password</a>
                                </div>
                                <h3 class="section-intro section-intro--bold">Account & Subscriptions</h3>
                                <p>{{ 'To manage your Brown Bear Digital account and Unlimited Wash Club subscriptions visit the Account Management section.' }}</p>
                                <div class="button-row">
                                    <a href="{{ route('my-account.account.manage') }}" class="button">Manage Account</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Hide/Show Sections
            $('.section-trigger').click(function(e) {
                e.preventDefault();
                $(this).toggleClass('is-active');
                $(this).next('div.section-content').toggleClass('is-active');

                if ($(this).hasClass('is-active')) {
                    window.history.pushState(null, null, '{{ route('my-account.index') }}/'+($(this).attr('name')));
                }
            });
        });
        $(window).unload(function(){}); {{-- Does nothing but break the bfcache for Safari and firefox (https://bitbucket.org/flowerpress/brownbear.com/issues/694/account-login-identity-switching-with-use) --}}
    </script>
@endpush
