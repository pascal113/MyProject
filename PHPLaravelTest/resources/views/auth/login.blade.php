@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
      @component('components.blocks.page-intro', [
          'heading' => 'Welcome!',
          'paragraphs' => [
              'Please sign in to access your Brown Bear Digital Account, or create one below.'
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

              <h2 class='section-title'>Sign In to Brown Bear Digital</h2>
              <hr>
                <div class="form-row">
                    <form method="post" action="{{ config('services.gateway.base_url').config('services.gateway.oauth_login_url') }}">
                        @csrf

                        <input type="hidden" name="redirectAfterLogin" value="{{ GatewayService::redirectRoute('sso.after-login') }}" />

                        @include('components.forms.login')

                        <div class='button-row button-row--section-margin text-center'>
                            <button class='button' data-test-id="login:submit">Sign In</button>
                        </div>
                    </form>
                </div>

                <div class="form-row form-row__links">
                  <a href="{{ route('password.request') }}" data-test-id="login:forgot-password">Forgot Password</a>
                  <a href="{{ route('register') }}" data-test-id="login:create-account">Create Account</a>
                </div>
            </div>
          </section>
      </div>
      @include('components.blocks.ask-a-question', (array)$askAQuestion)
    </main>
@endsection
