@if ($items && count($items))
    <div class="block-home-slider js-home-slider">
        @foreach ($items as $index => $item)
            <div class="home-slide home-slide__{{ $index }}">
                <div class="home-slide__content-wrap">
                    <div class="home-slide__content">
                        @if ($item->heading ?? null)
                            <h2 class="promo">{{ $item->heading }}</h2>
                        @endif
                        @if (isset($item->image) && $item->image)
                            <img src="{{ $item->image }}" alt="{{ htmlspecialchars($item->heading ?? '') }}" class="img-washgreen" />
                        @endif
                        @if ($item->button ?? null)
                            @include('components.base.button', (array) $item->button)
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                $('.js-home-slider').slick({
                    arrows: false,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                });
            });
        </script>
    @endpush

    @push('css')
        <style>
            @foreach ($items as $index => $item)
                .home-slide__{{ $index }} {
                    background-image: url('{{ $item->mobileBackgroundImage ?? '' }}');
                }
                @media (min-width: 800px) {
                    .home-slide__{{ $index }} {
                        background-image: url('{{ $item->desktopBackgroundImage ?? '' }}');
                    }
                }
            @endforeach
        </style>
    @endpush
@endif
