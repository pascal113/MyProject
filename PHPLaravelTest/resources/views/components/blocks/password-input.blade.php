@php
    $name = $name ?? 'password';
@endphp

<div class="password-reveal-wrap">
    <input name="{{ $name }}" id="{{ $name }}" type="password" @if (!($ignorePost ?? false)) value="{{ old($name) }}" class="@error($name) has-error @enderror" @endif required @if ($preventAutocomplete ?? false) autocomplete="new-password" @endif @if ($testId ?? null) data-test-id="{{ $testId }}" @endif>
    <button type="button" class="password-reveal" tabindex="-1"></button>
</div>

@if (!($ignorePost ?? false))
    @error($name)
        <span class="error-text invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
@endif

@push('js')
    <script>
        (function($) {
            'use strict';

            $(document).ready(function() {
                // Password Show/Hide
                $('.password-reveal').off('click');
                $('.password-reveal').click(function(event) {
                    $(event.target).hasClass('show-password') ? hidePassword($(event.target)) : showPassword($(event.target));
                });
                function hidePassword(elem) {
                    elem.removeClass('show-password').addClass('hide-password');
                    elem.prev('input').attr('type', 'password');
                }
                function showPassword(elem) {
                    elem.removeClass('hide-password').addClass('show-password');
                    elem.prev('input').attr('type', 'text');
                }
            });
        })(jQuery);
    </script>
@endpush
