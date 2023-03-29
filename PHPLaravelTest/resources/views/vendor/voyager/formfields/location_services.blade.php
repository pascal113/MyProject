@php
    $activeServices = $dataTypeContent->services->map(function($service) {
        $activeService = $service->pivot->toArray();

        $activeService['days'] = [];
        for ($i = 1; $i <= 7; $i++) {
            $activeService['days'][$i] = [
                'active' => $activeService['day_'.$i.'_opens_at'] !== null || $activeService['day_'.$i.'_closes_at'] !== null,
                'is24' => $activeService['day_'.$i.'_opens_at'] === 0 && $activeService['day_'.$i.'_closes_at'] === 86400,
                'opens_at' => $activeService['day_'.$i.'_opens_at'] === null ? null : \Carbon\Carbon::createFromTimestamp($activeService['day_'.$i.'_opens_at'])->setTimezone('UTC')->format('H:i:s'),
                'closes_at' => $activeService['day_'.$i.'_closes_at'] === null ? null : \Carbon\Carbon::createFromTimestamp($activeService['day_'.$i.'_closes_at'])->setTimezone('UTC')->format('H:i:s'),
            ];
        }

        return array_merge($service->toArray(), $activeService);
    });

    $services = $allServices->map(function($service) use ($activeServices) {
        $activesService = $activeServices->first(function($aS) use ($service) {
            return $aS['slug'] === $service->slug;
        });

        return $activesService ?? $service->toArray();
    });
@endphp

<div id="location-services-formfield">
    <location-services-formfield inline-template>
        <voyager-formfields-location-services
            :csrf-token="'{{ csrf_token() }}'"
            name="{{ $row->name }}"
            :options='@json($row->options)'
            :row='@json($row)'
            :services='@json($services->toArray())'
        />
    </location-services-formfield>
</div>

@push('javascript')
    <script>
        Vue.component('location-services-formfield', {
            props: {
            },
        });

        var locationServicesVm = new Vue({ el: '#location-services-formfield' });
    </script>
@endpush
