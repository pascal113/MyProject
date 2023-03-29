@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => 'Wash Club Details',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        <div class="wrapper">
            @if (\App\Services\OnScreenNotificationService::count())
                <div class="section">
                    <div class="
                        section-row
                        section-row--padded-top
                        section-row--center
                        section-row--sm
                    ">
                        @include('components.blocks.notifications')
                    </div>
                </div>
            @endif

            @if ($membership->is_mine && !$membership->wash_connect->membership)
                <section class="section">
                    @if ($membership->purchase->one_time_wash_code ?? null)
                        <div class="
                            section-row
                            section-row--padded-top
                            section-row--center
                            section-row--sm
                        ">
                            <h2 class='section-title'>Initial Wash Barcode</h2>
                            <hr class="section-below">
                            <p>Scan this barcode for your initial wash before your account is activated.{{ $membership->purchase->one_time_wash_code_expires_at ? ' This code expires on '.\Carbon\Carbon::parse($membership->purchase->one_time_wash_code_expires_at)->format(config('format.date')).'.' : '' }}</p>
                            <div class="one-time-wash-barcode">
                                <a href="{{ url(config('services.gateway.base_url').'/membership-purchases/'.$membership->purchase->id.'/certificate/pdf') }}">
                                    <img src="{{ $membership->purchase->one_time_wash_barcode_url }}" alt="One Time Wash Code" class="one-time-wash-barcode__img" />
                                    <span class="one-time-wash-barcode__text">{{ $membership->purchase->one_time_wash_code }}</span>
                                    <span class="one-time-wash-barcode__certificate-link">Print Certificate PDF</span>
                                </a>
                            <div>
                        </div>
                    @endif

                    <div class="
                        section-row
                        section-row--padded-top
                        section-row--center
                        section-row--sm
                    ">
                        <h2 class='section-title'>Activation Code</h2>
                        <hr class="section-below">
                        <p>Activate at any Brown Bear Car Wash tunnel wash location.</p>
                        <div class="qr-code">
                            <a href="{{ $membership->certificate_url }}">
                                <img src="{{ $membership->qr_code_url }}" alt="QR Code" class="qr-code__img" />
                                <span class="qr-code__text">{{ $membership->redemption_code }}</span>
                                @if (!($membership->is_gift ?? null))
                                    <span class="qr-code__text">Email: {{ Auth::user()->masked_email }}</span>
                                @endif
                                <span class="qr-code__certificate-link">Print Certificate PDF</span>
                            </a>
                        <div>
                    </div>
                </section>
            @endif
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    <h2 class='section-title'>Wash Club Info</h2>
                    <hr class="section-below">
                    @if ($membership->is_mine)
                        @if ($membership->purchase->created_at ?? null)
                            <p><strong>Purchase Date</strong><br>{{ \Carbon\Carbon::parse($membership->purchase->created_at)->format(config('format.date')) }}</p>
                        @endif
                        <p><strong>Club Type</strong><br><span class="font-size--26">{{ $membership->club->display_name_with_term ?? null }}</span></p>

                        @if ($membership->pending_modification)
                            @component('components.blocks.pending-membership-modification-notification', [
                                'membership' => $membership,
                                'buttons' => [
                                    [
                                        'text' => 'View Details',
                                        'url' => '#',
                                        'class' => 'show-hide__trigger-modification-summary',
                                    ],
                                ],
                            ])
                                @component('components.blocks.show-hide', [ 'id' => 'modification-summary' ])
                                    @include('components.blocks.modification-summary', [
                                        'modification' => $membership->pending_modification,
                                    ])
                                @endcomponent
                            @endcomponent
                        @endif

                        @if ($membership->order_product->display_purchase_price ?? null)
                            <p><strong>Price</strong><br>${{ $membership->order_product->display_purchase_price }} (excluding tax)</p>
                        @endif
                        <p><strong>Membership Tag</strong><br>{{ $membership->wash_connect->vehicle->rfid ?? 'Unknown' }}</p>
                        <p><strong>Club Status</strong><br>{{ $membership->status_details }}</p>
                        <p><strong>Billing Details</strong><br>{!! $membership->billing_details !!}</p>
                    @elseif ($membership->is_gift)
                        <p>{{ "This gift membership has been activated. Club management is available in the gift recipient's account." }}</p>
                    @else
                        <p>Unable to retrieve membership details.</p>
                    @endif
                </div>
            </section>
            @if ($membership->is_mine)
                <section class="section">
                    <div class="
                        section-row
                        section-row--padded-bottom
                        section-row--center
                        section-row--sm
                    ">
                        <h2 class='section-title'>Vehicle Info</h2>
                        <hr class="section-below">
                        @if ($membership->vehicle)
                            @if ($membership->wash_connect->vehicle ?? null)
                                <p><strong>Assigned Vehicle</strong><br>
                            @else
                                <p><strong>Selected Vehicle</strong><br>
                            @endif
                            <strong>Year:</strong> {{ $membership->vehicle->year ?? 'Unknown' }}<br>
                            <strong>Make:</strong> {{ $membership->vehicle->make ?? 'Unknown' }}<br>
                            <strong>Model:</strong> {{ $membership->vehicle->model ?? 'Unknown' }}<br>
                            <strong>Color:</strong> {{ $membership->vehicle->color ?? 'Unknown' }}<br>
                            <strong>License:</strong> {{ $membership->vehicle->license_plate_number ?? 'Unknown' }}<br>
                            <strong>Licensing State:</strong> {{ $membership->vehicle->license_plate_state ?? 'Unknown' }}</p>
                        @else
                            <p><strong>N/A</strong></p>
                        @endif
                    </div>
                </section>

                @if ($buttons->count())
                    <section class="section">
                        <div class="
                            section-row
                            section-row--padded-bottom
                            section-row--center
                            section-row--sm
                        ">
                            <h2 class='section-title'>Manage this Club Membership</h2>
                            <hr class="section-below">
                            <div class="button-row button-row--block button-row--no-margin">
                                @if (isset($buttons['update-payment-method']))
                                    <a href="{{ route('my-account.payment-methods.show') }}" class="button">Update Payment Method</a>
                                @endif
                                @if (isset($buttons['modify']))
                                    @if (Auth::user()->email_verified_at)
                                        <a href="{{ route('shop.modify-membership', [ 'washConnectId' => $membership->wash_connect->membership->id ]) }}" class="button">Change Club Membership</a>
                                    @else
                                        <button class="button" data-featherlight="#email-verification-modify" data-featherlight-persist="true">Change Club Membership</button>
                                    @endif
                                @endif
                                @if (isset($buttons['terminate']))
                                    <button class="button" data-featherlight="{{ Auth::user()->email_verified_at ? '#terminate-membership' : '#email-verification-terminate' }}" data-featherlight-persist="true">Terminate Club</button>
                                @endif
                                @if (isset($buttons['cancel-termination']))
                                    <button class="button" data-featherlight="{{ Auth::user()->email_verified_at ? '#confirm-cancel-termination' : '#email-verification-cancel-termination' }}" data-featherlight-persist="true">Cancel Club Termination</button>
                                    <form id="cancel-termination" action="{{ route('my-account.memberships.cancel-termination', [ 'washConnectId' => $membership->wash_connect->membership->id ]) }}" method="post">@csrf</form>
                                @endif
                                @if (isset($buttons['reactivate']))
                                    <button class="button" data-featherlight="{{ Auth::user()->email_verified_at ? '#confirm-reactivation' : '#email-verification-reactivate' }}" data-featherlight-persist="true">Reactivate Club</button>
                                    <form id="reactivate" action="{{ route('my-account.memberships.reactivate', [ 'washConnectId' => $membership->wash_connect->membership->id ]) }}" method="post">@csrf</form>
                                @endif
                            </div>
                        </div>
                    </section>
                @endif
            @endif
            @if ($membership->is_mine && $membership->wash_connect->customer ?? null)
                <section class="section">
                    <div class="
                        section-row
                        section-row--padded-bottom
                        section-row--center
                        section-row--sm
                    ">
                        <h2 class='section-title'>Customer Info</h2>
                        <hr class="section-below">
                        <p><strong>Name</strong><br>{{ $membership->wash_connect->customer->first_name ?? '' }} {{ $membership->wash_connect->customer->last_name ?? '' }}</p>
                        <p><strong>Email Address</strong><br>{{ $membership->wash_connect->customer->email ?? '' }}</p>
                    </div>
                </section>
            @endif
            <section class="section">
                <div class="
                    section-row
                    section-row--center
                    section-row--sm
                ">
                    @include('components.blocks.contact-support', [
                        'content' => 'For questions regarding this club, contact us.',
                        'contactSupportQueryString' => '?show=email&regarding=Unlimited%20Wash%20Club'
                    ])

                    @include('components.blocks.footer-return', [
                        'returnLink' => [
                            'theme' => 'my-account',
                        ],
                    ])
                </div>
            </section>
        </div>
    </main>
    @if (!Auth::user()->email_verified_at)
        @if (isset($buttons['modify']))
            @include('components.popups.email-verification', [
                'id' => 'email-verification-modify',
                'onSuccess' => 'location.href = location.href;',
                'messageType' => 'modify-membership',
            ])
        @endif
        @if (isset($buttons['terminate']))
            @include('components.popups.email-verification', [
                'id' => 'email-verification-terminate',
                'onSuccess' => 'location.href = location.href;',
                'messageType' => 'terminate-membership',
            ])
        @endif
        @if (isset($buttons['cancel-termination']))
            @include('components.popups.email-verification', [
                'id' => 'cemail-verification-ancel-termination',
                'onSuccess' => 'location.href = location.href;',
                'messageType' => 'cancel-membership-termination',
            ])
        @endif
        @if (isset($buttons['reactivate']))
            @include('components.popups.email-verification', [
                'id' => 'email-verification-reactivate',
                'onSuccess' => 'location.href = location.href;',
                'messageType' => 'reactivate-membership',
            ])
        @endif
    @else
        @if (isset($buttons['terminate']))
            @include('components.popups.terminate-membership')
        @endif
        @if (isset($buttons['cancel-termination']))
            @include('components.popups.confirm', [
                'id' => 'confirm-cancel-termination',
                'actionText' => "cancel this membership's termination",
                'confirmText' => 'Yes, Cancel Termination',
                'onConfirm' => "$('#cancel-termination').submit();",
            ])
        @endif
        @if (isset($buttons['reactivate']))
            @include('components.popups.confirm', [
                'id' => 'confirm-reactivation',
                'actionText' => "reactivate this membership",
                'confirmText' => 'Yes, Reactivate Membership',
                'onConfirm' => "$('#reactivate').submit();",
            ])
        @endif
    @endif
@endsection
