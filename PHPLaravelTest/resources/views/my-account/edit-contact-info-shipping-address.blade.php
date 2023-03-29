@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => 'Contact Info & Shipping Address',
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

                        <h2 class='section-title'>Contact Information</h2>
                        <hr class="section-below">
                        <div class="form-row">
                            <div class="field-wrapper">
                                <label for="email">Your First Name</label>
                                <input type="text" id="customer_first_name" name="customer_first_name" value="{{ old('customer_first_name', $user->first_name) }}" class="@error('customer_first_name') has-error @enderror" required autocomplete="customer_first_name" autofocus>

                                @error('customer_first_name')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="field-wrapper">
                                <label for="email">Your Last Name</label>
                                <input type="text" id="customer_last_name" name="customer_last_name" value="{{ old('customer_last_name', $user->last_name) }}" class="@error('customer_last_name') has-error @enderror" required autocomplete="customer_last_name" autofocus>

                                @error('customer_last_name')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="field-wrapper">
                                <label for="email">Your Email Address</label>
                                <input type="email" id="email" name="email" placeholder="name@email.com" value="{{ old('email', $user->email) }}" class="@error('email') has-error @enderror" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <h2 class='section-title section-title--section-margin-top'>Shipping Information</h2>
                        <hr class="section-below">
                        <section class="section">
                            <div class="
                                section-row
                            ">
                                <div class="form-row">
                                    @include('components.forms.shipping-address', ['user' => $user])
                                </div>
                            </div>
                        </section>
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
