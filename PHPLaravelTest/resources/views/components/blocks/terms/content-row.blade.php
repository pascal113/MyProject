@if ($row && $row->tags)
    @foreach ($row->tags as $tag)
        @include('components.blocks.terms.content-tag')
    @endforeach
@endif
