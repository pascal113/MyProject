@if (($route ?? null) && ($text ?? null))
    <div class="button-row{{ ($wrapperClass ?? null) ? ' '.$wrapperClass.' ' : '' }}">
        {{--
        $queryParams is always passed in the include statment where this componet is included
        which should always be of type string. In some cases parent page can pass $queryParams,
        as a array which would break the view and this is why we need additional check if is_string
        --}}
        @if(!empty($queryParams) &&  is_string($queryParams))
            <a href="{{ $route . $queryParams }}" class="button"{!! ($openInNewTab ?? null) ? ' target="_blank"' : '' !!}>{{ $text }}</a>
        @elseif(!empty($anchorTags))
            <a href="{{ $route . $anchorTags}}" class="button"{!! ($openInNewTab ?? null) ? ' target="_blank"' : '' !!}>{{ $text }}</a>
        @else
            <a href="{{ $route }}" class="button"{!! ($openInNewTab ?? null) ? ' target="_blank"' : '' !!}>{{ $text }}</a>
        @endif
    </div>
@endif
