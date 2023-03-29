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
                    <form action="?" method="post">
                        @csrf

                        <h2 class="section-title">Unlimited Wash Clubs</h2>
                        <hr class="section-below">
                        <div class="section-row">
                            <p>Unlimited Wash Clubs are managed on an individual basis under each wash club page. If you wish to change, cancel or re-activate a wash club, please visit the "Wash Clubs" section at the top of Brown Bear Digital to expand the list of clubs. Click into the club you wish to manage to view available options. If you are having issues locating this information, <a href="{{ cms_route('support/contact-us').'?show=email' }}">contact support</a>.</p>
                            <a href="{{ route('my-account.index').'/memberships' }}" class="button">Manage Wash Clubs</a>
                        </div>

                        <h2 class='section-title section-title--section-margin-top'>Brown Bear Digital</h2>
                        <hr class="section-below">
                        <section class="section">
                            <div class="section-row">
                                <p>Your Brown Bear Digital account allows you access to ordering on BrownBear.com, as well as Unlimited Wash Club membership management features. If you are having issues with using Brown Bear Digital, please <a href="{{ cms_route('support/contact-us').'?show=email' }}">contact support</a>. To manage your Brown Bear Digital account, visit the <a href="{{ route('my-account.account.cancel') }}">account page</a>.</p>
                            </div>
                        </section>
                        @include('components.blocks.footer-return', [
                            'returnLink' => [
                                'theme' => 'my-account',
                            ],
                        ])
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection
