@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => 'Notification Preferences',
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

                        <h2 class='section-title'>Email Settings</h2>
                        <hr class="section-below">
                        <div class="form-row">
                            <div class="field-wrapper">
                                <label for="orders">Order Receipts & Updates</label>
                                <select name="orders" id="notification_pref_orders" class="@error('orders') has-error @enderror" required>
                                    <option value="1" @if (old('orders', $user->notification_pref_orders)) selected @endif>On</option>
                                    <option value="0" @if (!old('orders', $user->notification_pref_orders)) selected @endif>Off</option>
                                </select>

                                @error('orders')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            @if (1 === 2) @php //disabled as per https://bitbucket.org/flowerpress/brownbear.com/issues/317/hide-irrelevant-notification-settings @endphp
                                <div class="field-wrapper">
                                    <label for="promotions">Discounts & Special Promotions</label>
                                    <select name="promotions" id="promotions" class="@error('promotions') has-error @enderror" required>
                                        <option value="1" @if (old('promotions', $user->notification_pref_promotions)) selected @endif>On</option>
                                        <option value="0" @if (!old('promotions', $user->notification_pref_promotions)) selected @endif>Off</option>
                                    </select>

                                    @error('promotions')
                                        <span class="error-text invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="field-wrapper">
                                    <label for="marketing">Marketing Emails</label>
                                    <select name="marketing" id="marketing" class="@error('marketing') has-error @enderror" required>
                                        <option value="1" @if (old('marketing', $user->notification_pref_marketing)) selected @endif>On</option>
                                        <option value="0" @if (!old('marketing', $user->notification_pref_marketing)) selected @endif>Off</option>
                                    </select>

                                    @error('marketing')
                                        <span class="error-text invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endif
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
