@extends('emails.layouts.text')
@section('content')

Congratulations! You are the recipient of an Unlimited Wash Club Membership! Simply bring this email to your nearest Brown Bear Car Wash tunnel location to activate your new membership.
@if ($washClub->data->purchase->one_time_wash_code ?? null)

Your one-time wash code is: {{ $washClub->data->purchase->one_time_wash_code }}
@endif

Your activation code is: {{ $washClub->data->redemption_code }}

@if (!($washClub->data->is_gift ?? null))
Email: {{ $user->masked_email }}

Download a PDF certificate at {{ $washClub->data->certificate_url }}

@endif

We will enter your QR Code to activate your membership when you redeem this at any Brown Bear Car Wash tunnel location ({{ url(cms_route('locations')) }})

** QUESTIONS?

------------------------------------------------------------

Visit Our Website ({{ url('/') }})

Email a Question ({{ config('mail.contact.address') }})

Give Us A Call (206.789.3700)

@endsection
