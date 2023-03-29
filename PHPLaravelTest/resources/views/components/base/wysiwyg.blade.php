@if (!empty($content))
    <div class="wysiwyg-content {{ isset($class) && $class ? $class : '' }} make-trademarks-superscript">
        {!! $content !!}
    </div>
@endif
