@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => 'Home Car Wash',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--center
                    section-row--sm
                ">
                    <form action="{{ route('my-account.home-car-wash.store') }}" method="post">
                        @csrf

                        @include('components.blocks.choose-home-car-wash', [ 'intro' => 'Please choose your home tunnel car wash so we can serve you best.' ])

                        @include('components.blocks.footer-return', [
                            'returnLink' => [
                                'theme' => 'my-account',
                            ],
                            'button' => [
                                'text' => 'Save Changes',
                                'type' => 'submit',
                            ],
                        ])
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection
