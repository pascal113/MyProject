@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => ($mode ?? null) === 'legacy' ? 'Welcome to Brown Bear Digital!' : 'Account Management',
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

                    <h2 class='section-title'>Check your inbox</h2>
                    <hr>
                    <div class="form-row">
                        <p class="page-intro text-center">Please check your inbox to complete your account {{ (($mode ?? null) === 'legacy') ? 'activation' : 're-activation' }}.</p>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
