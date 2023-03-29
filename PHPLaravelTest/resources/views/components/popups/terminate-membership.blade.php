<div class="popup popup-terminate-membership popup--wide" id="terminate-membership">
    <h3 class="
        popup__title
        popup__title--large
        popup__title--black
        text-center
    ">
        Terminate Wash Club Membership
    </h3>
    <hr />

    <p class="text-center font-size--24">We are sad to see you go! Terminating a wash club membership will stop the next recurring charge. You will still have services until the current term expires.</p>
    @if ($membership->club->is_bip ?? null)
        <p class="text-center font-size--24">Please note: if you suspend or terminate your membership, you will lose your BIP price and will be subject to our standard prices if you reactivate or rejoin in the future.</p>
    @endif
    <div class="text-center card--highlight">
        <p>
            <strong>{{ $membership->vehicle->label ?? 'Unknown vehicle' }}</strong><br />
            {{ $membership->club->display_name ?? null }}<br />
            Membership Tag:  {{ $membership->wash_connect->vehicle->rfid ?? '' }}
        </p>
        <p>
            <strong>The last day to use your membership will be:</strong><br />
            {{ $membership->last_membership_day->format(config('format.date')) }}
        </p>
    </div>
    <form action="{{ route('my-account.memberships.terminate', [ 'id' => $membership->wash_connect->membership->id ]) }}" method="post">
        @csrf

        <div>
        <p class="font-22"><strong>Please tell us why you are terminating this wash club membership so we can improve our service.</strong></p>
            @error('cancellation_reasons')
                <span class="error-text invalid-feedback" role="alert" style="margin-bottom: 20px;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="checkbox-group">
                @foreach (\App\Http\Controllers\MembershipController::CANCELLATION_REASONS as $reason)
                    <label><input type="checkbox" name="cancellation_reasons[{{ $reason }}]">{{ $reason }}</label>
                @endforeach
            </div>

            <p class="font-22"><strong>Comments</strong> (Optional)</p>
            <textarea name="comments" autofocus></textarea>
        </div>
        <div class="
            button-row
            button-row--center
            button-row--extra-margin-top
        ">
            <input type="submit" class="button" value="Terminate Club" />
            <button class="button featherlight-close">Nevermind</button>
        </div>
</div>

@error('cancellation_reasons')
    @push('js')
        <script>
            $(document).ready(function() {
                $.featherlight($('#terminate-membership'));
            });
        </script>
    @endpush
@enderror
