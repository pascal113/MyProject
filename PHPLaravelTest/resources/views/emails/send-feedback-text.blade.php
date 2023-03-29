@php
    $isInternal = true;
@endphp

@extends('emails.layouts.text')
@section('content')

A visitor{!! $host ? ' on <a href="http://' . $host . '">' . $host . '</a>' : '' !!} submitted the following:

Name: {{ $name }}
Phone Number: {{ $phone }}
Email Address: {{ $email }}

@if ($regarding)
Regarding: {{ $regarding }}
@endif

@if ($regarding === 'Car Wash Location Inquiry')
    @if ($location)
Location: {{ $location }}
    @endif

    @if ($service)
Service: {{ $service }}
    @endif
@endif

@if ($regarding === 'Programs Inquiry')
    @if ($program)
Program: {{ $program }}
    @endif
@endif

@if ($regarding === 'Unlimited Wash Club')
    @if ($washClubInquiryType)
Inquiry Type: {{ $washClubInquiryType }}
    @endif

    @if ($washClubInquiryType === 'Club Change')
        @if ($washClubChangeAction)
What would you like to change?: {{ $washClubChangeAction }}
        @endif

        @if ($washClubInquiryType === 'Club Change')
            @if ($washClubChangeLicensePlate)
License Plate Number: {{ $washClubChangeLicensePlate }}
            @endif

            @if ($washClubChangeTagNumber)
Tag Number: {{ $washClubChangeTagNumber }}
            @endif
        @endif
    @endif
@endif

@if ($comments)
    @if ($regarding === 'Unlimited Wash Club' && $washClubInquiryType === 'Club Change' && $washClubChangeAction === 'Change Membership')
Reason for Change:
    @else
Comments:
    @endif

{{ $comments }}
@endif
@endsection
