@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => 'Update Password',
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

                        <h2 class='section-title'>Password</h2>

                        <hr class="section-below">

                        <div class="form-row">
                            <div class="field-wrapper">
                                <label for="current_password">Current Password</label>
                                @include('components.blocks.password-input', [
                                    'name' => 'current_password',
                                ])
                            </div>
                            <div class="field-wrapper">
                                <label for="password">New Password</label>
                                <span class="form-instructions">Numbers, letters and a special character please</span>
                                @include('components.blocks.password-input', [
                                    'preventAutocomplete' => true,
                                ])
                            </div>
                            <div class="field-wrapper">
                                <label for="password_confirmation">Confirm New Password</label>
                                @include('components.blocks.password-input', [
                                    'name' => 'password_confirmation',
                                    'preventAutocomplete' => true,
                                ])
                            </div>
                        </div>
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
