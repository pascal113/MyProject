            <div class="call-to-action">
                <section class="call-to-action__content">
                    @if (!empty($icon))
                        <i class="icon icon--{{ $icon }}"></i>
                    @endif
                    <h2 class='section-title'>{{ $heading }}</h2>
                    @include('components.base.wysiwyg', [ 'content' => $paragraphs])
                    @include('components.base.button', (array)($button ?? []))
                </section>
            </div>
