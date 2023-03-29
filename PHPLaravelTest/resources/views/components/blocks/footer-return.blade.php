<div class="footer-return">
    @if (!empty($returnLink))
        @php
            $theme = [];
            if (($returnLink['theme'] ?? null) === 'checkout') {
                $theme['text'] = 'Return to Cart';
                $theme['url'] = route('cart.index');
            } elseif (($returnLink['theme'] ?? null) === 'my-account') {
                $theme['text'] = 'Return to My Account';
                $theme['url'] = route('my-account.index');
            }
            $returnLinkText = $returnLink['text'] ?? $theme['text'] ?? null;
            $returnLinkUrl = $returnLink['url'] ?? $theme['url'] ?? null;
            $returnLinkTags = $returnLink['tags'] ?? $theme['tags'] ?? null;
        @endphp
        @if ($returnLinkText && $returnLinkUrl)
            <a class="link-return" href="{{ $returnLinkUrl }}" {!! $returnLinkTags !!}>{{ $returnLinkText }}</a>
        @endif
    @endif
    @if (!empty($button) && !empty($button['text']))
        @php
            $type = $button['type'] ?? 'a';
            $class = 'button button-right '.($button['class'] ?? '');
        @endphp

        @if ($type === 'button')
            <button type="button" class="{{ $class }}" {!! $button['tags'] ?? '' !!} data-test-id="{{ $button['testId'] }}">{{ $button['text'] }}</button>
        @elseif ($type === 'submit')
            <input type="submit" class="{{ $class }}" {!! $button['tags'] ?? '' !!} value="{{ $button['text'] }}" @if ($button['testId'] ?? null) data-test-id="{{ $button['testId'] }}" @endif/>
        @elseif (!empty($button['url']))
            <a class="{{ $class }}" href="{{ $button['url'] }}" {!! $button['tags'] ?? '' !!} @if ($button['testId'] ?? null) data-test-id="{{ $button['testId'] }}" @endif>{{ $button['text'] }}</a>
        @endif
    @endif
</div>
