@if (($view ?? null) === 'browse')
    <p>{{ $data->user->full_name ?? $data->customer_full_name }} @if (!$data->user) <i>(Guest)</i> @endif</p>
@elseif (($view ?? null) === 'read')
    <p>
        @if ($dataTypeContent->user)
            <a href="{{ route('voyager.users.show', [$dataTypeContent->user->id]) }}" target="_user">{{ $dataTypeContent->user->full_name ?? $dataTypeContent->customer_full_name }}</a>
        @else
            {{ $dataTypeContent->user->full_name ?? $dataTypeContent->customer_full_name }} <i>(Guest)</i>
        @endif
    </p>
    <p>
        Email:
        @if ($dataTypeContent->user->email ?? null)
            <a href="mailto:{{ $dataTypeContent->user->email }}">{{ $dataTypeContent->user->email }}</a>
        @else
            <a href="mailto:{{ $dataTypeContent->customer_email }}">{{ $dataTypeContent->customer_email }}</a>
        @endif
    </p>
    <p>
        Phone:
        @if ($dataTypeContent->user->phone ?? null)
            {{ $dataTypeContent->user->phone }}
        @else
            <i>none</i>
        @endif
    </p>
@endif
