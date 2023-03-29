@extends('layouts.web')
@section('content')
    <div class="flex-center position-ref full-height error">
        <div class="code">
            {{ 500 }}
        </div>
        <div class="message">
            {{ (isset($exception) && $exception->getMessage() ? $exception->getMessage() : null) ?? OnScreenNotificationService::firstError() ?? 'Server Error' }}
        </div>
        <div class="message">
            Please <a href="{{ cms_route('support/contact-us') }}">contact support</a>.
        </div>
    </div>
@endsection
