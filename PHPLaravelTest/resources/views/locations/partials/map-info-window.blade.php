<div class="location-map-detail">
    <a class="location-map-detail__title" href="{{ $location->url }}">{{ $location->title }}</a>
    <p>
        {{ $location->address_line_1 }}<br />
        {{ $location->address_line_2 }}
    </p>
    <p>{{ $location->phone }}</p>
    @if ($location->temporarily_closed)
        <p class="location-map-detail__temporarily-closed">Temporarily Closed</p>
    @endif
    <ul>
        @foreach ($location->services as $service)
            <li>
                <i class="icon icon--small icon--{{ $service->icon_class }}"></i>
                {{ $service->name }} <a href='{{ $location->url }}#{{ $service->slug }}'>{{ $service->isOpen ? 'Open' : 'Closed' }}</a>
            </li>
        @endforeach
    </ul>
</div>
