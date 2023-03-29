            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                ">
                    <h2 class='section-title section-title--section-margin'>{{ $heading }}</h2>

                    @if (isset($items) && $items)
                        <div class="flex-row">
                            @forelse ($items as $item)
                                <div class="flex-row__col">
                                    @if (isset($item->image))
                                        <img src="{{ asset($item->image) }}" alt="{{ htmlspecialchars($item->heading) }}" class="{{ $item->heading }}">
                                    @endif
                                    <h3 class="section-subtitle make-trademarks-superscript">{{ $item->heading }}</h3>
                                    @if ($item->paragraphs)
                                        @foreach ($item->paragraphs as $paragraph)
                                            <p class="make-trademarks-superscript">{{ $paragraph }}</p>
                                        @endforeach
                                    @endif
                                    @include('components.base.button', array_merge((array)($item->button ?? []), ['wrapperClass' => 'button-row--no-margin']))
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
