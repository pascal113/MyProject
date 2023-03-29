                        @php
                            $ignorePost = $ignorePost ?? false;
                        @endphp

                        <input name="redirectTo" type="hidden" value="{{ $redirectTo ?? request()->get('redirectTo') }}">

                        <div class="field-wrapper">
                            <label for="login-email">Your Email Address</label>
                            <input id="login-email" name="email" type="email" placeholder="name@email.com" @if (!($ignorePost ?? false)) value="{{ old('email') }}" class="@error('email') has-error @enderror" @endif required autocomplete="email" autofocus data-test-id="login:email">

                            @if (!($ignorePost ?? false))
                                @error('email')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endif
                        </div>

                        <div class="field-wrapper">
                            <label>Password</label>
                            @include('components.blocks.password-input', [
                                'ignorePost' => $ignorePost,
                                'testId' => 'login:password',
                            ])
                        </div>

                        <div class="field-wrapper field-wrapper--checkbox">
                            <input id="remember" name="remember" type="checkbox">
                            <label for="remember">Keep me signed in on this device</label>
                        </div>

                        @push('js')
                            <script>
                                $(document).ready(function() {
                                    $('#remember').change(function() {
                                        if (this.checked) {
                                            var newVal = $('input[name="redirectAfterLogin"]').val().replace(/[&]?rememberMe=[^&]*/, '');
                                            newVal += ($('input[name="redirectAfterLogin"]').val().match(/\?/) ? '&' : '?')+'rememberMe=1';
                                        }
                                        else {
                                            var newVal = $('input[name="redirectAfterLogin"]').val().replace(/[&]?rememberMe=1/, '');
                                        }
                                        $('input[name="redirectAfterLogin"]').val(newVal);
                                    });
                                });
                            </script>
                        @endpush
