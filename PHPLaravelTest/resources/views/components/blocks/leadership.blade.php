@php
    if ($headshots) {
        $headshotRows = array_map(function($row) {
            return array_reverse($row);
        }, array_reverse(array_chunk(array_reverse($headshots), ceil(count($headshots) / 2))));
    }
@endphp

<section class="block-leadership">
    <h2 class='section-title'>{{ $title ?? 'Leadership'}}</h2>
    <div class="block-leadership__rows">
        @if (isset($headshotRows))
            <div class="block-leadership__row">
                @foreach ($headshotRows[0] as $image)
                    <img src="{{ asset($image) }}" alt="">
                @endforeach
            </div>
        @endif
        @if (isset($headshotRows))
            @if (isset($headshotRows[1]))
                <div class="block-leadership__row">
                    @foreach ($headshotRows[1] as $image)
                        <img src="{{ asset($image) }}" alt="">
                    @endforeach
                </div>
            @endif
        @endif
        @foreach ($paragraphs ?? [] as $paragraph)
            <p class="width-small">{{ $paragraph }}</p>
        @endforeach
    </div>
    @if ($button ?? null)
        @include('components.base.button', array_merge((array)$button, ['wrapperClass' => 'button-row--no-margin']))
    @endif
</section>
