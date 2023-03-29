@extends('emails.layouts.text')
@section('content')

Thank you for shopping with us! We received your order and we are working on processing it.
Here are the details:

@include('emails.partials.order-details-text', ['order' => $order])

** QUESTIONS?
------------------------------------------------------------

View Order Details ({{ route('my-account.orders.show', [$order->id]) }})

Visit Our Website ({{ url('/') }})

Email a Question ({{ config('mail.contact.address') }})

Give Us A Call (206.789.3700)

@endsection
