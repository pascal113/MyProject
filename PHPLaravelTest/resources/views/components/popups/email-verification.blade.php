<div class="popup popup-email-verification" id="{{ $id ?? 'email-verification' }}">
    @include('components.blocks.email-verification', [ 'onSuccess' => ($onSuccess ?? '').'$.featherlight.close();' ])
</div>
