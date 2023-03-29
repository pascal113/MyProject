@php
    // Backwards-compatibility for older data (before links were added to images)
    if (!empty($items)) {
        $items = collect($items)->map(function($item) {
            if (!is_object($item)) {
                return (object)[
                    'image' => $item,
                    'openInNewTab' => false,
                    'url' => null,
                ];
            }

            return $item;
        })->toArray();
    }else{
        $items = [];
    }
@endphp

@if (isset($items) && $items)
    <div class="block-images-linked">
        @forelse ($items as $item)
            @if (isset($item->image))
                <div>
                    @if (!empty($item->url->value))
                        <a href="{{ $item->url->value }}" {{ (isset($item->url->openInNewTab) && $item->url->openInNewTab) ? 'target="_blank"' : ''}}>
                            <img src="{{ asset($item->image) }}" alt="">
                        </a>
                    @else
                        <img src="{{ asset($item->image) }}" alt="">
                    @endif
                </div>
            @endif
        @endforeach
    </div>
@endif
