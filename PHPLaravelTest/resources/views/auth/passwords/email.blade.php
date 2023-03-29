@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
      @component('components.blocks.page-intro', [
          'heading' => 'Forgot Password',
          'paragraphs' => [
              'Please enter your email address below and we’ll send you a email to reset your password.'
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
              @include('components.blocks.notifications')

              <h2 class='section-title'>Password Reset Email</h2>
              <hr>

                <div class="form-row">
                    <form method="post" action="{{config('services.gateway.base_url').config('services.gateway.forgot_password_url') }}">
                        @csrf
                        <input type="hidden" name="redirectAfterForgotPassword" value="{{ GatewayService::redirectRoute('sso.after-forgot-password') }}" />
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
                            <div id="recaptcha">{!! ReCaptcha::htmlFormSnippet() !!}</div>

                            @error('g-recaptcha-response')
                                <span class="error-text invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class='button-row button-row--section-margin text-center'>
                            <button class='button'>Send Password Reset</button>
                        </div>
                    </form>
                </div>
            </div>
          </section>
      </div>
      @include('components.blocks.ask-a-question', (array)$askAQuestion)
    </main>
@endsection
