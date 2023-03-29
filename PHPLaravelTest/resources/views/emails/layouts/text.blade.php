@php
    $isInternal = isset($isInternal) ? $isInternal : false;
@endphp

@include('emails.partials.header-text')

@yield('content')

@include('emails.partials.footer-text')
