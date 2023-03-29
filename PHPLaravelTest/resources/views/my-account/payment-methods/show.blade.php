@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => 'Account Management',
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

                    <h2 class='section-title'>Payment Method Info</h2>
                    <hr>
                    <p class="page-intro">All recurring charges for monthly or yearly club memberships  associated with your Brown Bear Digital account will be billed to the payment method on file.</p>

                    @if ($hasWashConnectAccount)
                        @if ($paymentMethod)
                            <p>
                                <br />
                                @if ($paymentMethod->is_payeezy)
                                    <strong>Your current card on file is:</strong><br />
                                    {{ $paymentMethod->brand }} ending in {{ $paymentMethod->last4 }}
                                @else
                                    <strong>You have a stored payment method, but it needs to be updated into our new system.</strong>
                                @endif
                            </p>
                            <div class="button-row button-row--extra-margin-bottom">
                                <button type="button" class="button" data-featherlight="#change-payment-method-warning">Update Card on File</button>
                            </div>
                        @else
                            <p>You have no card on file currently.</p>
                            <div class="button-row button-row--extra-margin-bottom">
                                <a href="{{ route('my-account.payment-methods.edit') }}" class="button">Add a Card</a>
                            </div>
                        @endif
                    @else
                        <p>You have not purchased any wash club memberships. Once you purchase a membership you will be able to store a payment method on file.</p>
                    @endif

                    @include('components.blocks.contact-support', [
                        'content' => 'For questions about our services, contact us.',
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

    @if ($hasWashConnectAccount && $paymentMethod)
        @include('components.popups.change-payment-method-warning', [
            'onConfirm' => "location.href = '".route('my-account.payment-methods.edit')."';",
        ])
    @endif
@endsection
