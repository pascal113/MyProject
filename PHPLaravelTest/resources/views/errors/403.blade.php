@extends('layouts.web')
@section('content')
    <div class="flex-center position-ref full-height error">
        <div class="code">
            {{ 403 }}
        </div>
        <div class="message">
            {{ (isset($exception) && $exception->getMessage() ? $exception->getMessage() : null) ?? OnScreenNotificationService::firstError() ?? 'Forbidden' }}
        </div>
    </div>
@endsection
