@php
    if ($message ?? null) {
        $notifications = \App\Services\OnScreenNotificationService::parse([
            'message' => $message ?? null,
            'level' => $level ?? null,
            'prefix' => $prefix ?? null,
            'message' => $message ?? null,
            'additionalInfo' => $additionalInfo ?? null,
            'buttons' => $buttons ?? null,
        ]);
    }
@endphp

@foreach ($notifications ?? \App\Services\OnScreenNotificationService::get() as $notification)
    @if ($notification->message)
        <div {!! ($id ?? null) ? 'id="'.$id.'"' : '' !!} class="notifications notifications--{{ $notification->level }}">
            <p class="message">
                @if ($notification->prefix)
                    <strong>{!! $notification->prefix !!} </strong>
                @endif
                {!! $notification->message !!}
            </p>
            @if ($notification->additionalInfo)
                <p class="additional-info">{!! $notification->additionalInfo !!}</p>
            @endif

            {{ $slot ?? '' }}

            @if (count($notification->buttons))
                <div class="button-row">
                    @foreach ($notification->buttons as $button)
                        @if (!empty($button['text']))
                            @php
                                $type = $button['type'] ?? 'button';
                                $class = 'button button--small '.($button['class'] ?? '');
                            @endphp

                            @if ($type === 'button')
                                <button type="button" class="{{ $class }}" {!! $button['tags'] ?? '' !!}>{{ $button['text'] }}</button>
                            @elseif ($type === 'submit')
                                <input type="submit" class="{{ $class }}" {!! $button['tags'] ?? '' !!} value="{{ $button['text'] }}" />
                            @elseif (!empty($button['url']))
                                <a class="{{ $class }}" href="{{ $button['url'] }}" {!! $button['tags'] ?? '' !!}>{{ $button['text'] }}</a>
                            @endif
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    @endif
@endforeach
