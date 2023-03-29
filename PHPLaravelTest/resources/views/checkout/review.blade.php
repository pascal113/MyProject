@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--short',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        @include('components.blocks.order-summary', [ 'hideSummary' => false ])

        <div class="wrapper">
            @include('components.blocks.footer-return', [
                'returnLink' => [
                    'theme' => 'checkout',
                ],
                'button' => [
                    'text' => 'Continue',
                    'url' => $nextRoute,
                    'testId' => 'review:continue'
                ],
            ])
        </div>
    </main>
@endsection
