<div class="block-wash-levels">
    @if ($items)
        @foreach($items as $level)
            <!-- WASH LEVEL -->
            <div class="block-wash-level">
                <div class="block-wash-level__top js-hide-show-trigger">
                    <h3 class="section-subtitle block-wash-level__title">{{ $level->title ?? null }}<i class="plus-minus-icon"></i></h3>
                </div>
                <div class="block-wash-level__content">
                    @include('components.base.wysiwyg', ['content' => $level->details ?? null])

                    @if (isset($level->images) && is_array($level->images) && count($level->images))
                        <div class="block-wash-level__sponsors">
                            @foreach($level->images as $image)
                                <img src="{{ asset($image) }}" alt="{{ htmlspecialchars(get_img_name($image)) }}">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <!-- /WASH LEVEL -->
        @endforeach
    @endif
</div>

@push('js')
    <script>
          $(document).ready(function() {
            // Hide/Show blocks
            $('.js-hide-show-trigger').click(function(e) {
                e.preventDefault();
                $(this)
                    .parent()
                    .toggleClass('is-active');
            });
          });
    </script>
@endpush
