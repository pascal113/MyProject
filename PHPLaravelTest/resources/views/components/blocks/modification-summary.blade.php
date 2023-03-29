<div class="modification-summary {{ $class ?? null }}">
    <div class="modification-summary__row">
        <p><i class="checkout-icon checkout-icon--membership"></i></p>
        <p class="section-subtitle">You are modifying your:</p>
        <ul>
            <li><strong>{{ $membership->vehicle->label ?? 'Unknown vehicle' }}</strong></li>
            <li>{{ $membership->club->display_name_with_term ?? 'Unknown club' }}</li>
            <li>Membership Tag: {{ $membership->wash_connect->vehicle->rfid ?? '' }}</li>
        </ul>
        <p class="section-subtitle section-subtitle--margin-top">Your resulting club will be:</p>
        <ul>
            <li><strong>{{ $membership->vehicle->label ?? 'Unknown vehicle' }}</strong></li>
            <li>{{ $modification->club->display_name_with_term ?? 'Unknown club' }}</li>
            <li>Membership Tag: {{ $membership->wash_connect->vehicle->rfid ?? '' }}</li>
        </ul>
    </div>
    @if ($modification->is_reactivation ?? false)
        <div class="modification-summary__row">
            <p class="section-subtitle">Status will change from:</p>
            <p>Terminated to Active</p>
        </div>
    @endif
    <div class="modification-summary__row">
        <p><i class="checkout-icon checkout-icon--calendar"></i></p>
        <p class="section-subtitle">Timing of changes:</p>
        @if ($membership->wash_connect->membership)
            <p>
                <strong>Last day to use your old membership {{ $membership->expires_at->addDays(-1)->lt(\Carbon\Carbon::now()) ? 'was' : 'will be' }}:</strong><br />
                {{ $membership->expires_at->addDays(-1)->format(config('format.date')) }}
            </p>
            <p>
                <strong>First day to use your new membership will be:</strong><br />
                @if (($modification->is_reactivation ?? false) || $membership->expires_at->lt(\Carbon\Carbon::now()))
                    {{ \Carbon\Carbon::now()->format(config('format.date')) }}
                @else
                    {{ $membership->expires_at->format(config('format.date')) }}
                @endif
            </p>
        @else
            <p>Unable to retrieve your data.</p>
        @endif
    </div>
    <div class="modification-summary__row">
        <p><i class="checkout-icon checkout-icon--cost"></i></p>
        <p class="section-subtitle">Your costs will change as follows:</p>
        <p>
            <strong>Your new costs will be:</strong><br />
            @if ($modification->order_product->display_purchase_price ?? null)
                ${{ $modification->order_product->display_purchase_price }} plus tax
            @else
                Unknown, please <a href="'.CmsRoute::get('support/contact-us').'?show=email">contact support</a>.
            @endif
        </p>
    </div>
</div>
