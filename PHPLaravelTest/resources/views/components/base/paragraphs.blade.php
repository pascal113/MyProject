@if ($paragraphs)
    @foreach ($paragraphs as $paragraph)
        <p class="{{ $class ?? '' }}">{{ $paragraph }}</p>
    @endforeach
@endif
