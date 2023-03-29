@php
    $locationServices = [];
    $locations = \App\Models\Location::with('services')->get()->toArray();
    foreach ($locations as $location) {
        $locationServices[$location['id']] = $location['services'];
    }
    $showOptions = $showOptions ?? true;
    $queryParams = $queryParams ?? [];
    $regarding = isset($queryParams['regarding']) ? $queryParams['regarding'] : '';
    $program = isset($queryParams['program']) ? $queryParams['program'] : '';
    $clubchange = !empty($queryParams['clubchange']);
@endphp

<div class="block-contact-email">
    <form id="contactForm" method="POST">
        @if (isset($mailto))
            <input id="mailto" type="hidden" value="{{ $mailto }}">
        @endif
        @if (isset($subject))
            <input id="subject" type="hidden" value="{{ $subject }}">
        @endif
        <div class="field-wrapper">
            <label for="name">Your Full Name</label>
            <input id="name" type="text" placeholder="First and Last Name" value="{{ Auth::user()->full_name ?? '' }}" data-test-id="contact-email:name">
            <span class="error-text hidden"></span>
        </div>
        <div class="field-wrapper">
            <label for="phone">Your Phone (optional)</label>
            <input id="phone" type="text" placeholder="Phone number" value="" data-test-id="contact-email:phone">
            <span class="error-text hidden"></span>
        </div>
        <div class="field-wrapper">
            <label for="email">Your Email Address</label>
            <input id="email" type="email" placeholder="name@email.com" value="{{ Auth::user()->email ?? '' }}" data-test-id="contact-email:email">
            <span class="error-text hidden"></span>
        </div>

        @if ($showOptions)
            <div class="field-wrapper">
                {{--Options added from  the JS --}}
                <label for="regarding">Regarding</label>
                <select class="has-placeholder" name="regarding" id="regarding" data-test-id="contact-email:regarding">
                    <option value="">Choose One</option>
                    <option value="Corporate Inquiry"
                        {{ $regarding === 'Corporate Inquiry' ? 'selected' : '' }}>Corporate Inquiry</option>
                    <option value="Car Wash Location Inquiry"
                        {{ $regarding === 'Car Wash Location Inquiry' ? 'selected' : '' }}>Car Wash Location Inquiry</option>
                    <option value="Programs Inquiry"
                        {{ $regarding === 'Programs Inquiry' || $clubchange ? 'selected' : '' }}>Programs Inquiry</option>
                    <option value="Unlimited Wash Club"
                        {{ $regarding === 'Unlimited Wash Club' || $clubchange ? 'selected' : '' }} >Unlimited Wash Club</option>
                </select>
                <span class="error-text hidden"></span>
            </div>
            <div class="field-wrapper location-field-wrapper">
                <label for="location">Location</label>
                <select class="has-placeholder" name="location" id="location">
                    <option value="">Choose One</option>
                    @foreach(\App\Models\Location::orderBy('address_line_2')->orderBy('address_line_1')->get() as $location)
                        <option value="{{ $location->id }}">{{ $location->address_line_2 }} ({{$location->address_line_1}})</option>
                    @endforeach
                </select>
                <span class="error-text hidden"></span>
            </div>
            <div class="field-wrapper service-field-wrapper">
                <label for="service">Service</label>
                <select class="has-placeholder" name="service" id="service">
                </select>
                <span class="error-text hidden"></span>
            </div>
            <div class="field-wrapper program-field-wrapper">
                <label for="program">Program</label>
                <select class="has-placeholder" name="program" id="program" data-test-id="contact-email:program">
                    <option value="">Choose One</option>
                    <option value="Charity Car Wash Program"
                        {{ $program === 'Charity Car Wash Program' ? 'selected' : '' }}>Charity Car Wash Program</option>
                    <option value="Car Dealership Program"
                        {{ $program === 'Car Dealership Program' ? 'selected' : '' }}>Car Dealership Program</option>
                    <option value="Fleet Wash Program"
                        {{ $program === 'Fleet Wash Program' ? 'selected' : '' }}>Fleet Wash Program</option>
                </select>
                <span class="error-text hidden"></span>
            </div>
            <div class="field-wrapper wash-club-inquiry-type-field-wrapper">
                <label for="wash-club-inquiry-type">Inquiry Type</label>
                <select class="has-placeholder" name="wash-club-inquiry-type" id="wash-club-inquiry-type">
                    <option value="">Choose One</option>
                    <option value="General Question" >General Question</option>
                    <option {{ $clubchange ? 'selected' : '' }} value="Club Change">Club Change</option>
                </select>
                <span class="error-text hidden"></span>
            </div>
            <div class="field-wrapper wash-club-change-action-field-wrapper">
                <label for="wash-club-change-action">What would you like to change?</label>
                <select class="has-placeholder" name="wash-club-change-action" id="wash-club-change-action">
                    <option value="">Choose One</option>
                    <option value="Cancel Membership" >Cancel Membership</option>
                    <option value="Suspend Membership">Suspend Membership</option>
                    <option value="Unsuspend Membership" >Unsuspend Membership</option>
                    <option value="Change Membership">Change Membership</option>
                </select>
                <span class="error-text hidden"></span>
            </div>

            <div class="field-wrapper wash-club-change-input-field-wrapper">
                <label for="wash-club-change-license-plate">License Plate Number</label>
                <input class="has-placeholder" name="wash-club-change-license-plate" id="wash-club-change-license-plate">
                <span class="error-text hidden"></span>
            </div>

            <div class="field-wrapper wash-club-change-input-field-wrapper">
                <label for="wash-club-change-tag-number">Tag Number</label>
                <input class="has-placeholder" name="wash-club-change-tag-number" id="wash-club-change-tag-number">
                <span class="error-text hidden"></span>
            </div>
        @endif

        <div class="field-wrapper comments-type-field-wrapper">
            <label for="comments">Comments</label>
            <textarea id="comments" name="comments" data-test-id="contact-email:comments"></textarea>
            <span class="error-text hidden"></span>
        </div>
        <div class="robot-check"  data-test-id="contact-email:recaptcha">
            <div id="recaptcha">{!! ReCaptcha::htmlFormSnippet() !!}</div>
            <span class="error-text hidden"></span>
        </div>
        <div class='button-row text-center'>
            <button id="submitContactForm" class='button' data-test-id="contact-email:submit">Send Inquiry</button>
        </div>
    </form>
</div>

@include('components.popups.feedback-thank-you')

@push('js')
    <script>
        $(document).ready(function () {
            var services = JSON.parse('{!! json_encode($locationServices) !!}');
            var regardingSelect = $('#regarding');
            var programSelect = $('#program');
            var inquiryTypeSelect = $('#wash-club-inquiry-type');
            var clubChangeActionSelect = $('#wash-club-change-action');
            var locationSelect = $('#location');
            var serviceSelect = $('#service');

            $('.location-field-wrapper').hide();
            $('.service-field-wrapper').hide();
            $('.program-field-wrapper').hide();

            // Club Change wrappers
            $('.wash-club-inquiry-type-field-wrapper').hide();
            $('.wash-club-change-action-field-wrapper').hide();
            $('.wash-club-change-input-field-wrapper').hide();

            function showComments() {
                $('label[for="comments"]').text('Comments');
                $('.comments-type-field-wrapper').slideDown();
            }

            function showReasonForChange() {
                $('label[for="comments"]').text('Reason for Change');
                $('.comments-type-field-wrapper').slideDown();
            }

            // Based on regarding selected we will show location select
            regardingSelect.on('change', function () {
                var selectedOption = $(this).val();
                if (selectedOption === "Corporate Inquiry"
                    || selectedOption === "") {
                    $('.location-field-wrapper').slideUp();
                    $('.service-field-wrapper').slideUp();
                    $('.program-field-wrapper').slideUp();
                    showComments();

                    // Club Change wrappers
                    $('.wash-club-inquiry-type-field-wrapper').slideUp();
                    $('.wash-club-change-action-field-wrapper').slideUp();
                    $('.wash-club-change-input-field-wrapper').slideUp();
                } else if (selectedOption === "Programs Inquiry") {
                    $('.location-field-wrapper').slideUp();
                    $('.service-field-wrapper').slideUp();
                    $('.comments-type-field-wrapper').slideUp();
                    $('.wash-club-inquiry-type-field-wrapper').slideUp();
                    $('.wash-club-change-input-field-wrapper').slideUp();
                    $('.wash-club-change-action-field-wrapper').slideUp();
                    $('.program-field-wrapper').slideDown();

                    // if program select has pre selected value trigger a change event so it opens another dropdown
                    if (programSelect.val()) {
                        programSelect.trigger('change');
                    }
                } else if (selectedOption === 'Unlimited Wash Club') {
                    $('.comments-type-field-wrapper').slideUp();
                    $('.wash-club-inquiry-type-field-wrapper').slideDown();
                    $('.location-field-wrapper').slideUp();
                    $('.service-field-wrapper').slideUp();
                    $('.program-field-wrapper').slideUp();

                    // if inquiry select has pre selected value trigger a change event so it opens another dropdown
                    if (inquiryTypeSelect.val()) {
                        inquiryTypeSelect.trigger('change');
                    }
                } else if (selectedOption === 'Car Wash Location Inquiry') {
                    $('.location-field-wrapper').slideDown();
                    $('.program-field-wrapper').slideUp();
                    $('.comments-type-field-wrapper').slideUp();

                    // Club Change wrappers
                    $('.wash-club-inquiry-type-field-wrapper').slideUp();
                    $('.wash-club-change-action-field-wrapper').slideUp();
                    $('.wash-club-change-input-field-wrapper').slideUp();

                    // if location select has pre selected value trigger a change event so it opens another dropdown
                    if (locationSelect.val()) {
                        locationSelect.trigger('change');
                    }
                }
            });

            programSelect.on('change', function () {
                $('.wash-club-inquiry-type-field-wrapper').slideUp();
                $('.wash-club-change-action-field-wrapper').slideUp();

                showComments();
            })

            inquiryTypeSelect.on('change', function () {
                var selectedOption = $(this).val();
                if (selectedOption === 'General Question') {
                    showComments();
                    $('.wash-club-change-action-field-wrapper').slideUp();
                    $('.wash-club-change-input-field-wrapper').slideUp();
                } else if (selectedOption === 'Club Change') {
                    $('.comments-type-field-wrapper').slideUp();
                    $('.wash-club-change-action-field-wrapper').slideDown();
                    // if club change action select has pre selected value trigger a change event so it opens another dropdown
                    if (clubChangeActionSelect.val()) {
                        clubChangeActionSelect.trigger('change');
                    }
                } else {
                    $('.wash-club-change-action-field-wrapper').slideUp();
                    $('.wash-club-change-input-field-wrapper').slideUp();
                    $('.comments-type-field-wrapper').slideUp();
                }
            })

            clubChangeActionSelect.on('change', function () {
                var selectedOption = $(this).val();
                if (selectedOption === 'Change Membership') {
                    showReasonForChange();
                    $('.wash-club-change-input-field-wrapper').slideDown();
                } else if (!selectedOption) {
                    $('.comments-type-field-wrapper').slideUp();
                    $('.wash-club-change-input-field-wrapper').slideUp();
                } else {
                    $('.wash-club-change-input-field-wrapper').slideDown();
                    showComments();
                }
            })

            // if regarding select has pre selected value trigger a change event so it opens another dropdown
            if (regardingSelect.val()) {
                regardingSelect.trigger('change');
            }

            // Here we are loading services for the selected location
            locationSelect.on('change', function () {
                var locationId = $(this).val();
                var possibleServices = locationId ? services[locationId] : null;
                if (locationId && possibleServices) {
                    serviceSelect.empty().append(new Option('Choose One'));
                    possibleServices.forEach(function (service) {
                        serviceSelect.append(new Option(service.name));
                    });

                    $('.service-field-wrapper').slideDown();
                } else {
                    $('.service-field-wrapper').slideUp();
                }
            });

            serviceSelect.on('change', function() {
                showComments();
            });

            $('#contactForm').on('submit', function (e) {
                e.preventDefault();

                var contactForm = $('#contactForm');
                var mailto = contactForm.find('#mailto') ? contactForm.find('#mailto').val() : null;
                var subject = contactForm.find('#subject') ? contactForm.find('#subject').val() : null;
                var formData = {
                    mailto: mailto,
                    subject: subject,
                    name: contactForm.find('#name').val(),
                    phone: contactForm.find('#phone').val(),
                    email: contactForm.find('#email').val(),
                    regarding: contactForm.find('select#regarding').val(),
                    location: contactForm.find('select#location').val() ? contactForm.find('select#location option:selected').text() : '',
                    service: contactForm.find('select#service').val(),
                    program: contactForm.find('select#program').val(),
                    comments: contactForm.find('#comments').val(),
                    recaptcha: $('[name=g-recaptcha-response]').val(),
                    'wash-club-inquiry-type': contactForm.find('#wash-club-inquiry-type option:selected').val(),
                    'wash-club-change-action': contactForm.find('#wash-club-change-action option:selected').val(),
                    'wash-club-change-license-plate': contactForm.find('#wash-club-change-license-plate').val(),
                    'wash-club-change-tag-number': contactForm.find('#wash-club-change-tag-number').val(),
                };

                $.ajax({
                    url: '{{ route('api.contact.feedback') }}',
                    type: 'POST',
                    data: formData,
                    success: function () {
                        $.featherlight($('#feedback-thank-you'), {
                            persist: 'true',
                            beforeOpen: function () {
                                $('#contactForm')[0].reset();
                                $.featherlight.close();
                            }
                        });

                        fbq('track', 'Contact');
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            for(errorKey in xhr.responseJSON.errors) {
                                contactForm.find('#' + errorKey)
                                    .siblings('.error-text').text(xhr.responseJSON.errors[errorKey]).removeClass('hidden').end()
                                    .addClass('has-error');
                            }
                        } else {
                            Vue.toasted.error("There was an error contacting the server. Please try again.");
                        }

                        grecaptcha.reset();
                    }
                });
            })
        });
    </script>
@endpush
