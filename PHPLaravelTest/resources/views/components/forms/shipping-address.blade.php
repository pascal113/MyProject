                            @php
                                $allStates = \App\Models\State::all();
                            @endphp

                            <div class="field-wrapper">
                                <label for="first_name">First Name</label>
                                <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $user ? $user->shipping->first_name : null) }}" class="@error('first_name') has-error @enderror" required>

                                @error('first_name')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="field-wrapper">
                                <label for="last_name">Last Name</label>
                                <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $user ? $user->shipping->last_name : null) }}" class="@error('last_name') has-error @enderror" required>

                                @error('last_name')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="field-wrapper">
                                <label for="address">Address</label>
                                <input id="address" name="address" type="text" value="{{ old('address', $user ? $user->shipping->address : null) }}" class="@error('address') has-error @enderror" required>

                                @error('address')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="field-wrapper">
                                <label for="city">City</label>
                                <input id="city" name="city" type="text" value="{{ old('city', $user ? $user->shipping->city : null) }}" class="@error('city') has-error @enderror" required>

                                @error('city')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="field-wrapper">
                                <label for="state">State</label>
                                <select id="state" name="state" class="has-placeholder @error('state') has-error @enderror" required>
                                    <option value="">Choose One</option>
                                    @foreach ($allStates as $state)
                                        <option value="{{ $state->abbr }}" @if ($state->abbr === (old('state') ?? $user->shipping->state ?? 'WA')) selected="selected" @endif>{{ $state->name }}</option>
                                    @endforeach
                                </select>

                                @error('state')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="field-wrapper">
                                <label for="zip">Zip Code</label>
                                <input id="zip" name="zip" type="text" value="{{ old('zip', $user ? $user->shipping->zip : null) }}" class="@error('zip') has-error @enderror" maxlength="10" pattern="[0-9]{5}(-{0,1}[0-9]{4})*" required>

                                @error('zip')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="field-wrapper">
                                <label for="phone">Phone</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone', $user ? $user->shipping->phone : null) }}" class="@error('phone') has-error @enderror" maxlength="20" required>

                                @error('phone')
                                    <span class="error-text invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
