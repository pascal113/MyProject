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
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    <h2 class='section-title'>Modification Summary</h2>
                    <hr class="section-below">
                    @include('components.blocks.modification-summary')
                </div>
            </section>
            @include('components.blocks.footer-return', [
                'returnLink' => [
                    'theme' => 'checkout',
                ],
                'button' => [
                    'text' => 'Continue',
                    'type' => 'a',
                    'url' => $nextRoute,
                ],
            ])
        </div>
    </main>
@endsection
