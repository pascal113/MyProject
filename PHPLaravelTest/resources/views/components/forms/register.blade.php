                            @php
                                $ignorePost = $ignorePost ?? false;
                            @endphp

                            <div class="field-wrapper">
                                <label for="first_name">First Name</label>
                                <input name="first_name" id="first_name" data-test-id="register:first_name" type="text" @if (!($ignorePost ?? false)) value="{{ old('first_name') ?? $firstName ?? '' }}" class="@error('first_name') has-error @enderror" @endif required autocomplete="given-name" autofocus >

                                @if (!$ignorePost)
                                    @error('first_name')
                                        <span class="error-text invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endif
                            </div>

                            <div class="field-wrapper">
                                <label for="last_name">Last Name</label>
                                <input name="last_name" id="last_name" data-test-id="register:last_name" type="text" @if (!($ignorePost ?? false)) value="{{ old('last_name') ?? $lastName ?? '' }}" class="@error('last_name') has-error @enderror" @endif required autocomplete="family-name">

                                @if (!$ignorePost)
                                    @error('last_name')
                                        <span class="error-text invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endif
                            </div>

                            <div class="field-wrapper">
                                <label for="register-email">Your Email Address</label>
                                <input id="register-email" name="email" type="email" data-test-id="register:email" placeholder="name@email.com" @if (!($ignorePost ?? false)) value="{{ old('email') ?? $email ?? '' }}" class="@error('email') has-error @enderror" @endif required autocomplete="email">
                                <div id="register-email-mailcheck-suggestion" class="form-row__info" style="text-align: left; margin-top: 5px; color: orange; display: none;">
                                    <p>Did you mean <a href="#"></a>?</p>
                                </div>

                                @if (!$ignorePost)
                                    @error('email')
                                        <span class="error-text invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endif
                            </div>

                            @if (!isset($requirePassword) || (isset($requirePassword) && $requirePassword))
                                <div class="field-wrapper">
                                    <label for="password">Password</label>
                                    <span class="form-instructions small">Passwords must be at least 8 characters long and include at least one number, one letter, and one special character.</span>
                                    @include('components.blocks.password-input', [
                                        'ignorePost' => $ignorePost,
                                        'testId' => 'register:password',
                                        'preventAutocomplete' => true,
                                    ])
                                </div>
                                <div class="form-row__info">
                                    <p>By clicking Create My Account you accept the Brown Bear Digital <a href="{{ cms_route('support.policies') }}">Privacy Policy and Terms and Conditions</a>.</p>
                                </div>

                                <div class="field-wrapper">
                                    <div id="recaptcha" data-test-id="register:recaptcha">{!! ReCaptcha::htmlFormSnippet() !!}</div>

                                    @error('g-recaptcha-response')
                                        <span class="error-text invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endif

                            @push('js')
                                <script>
                                    $(document).ready(function() {
                                        $('#register-email').on('blur', function() {
                                            window.mailcheck.run({
                                                email: document.querySelector('#register-email').value,
                                                suggested: function (suggestion) {
                                                    window.axios.post('{{ GatewayService::url('v2/validate-email') }}', { email: suggestion.full })
                                                        .then(function(response) {
                                                            $('#register-email-mailcheck-suggestion a').text(suggestion.full);
                                                            $('#register-email-mailcheck-suggestion').show();
                                                        })
                                                        .catch(function() {
                                                            $('#register-email-mailcheck-suggestion').hide();
                                                        });
                                                },
                                                empty: function () {
                                                    $('#register-email-mailcheck-suggestion').hide();
                                                },
                                            });
                                        });

                                        $('#register-email-mailcheck-suggestion a').on('click', function(event) {
                                            event.preventDefault();

                                            $('#register-email').val($(this).text());
                                            $('#register-email-mailcheck-suggestion').hide();
                                        });
                                    });
                                </script>
                            @endpush
