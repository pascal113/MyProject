@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
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

                    @include('components.blocks.email-verification', [ 'onSuccess' => request()->get('redirectTo') ? 'location.href = "'.request()->get('redirectTo').'";' : '' ])
                </div>
            </section>
        </div>
    </main>
@endsection
