@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => $mode === 'legacy' ? 'Welcome to Brown Bear Digital!' : 'Account Management',
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

                    <form action="{{ config('services.gateway.base_url').config('services.gateway.reactivate_url') }}/reset" method="post">
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="redirectAfterFail" value="{{ GatewayService::redirectRoute('reactivate.reset', [ 'token' => $token ]) }}">
                        <input type="hidden" name="redirectAfterSuccess" value="{{ GatewayService::redirectRoute('reactivate.success') }}">

                        @if ($mode === 'legacy')
                            <h2 class='section-title'>Activation Confirmation</h2>
                        @else
                            <h2 class='section-title'>Reactivation Confirmation</h2>
                        @endif
                        <hr>
                        <div class="form-row">
                            @if ($mode === 'legacy')
                                <p class="page-intro text-center">Hello! Please enter your email address and a password to activate your Brown Bear Digital Account.</p>
                            @else
                                <p class="page-intro text-center">Hello! Please enter your email address and a new password to re-activate your Brown Bear Digital Account.</p>
                            @endif
                            <div class="field-wrapper">
                                <label for="email">Your Email Address</label>
                                <input name="email" type="email" placeholder="name@email.com" value="{{ old('email') }}" class="@error('email') has-error @enderror" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="field-wrapper">
                                <label for="password">Password</label>
                                @include('components.blocks.password-input', [
                                    'preventAutocomplete' => true,
                                ])
                            </div>
                        </div>
                        <div class='button-row'>
                            <button type="submit" class='button'>Complete Reactivation</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection
