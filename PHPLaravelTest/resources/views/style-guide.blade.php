@extends('layouts.web')

@section('content')
    <main class="doc-content wrapper">
        <section class="section">
        <h1 class="promo">Promo - 25+ Tunnel Wash locations in Puget Sound!</h1>
        <h1 class="page-title">Page Title - Quick & Comfortable</h1>
        <h2 class="section-title">Section Title Example</h2>
        <h3 class="section-subtitle">Section Subtitle Example</h3>

        @include('components.blocks.notifications', [
            'message' => 'This is a success notification. The password reset has been sent. Please check your email for next steps.',
            'level' => 'success',
        ])
        @include('components.blocks.notifications', [
            'message' => 'This is an error notification.',
            'level' => 'error',
        ])
        @include('components.blocks.notifications', [
            'message' => 'This is a warning notification.',
            'level' => 'warning',
        ])
        @include('components.blocks.notifications', [
            'message' => 'This is an info notification.',
            'level' => 'info',
        ])
        @include('components.blocks.notifications', [
            'message' => 'This is an info notification. With some nice left aligned buttons.',
            'level' => 'info',
            'buttons' => [
                [
                    'text' => 'View Details',
                ],
                [
                    'text' => 'Cancel Conversion',
                ],
            ],
        ])
        @include('components.blocks.notifications', [
            'message' => 'This is a warning notification with lots of text. Voluptatibus, fugiat culpa laboriosam maiores praesentium? Enim non soluta officia, nam id quas at? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum labore consequatur magnam consectetur iusto quos iste.',
            'level' => 'warning',
        ])

        <p class="page-intro">This is page intro
        content. Voluptatibus, fugiat culpa laboriosam maiores praesentium? Enim non soluta officia, nam id quas at?</p>
        <p class="section-intro">This is a section intro
        content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum labore consequatur magnam consectetur iusto quos iste.
        <a href="{{ route('locations.index') }}">Go to something</a></p>
        <div class="field-wrapper">
            <label class="service-label" for="service">Service Label</label>
            <input type="text" id="service" placeholder="Placeholder Text">
        </div>
        <div class="field-wrapper">
            <label class="form-label" for="formLabel">Form Label</label>
            <input type="text" id="formLabel" placeholder="Placeholder Text">
        </div>


        <blockquote class="pull-quote">This is a pull quote</blockquote>
        <blockquote class="pull-quote pull-quote--green">This is a pull quote in green</blockquote>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos, ipsa laboriosam. Numquam eos officiis voluptatem fuga non laborum, ad temporibus possimus sequi tempora. Accusamus architecto nemo aliquam, voluptatem quia tempore officia vitae quo nulla eligendi omnis veritatis corrupti illum maxime similique ut hic perspiciatis exercitationem?</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos, ipsa laboriosam. Numquam eos officiis voluptatem fuga non laborum, ad temporibus possimus sequi tempora. Accusamus architecto nemo aliquam, voluptatem quia tempore officia vitae quo nulla eligendi omnis veritatis corrupti illum maxime similique ut hic perspiciatis exercitationem?
        </p>
        <div class="button-row">
            <button class="button">Find a Tunnel Wash</button>
            <button class="button">Find a Tunnel Wash</button>
        </div>
        </section>
        <section class="section section--has-margin">
        <h1 class='page-title'>Page Title</h1>
        <p>Page content<br />
        <a href="{{ route('locations.index') }}">Go to something</a></p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos, ipsa laboriosam. Numquam eos officiis voluptatem fuga non laborum, ad temporibus possimus sequi tempora. Accusamus architecto nemo aliquam, voluptatem quia tempore officia vitae quo nulla eligendi omnis veritatis corrupti illum maxime similique ut hic perspiciatis exercitationem?</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos, ipsa laboriosam. Numquam eos officiis voluptatem fuga non laborum, ad temporibus possimus sequi tempora. Accusamus architecto nemo aliquam, voluptatem quia tempore officia vitae quo nulla eligendi omnis veritatis corrupti illum maxime similique ut hic perspiciatis exercitationem?</p>
        </section>
        <section class="section">
        <h1 class='page-title'>Page Title</h1>
        <p>Page content<br />
        <a href="{{ route('locations.index') }}">Go to something</a></p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos, ipsa laboriosam. Numquam eos officiis voluptatem fuga non laborum, ad temporibus possimus sequi tempora. Accusamus architecto nemo aliquam, voluptatem quia tempore officia vitae quo nulla eligendi omnis veritatis corrupti illum maxime similique ut hic perspiciatis exercitationem?</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos, ipsa laboriosam. Numquam eos officiis voluptatem fuga non laborum, ad temporibus possimus sequi tempora. Accusamus architecto nemo aliquam, voluptatem quia tempore officia vitae quo nulla eligendi omnis veritatis corrupti illum maxime similique ut hic perspiciatis exercitationem?</p>
        </section>
    </main>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // If you have page-specific javascript that does not belong in global.js, put it here
        });
    </script>
@endpush

@push('css')
    <style type="text/css">
        /* If you have page-specific css that does not belong in the SASS stack, put it here */
    </style>
@endpush
