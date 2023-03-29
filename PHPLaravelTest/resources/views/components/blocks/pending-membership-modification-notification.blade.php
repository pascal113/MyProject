    @component('components.blocks.notifications', [
        'message' => 'Converting'.(($membership->pending_modification->club->display_name_with_term ?? null) ? ' to '.$membership->pending_modification->club->display_name_with_term : '').($membership->wash_connect->membership ? ' on '.$membership->expires_at->format(config('format.date')) : ''),
        'level' => 'info',
        'buttons' => array_merge($buttons ?? [], [
            $membership->pending_modification->is_cancelable ? [
                'text' => 'Cancel Conversion',
                'type' => 'button',
                'tags' => 'data-featherlight="#confirm-cancel-modification-for-'.$membership->wash_connect->membership->id.'"',
            ] : [],
        ]),
    ])
        @if ($membership->pending_modification->is_cancelable)
            <form id="cancel-modification-for-{{ $membership->wash_connect->membership->id }}" action="{{ route('my-account.memberships.cancel-modification', [ 'washConnectId' => $membership->wash_connect->membership->id ]) }}" method="post">@csrf</form>
        @endif
        {{ $slot }}
    @endcomponent

    @if ($membership->pending_modification->is_cancelable)
        @include('components.popups.confirm', [
            'actionText' => 'cancel this conversion',
            'confirmText' => 'Yes, Cancel Conversion',
            'id' => 'confirm-cancel-modification-for-'.$membership->wash_connect->membership->id,
            'onConfirm' => "$('#cancel-modification-for-".$membership->wash_connect->membership->id."').submit();",
        ])
    @endif
