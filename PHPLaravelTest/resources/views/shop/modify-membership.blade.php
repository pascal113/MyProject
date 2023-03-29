@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => 'Change Club Membership',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        <div class="wrapper">
            <div class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    @if ($productsAvailableForModifyingTo->count())
                        <h2 class="section-title section-title--no-margin">Change Wash Club</h2>
                        <hr class="width-small" />
                        <h3 class="section-subtitle">You are considering changing your:</h3>
                        <p><strong>{{ $membership->club->display_name_with_term ?? null }}</strong><br />{{ $membership->vehicle->label ?? 'Unknown vehicle' }}</p>
                        <p class="no-margin">Please review our current offerings below and select the club you are interested in.</p>
                    @else
                        <p><strong>There are no products available.</strong></p>
                    @endif
                </div>
            </div>
            <div class="section">
                @if ($productsAvailableForModifyingTo->count())
                    <div class="
                        section-row
                        section-row--padded-bottom
                    ">
                        @include('components.blocks.products', [
                            'products' => $productsAvailableForModifyingTo,
                            'isForModification' => true,
                            'modifiesMembershipId' => $membership->purchase->id ?? null,
                            'modifiesMembershipWashConnectId' => $membership->wash_connect->membership->id ?? null,
                        ])
                    </div>
                @endif
                @include('components.blocks.footer-return', [
                    'returnLink' => [
                        'theme' => 'my-account',
                    ],
                ])
            </div>
        </div>
    </main>
@endsection
