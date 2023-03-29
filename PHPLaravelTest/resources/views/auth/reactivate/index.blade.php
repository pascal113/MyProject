@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => ($mode ?? null) === 'legacy' ? 'Welcome to Brown Bear Digital!' : 'Account Management',
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

                    @if (($mode ?? null) === 'legacy')
                        <h2 class='section-title' data-test-id="activate:heading">Activate Account</h2>
                    @else
                        <h2 class='section-title' data-test-id="reactivate:heading">Re-activate Account</h2>
                    @endif
                    <hr>
                    <div class="form-row">
                        @if (($mode ?? null) === 'legacy')
                            <p class="page-intro text-center">Your email is in our system, but your account is not yet finalized. To complete your account, we just need to verify your email address and password. Click below to continue.</p>
                        @else
                            <p class="page-intro text-center">Oops! Your email was previously registered with our system and the account has been canceled. If you wish to re-activate this account click below to continue.</p>
                        @endif
                    </div>
                    <div class='button-row'>
                        <form action="{{ config('services.gateway.base_url').config('services.gateway.reactivate_url') }}" method="post">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <input type="hidden" name="redirectAfterSuccess" value="{{ GatewayService::redirectRoute('reactivate.index', [ 'email' => $email, 'mode' => $mode ?? null, 'success' => true ]) }}">
                            <input type="hidden" name="redirectAfterFail" value="{{ GatewayService::redirectRoute('reactivate.index', [ 'email' => $email, 'mode' => $mode ?? null ]) }}">

                            @if (($mode ?? null) === 'legacy')
                                <button type="submit" class='button' data-test-id='activate:button'>Activate Account</submit>
                            @else
                                <button type="submit" class='button' data-test-id='reactivate:button'>Re-activate Account</submit>
                            @endif
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
