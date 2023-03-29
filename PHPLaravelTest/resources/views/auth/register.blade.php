@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        @component('components.blocks.page-intro', [
            'heading' => 'Create an Account',
            'paragraphs' => [
                'Create a Brown Bear Digital Account to store your contact information, notification preferences and order history.'
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
                    <h2 class='section-title'>Account Sign-Up</h2>
                    <hr>

                    <div class="form-row">
                        <form id="register-form" method="post" action="{{ config('services.gateway.base_url').config('services.gateway.oauth_register_url') }}">
                            @csrf

                            <input name="redirectAfterRegister" type="hidden" value="{{ GatewayService::redirectRoute('sso.after-register') }}" />
                            <input name="origin" type="hidden" value="brownbear.com/create-account" />
                            <input name="shortOrigin" type="hidden" value="create-account" />

                            @include('components.forms.register')

                            <div class='button-row button-row--section-margin text-center'>
                                <button class='button' data-test-id="register:create-account">Create My Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        @include('components.blocks.ask-a-question', (array)$askAQuestion)
    </main>
@endsection

