@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        @component('components.blocks.page-intro', [
            'heading' => 'New Password',
            'paragraphs' => [
                'Please choose a new password for your Brown Bear Digital Account.'
            ],
        ])@endcomponent
        <div class="wrapper wrapper--blue">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    <h2 class='section-title'>Reset Password</h2>
                    <hr>

                    <div class="form-row">
                        <form method="post" action="{{ config('services.gateway.base_url').config('services.gateway.forgot_password_url').'/reset' }}">
                            <input type="hidden" name="redirectAfterFail" value="{{ GatewayService::redirectRoute('password.reset', [ 'token' => $token ]) }}">
                            <input type="hidden" name="redirectAfterSuccess" value="{{ GatewayService::redirectRoute('password.success') }}">

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="field-wrapper">
                                <label for="email">Your Email Address</label>
                                <input id="email" name="email" type="email" placeholder="name@email.com" value="{{ old('email') }}" class="@error('email') has-error @enderror" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="field-wrapper">
                                <label for="password">Password</label>
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

                            <div class='button-row button-row--section-margin text-center'>
                                <button class='button'>Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        @include('components.blocks.ask-a-question', (array)$askAQuestion)
    </main>
@endsection
