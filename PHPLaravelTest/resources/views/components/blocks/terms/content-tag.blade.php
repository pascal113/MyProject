@if ($tag)
    @if (is_string($tag))
        {{ $tag }}
    @else
        @if (!is_array($tag->content))
            @php
                $tag->content = [$tag->content]
            @endphp
        @endif

        @switch ($tag->type)
            @case('View')
                <div class="view-box">
                    @foreach ($tag->content as $tag)
                        @include('components.blocks.terms.content-tag')
                    @endforeach
                </div>
            @break

            @case('H3')
                <h3>
                    @foreach ($tag->content as $tag)
                        @include('components.blocks.terms.content-tag')
                    @endforeach
                </h3>
            @break

            @case('Em')
                <em>
                    @foreach ($tag->content as $tag)
                        @include('components.blocks.terms.content-tag')
                    @endforeach
                </em>
            @break

            @case('B')
                <b>
                    @foreach ($tag->content as $tag)
                        @include('components.blocks.terms.content-tag')
                    @endforeach
                </b>
            @break

            @case('U')
                <u>
                    @foreach ($tag->content as $tag)
                        @include('components.blocks.terms.content-tag')
                    @endforeach
                </u>
            @break

            @case('Small')
                <small>
                    @foreach ($tag->content as $tag)
                        @include('components.blocks.terms.content-tag')
                    @endforeach
                </small>
            @break

            @case('Span')
                <span>
                    @foreach ($tag->content as $tag)
                        @include('components.blocks.terms.content-tag')
                    @endforeach
                </span>
            @break

            @case('Text')
            @default
                <p class="terms{{ (!empty($tag->style) && is_string($tag->style)) ? ' '.$tag->style : '' }}">
                    @foreach ($tag->content as $tag)
                        @include('components.blocks.terms.content-tag')
                    @endforeach
                </p>
        @endswitch
    @endif
@endif
