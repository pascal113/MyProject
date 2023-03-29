@extends('emails.layouts.text')
@section('content')

Good news! Your order is on the way. It will arrive soon. Your order details are below.
Here are the details:

@include('emails.partials.order-details-text', ['order' => $order])

** QUESTIONS?
------------------------------------------------------------

View Order Details (#)

Visit Our Website ({{ url('/') }})

Email a Question (club@BrownBear.com)

Give Us A Call (206.789.3700)

@endsection
