@extends('layouts.web')
@section('content')
    <div class="flex-center position-ref full-height error">
        <div class="code">
            {{ 419 }}
        </div>
        <div class="message">
            {{ (isset($exception) && $exception->getMessage() ? $exception->getMessage() : null) ?? OnScreenNotificationService::firstError() ?? 'Page Expired' }}
        </div>
    </div>
@endsection
