<div class="block-contact-phone">
  <div class="block-contact-phone__detail">
    <h3>Monday – Friday</h3>
    <span class="block-contact-phone__phone">8:00 am - 4:00 pm</span>
  </div>
  @foreach($items as $item)
  <div class="block-contact-phone__detail">
    <h3>{{ $item->heading }}</h3>
    <p>{{ $item->phoneNumber }}</p>
    @include('components.base.wysiwyg', ['content' => $item->wysiwyg])
  </div>
  @endforeach
</div>
<div class="block-contact-phone__bottom">
  <p>Looking for a specific car wash phone number?</p>
  <div class='button-row text-center'>
      <a href="{{ route('locations.index') }}" class='button'>Find a Wash</a>
  </div>
</div>
