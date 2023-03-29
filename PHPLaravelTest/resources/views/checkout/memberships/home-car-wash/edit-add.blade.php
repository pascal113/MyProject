@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--short',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        @include('components.blocks.order-summary', [ 'hideSummary' => true ])

        <div class="wrapper">
            <form action="{{ route('checkout.memberships.home-car-wash.store') }}" method="post">
                @csrf

                <section class="section">
                    <div class="
                        section-row
                        section-row--padded-top
                        section-row--center
                        section-row--sm
                    ">
                    @include('components.blocks.choose-home-car-wash', [ 'intro' => 'Please choose your home tunnel car wash so we can be ready to receive you.' ])
                    </div>
                </section>
                @include('components.blocks.footer-return', [
                    'returnLink' => [
                        'theme' => 'checkout',
                    ],
                    'button' => [
                        'text' => 'Continue',
                        'type' => 'submit',
                    ],
                ])
            </form>
        </div>
    </main>
@endsection
