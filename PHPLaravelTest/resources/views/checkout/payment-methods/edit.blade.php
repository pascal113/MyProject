@extends('layouts.web')

@if (session()->get('isNewlyRegistered'))
    @include('components.blocks.fbq-complete-registration')
@endif

@section('content')
    <main class="doc-content">
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                ">
                    {!! $paymentForm !!}
                </div>
            </section>
        </div>
    </main>
@endsection
