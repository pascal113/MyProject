@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--short',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        @include('components.blocks.order-summary', [ 'hideSummary' => true ])
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--center
                    section-row--sm
                ">
                    @include('components.blocks.notifications')
                </div>
            </section>

            @include('components.blocks.terms.wash-clubs', [ 'content' => $content ])
            @include('components.blocks.footer-return', [
                'returnLink' => [
                    'theme' => 'checkout',
                ],
                'button' => [
                    'text' => 'Accept Terms & Pay',
                    'url' => route('checkout.payment-methods.index'),
                    'testId' => 'terms:accept',
                ],
            ])
        </div>
    </main>
@endsection
