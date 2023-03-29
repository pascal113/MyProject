<div class="popup popup-feedback" id="feedback">
    <form id="feedbackForm" method="POST">
        <h3 class="popup__title text-center" data-test-id="feedback:heading">How can we help?</h3>

        <div id="feedback-error" class="field-wrapper"><span class="error-text hidden" style="position: relative; padding-bottom: 0.5em; text-align: center;">There was an error contacting the server. Please try again.</span></div>

        <div class="field-wrapper">
            <label for="name">Your Name</label>
            <input type="text" name="name" id="name" data-test-id="feedback:name" placeholder="First and Last Name" autofocus>
            <span class="error-text hidden" data-test-id="feedback:name-error"></span>
        </div>
        <div class="field-wrapper">
            <label for="email">Your Email Address</label>
            <input type="email" name="email" id="email" data-test-id="feedback:email" placeholder="name@email.com">
            <span class="error-text hidden" data-test-id="feedback:email-error"></span>
        </div>
        <div class="field-wrapper">
            <label for="location">Location</label>

            @if (empty($location))
                <input type="text" name="location" id="location" placeholder="Bellevue, 1850 Main S">
                <span class="error-text hidden"></span>
            @else
                <p>{{ $location->title }} {{ $location->address_line_1 }}, {{ $location->address_line_2 }}</p>
                <input type="hidden" name="location" id="location" value="{{ $location->title }} {{ $location->address_line_1 }}, {{ $location->address_line_2 }}">
            @endif
        </div>
        <div class="field-wrapper">
            <label for="service">Service</label>
            <select class="has-placeholder" name="service" id="service" data-test-id="feedback:select">
                <option value="">Choose One</option>
                @foreach ($location->services as $service)
                    <option value="{{ $service->name }}">{{ $service->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field-wrapper">
            <label for="comments">Comments</label>
            <textarea name="comments" id="comments" cols="30" rows="6" data-test-id="feedback:comments"></textarea>
            <span class="error-text hidden" data-test-id="feedback:comments-error"></span>
        </div>
        <div class="field-wrapper" data-test-id="feedback:recaptcha">
            <div id="recaptcha">{!! ReCaptcha::htmlFormSnippet() !!}</div>
            <span class="error-text hidden"></span>
        </div>
        <div class="button-row button-row--block">
            <button type="submit" class="button" id="send-feedback" data-test-id="feedback:submit">Send Feedback</button>
            <button class="button featherlight-close" data-test-id="feedback:close">Nevermind</button>
        </div>
    </form>
</div>

@include('components.popups.feedback-thank-you')

@push('js')
    <script>
        $(document).ready(function() {
            $('#feedbackForm').on('submit', function(e) {
                e.preventDefault();

                var fieldsContainer = $(this).closest('#feedback');
                var formData = {
                    regarding: 'Car Wash Location Inquiry',
                    name: fieldsContainer.find('input#name').val(),
                    email: fieldsContainer.find('input#email').val(),
                    location: fieldsContainer.find('input#location').val(),
                    service: fieldsContainer.find('select#service').val(),
                    comments: fieldsContainer.find('textarea#comments').val(),
                    recaptcha: $('[name=g-recaptcha-response]').val()
                };

                fieldsContainer
                    .find('.error-text').addClass('hidden').end()
                    .find('input').removeClass('has-error');

                $.ajax({
                    url: '{{ route('api.contact.feedback') }}',
                    type: 'POST',
                    data: formData,
                    success: function () {
                        $.featherlight($('#feedback-thank-you'), {
                            persist: 'true',
                            beforeOpen: function() {
                                $('#feedbackForm')[0].reset();
                                $.featherlight.close();
                            }
                        });

                        fbq('track', 'Contact');
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            for (errorKey in xhr.responseJSON.errors) {
                                fieldsContainer.find('#'+errorKey)
                                    .siblings('.error-text').text(xhr.responseJSON.errors[errorKey]).removeClass('hidden').end()
                                    .addClass('has-error');
                            }
                        } else {
                            fieldsContainer.find('#feedback-error .error-text').removeClass('hidden');
                        }

                        grecaptcha.reset();
                    }
                });
            });
        });
    </script>
@endpush
