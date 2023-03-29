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
                    <form action="{{url(route('my-account.account.cancel'))}}" method="POST">
                        @csrf

                        <h2 class='section-title'>Cancel Account</h2>
                        <hr class="section-below">
                        <div class="form-row">
                            <p class="text-center">If you wish to cancel your Brown Bear Digital account, you can do so below. This action will remove your access to Brown Bear Digital and will <b>not</b> cancel any active Wash Club memberships. If you have active memberships you wish to cancel please do that prior to cancelling your Brown Bear Digital account, or <a href="{{ cms_route('support/contact-us') }}?show=email">contact support</a>. If you have feedback on how we can improve our service, please enter it below.</p>
                            <div class="field-wrapper">
                                <label for="comments">Comments (Optional)</label>
                                <textarea name="comments" id="comments" class="@error('comments') has-error @enderror">{{ old('comments') }}</textarea>

                                @error('comments')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @include('components.blocks.footer-return', [
                            'returnLink' => [
                                'theme' => 'my-account',
                            ],
                            'button' => [
                                'text' => 'Cancel Account',
                                'type' => 'submit',
                            ],
                        ])
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection
