<div class="leadership-group">
    @foreach ($people as $i => $person)
        @php
            if (!($person->name ?? null) && !($person->id ?? null)) {
                $person->id = 'person-'.$i;
            }
        @endphp

        <!-- Group Item -->
        <div class="leadership-group__item">
            <a id="{{ Str::slug(trim($person->name ?? $person->id)) }}" class="margin-top-2em"></a>
            <div>
                <img class="leadership-group__img" src="{{ asset($person->photo) }}" alt="{{ htmlspecialchars($person->name ?? '') }}">
            </div>
            <ul>
                @if ($person->name ?? null)
                    <li class="section-subtitle">{{ $person->name }}</li>
                @endif
                @if ($person->jobTitle ?? null)
                    <li class="leadership-group__position">{{ $person->jobTitle }}</li>
                @endif
                @if ($person->bio ?? null)
                    <li class="bio-section">
                        @foreach ($person->bio as $index => $bio)
                            @if ($index === 1)
                                <div class="bio-more hide">
                            @endif

                            <p>{{ $bio }}</p>
                        @endforeach

                        @if (count($person->bio) > 1)
                            </div>
                        @endif
                    </li>
                    @if (count($person->bio) > 1)
                        <li class="leadership-group__link"><a class="read-more" href="#{{ Str::slug(trim($person->name ?? $person->id)) }}-read-more">Read More</a></li>
                    @endif
                @endif
            </ul>
        </div>
        <!-- /Group Item -->
    @endforeach
</div>
@push('js')
    <script>
        $(document).ready(function() {
            // Hide/Show bio
            $('.read-more').off('click');
            $('.read-more').on('click', function(e) {
                e.preventDefault();

                var more = $(e.target).parent().parent().find('.bio-section .bio-more');

                if ($(more).hasClass('hide')) {
                    $(more).removeClass('hide');

                    $(e.target).text('Read Less');
                } else {
                    $(more).addClass('hide');

                    $(e.target).text('Read More');
                }
            });
        });
    </script>
@endpush
