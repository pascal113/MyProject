@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => 'Billing Information',
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
                    <h2 class='section-title'>Payment Method</h2>
                    <hr class="section-below">
                    <div class="form-row">
                        <form action="">
                            <div class="field-wrapper">
                                <label for="first-name">First Name</label>
                                <input id="first-name" type="text">
                            </div>
                            <div class="field-wrapper">
                                <label for="last-name">Last Name</label>
                                <input id="last-name" type="text">
                            </div>
                            <div class="field-wrapper">
                                <label for="cc-number">Credit Card Number</label>
                                <input id="cc-number" type="number">
                            </div>
                            <div class="flex-row flex-row--has-margin">
                                <div class="field-wrapper flex-row__col">
                                    <label for="exp-date">Expiration Date</label>
                                    <input id="exp-date" type="text">
                                </div>
                                <div class="field-wrapper flex-row__col">
                                    <label for="ccv">CCV</label>
                                    <input id="ccv" type="text">
                                </div>
                            </div>
                            <div class="field-wrapper">
                                <label for="zip-code">Billing Zip Code</label>
                                <input id="zip-code" type="text">
                            </div>
                        </form>
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
                </div>
            </section>
        </div>
    </main>
@endsection
