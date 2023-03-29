<!-- Blade component to allow changing content -->
<div class="block-page-hero {{ $class ?? ''  }}" data-test-id="hero">
    <h2 class="promo block-page-hero__title" data-test-id="hero:heading">{{ $heading ?? ''  }}</h2>
    <img src="{{ asset($image ?? '') }}" alt="{{ htmlspecialchars($heading ?? '') }}" data-test-id="hero:image">
</div>
