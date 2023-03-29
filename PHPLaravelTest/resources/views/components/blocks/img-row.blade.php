<div class="flex-row">
    @if ($images)
        @foreach ($images as $image)
            <div class="flex-row__col">
                <img class="img-framed" src="{{ asset($image['src'] ?? $image) }}" alt="{{ htmlspecialchars($image['alt'] ?? '') }}">
            </div>
        @endforeach
    @endif
</div>
