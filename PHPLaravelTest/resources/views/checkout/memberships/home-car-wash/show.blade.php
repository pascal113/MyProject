@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--short',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        @include('components.blocks.order-summary', [ 'hideSummary' => $hideSummary ])

        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--center
                    section-row--sm
                ">
                    <h3 class="section-title">Your Home Car Wash</h3>
                    <hr />
                    <p class="width-small page-intro">Please confirm your home car wash.</p>
                </div>
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--center
                    section-row--sm
                ">
                    <p class="width-small page-intro">
                        <strong>{{ $location->title }}</strong><br>
                        {{ $location->address_line_1 }}<br>
                        {{ $location->address_line_2 }}
                    </p>
                </div>
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                ">
                    <div class="button-row">
                        <a href="{{ route('checkout.memberships.home-car-wash.edit') }}" class="button button--wide">Update Car Wash</a>
                        <a href="{{ $nextRoute }}" class="button button--wide" data-test-id="home-wash:continue">Looks Good</a>
                    </div>
                </div>
            </section>
            @include('components.blocks.footer-return', [
                'returnLink' => [
                    'theme' => 'checkout',
                ],
            ])
        </div>
    </main>
@endsection
