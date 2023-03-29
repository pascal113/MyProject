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
                    {!! $paymentForm !!}
                </div>
            </section>
        </div>
    </main>
@endsection
