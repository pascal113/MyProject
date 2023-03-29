@php
    use Ramsey\Uuid\Uuid;
    $uuid = Uuid::uuid4();
@endphp
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--sm
                    text-center
                ">
                    <h2 class='section-title'>{{ $heading }}</h2>
                    @if (!empty($introParagraph))
                        <div class="remove-last-margin  section-intro">
                            @include('components.base.paragraphs', ['paragraphs' => $introParagraph])
                        </div>
                    @endif
                    @if (isset($items) && $items)
                        <div class="
                            @if (!empty($introParagraph))
                                section-row
                                section-row--padded
                                section-row--sm
                                text-center
                            @endif
                        ">
                            @foreach($items as $item)
                                <div class="block-accordion">
                                    <div class="block-accordion__top js-hide-show-trigger-{{ $uuid }}">
                                        <h3 class="section-subtitle block-accordion__title">{{ $item->heading }}<i class="plus-minus-icon"></i></h3>
                                    </div>
                                    <div class="block-accordion__content">
                                        @include('components.base.wysiwyg', ['content' => $item->wysiwyg])

                                        @if (is_array($item->images) && count($item->images))
                                            <div class="block-accordion__sponsors">
                                                @foreach($item->images as $image)
                                                    <img src="{{ asset($image) }}" alt="{{ get_img_name($image) }}">
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            @push('js')
                <script>
                    $(document).ready(function() {
                        // Hide/Show blocks
                        $('.js-hide-show-trigger-{{ $uuid }}').click(function(e) {
                            e.preventDefault();
                            $(this)
                                .parent()
                                .toggleClass('is-active');
                        });
                    });
                </script>
            @endpush
