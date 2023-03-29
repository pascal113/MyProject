<div class="popup popup-confirm" id="{{ $id ?? 'confirm-popup' }}">
    <h3 class="
        popup__title
        popup__title--large
        popup__title--black
        text-center
    ">
        {{ $titleText ?? 'Please confirm' }}
    </h3>
    <p class="text-center font-size--22">{{ $paragraphText ?? 'Are you sure'.(!empty($actionText) ? ' you want to '.$actionText : '').'?' }}</p>
    <div class="
        button-row
        button-row--block
        button-row--extra-margin-top
    ">
        <button class="button featherlight-close"{!! ($onConfirm ?? null) ? ' onclick="'.$onConfirm.'"' : '' !!}>{{ $confirmText ?? 'Yes, Do It' }}</button>
        <button class="button featherlight-close"{!! ($onCancel ?? null) ? ' onclick="'.$onCancel.'"' : '' !!}>{{ $cancelText ?? 'Nevermind' }}</button>
    </div>
</div>
