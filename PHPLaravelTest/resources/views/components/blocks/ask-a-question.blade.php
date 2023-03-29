<section class="block-ask-a-question {{ $class ?? null }}">
    <div class="block-ask-a-question__content">
        <img class="question-bear" src="{{ asset('images/img-bearQA.svg') }}" alt="Question Bear">
        <h2 class='section-title'>{{ $heading }}</h2>
        @include('components.base.paragraphs', ['paragraphs' => $paragraphs])
        @if ($button ?? null)
            @include('components.base.button', (array) $button)
        @endif
    </div>
</section>
