@if (!empty($image) || !empty($quote) || !empty($attribution))
    <div class="block-pull-quote {{ $class ?? null }}">
        <div class="block-pull-quote__content">
            @if (!empty($image))
                <div class="block-pull-quote__avatar">
                    <img src="{{ asset($image) }}" alt="{{ htmlspecialchars($attribution ?? '') }}">
                </div>
            @endif
            @if (!empty($quote))
                <blockquote>
                    {{ $quote }}
                </blockquote>
            @endif
            @if (!empty($attribution))
                <cite>{{ $attribution }}</cite>
            @endif
        </div>
    </div>
@endif
