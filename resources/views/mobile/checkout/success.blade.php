@extends('mobile.layouts.app')

@section('content')
<section id="payment-success-screen">
    <div class="container">
        <div class="payment-success-screen-full text-center">
            <div class="payment-success-img">
                <img src="{{ asset('assets/images/success/success.gif') }}" alt="payment-img" class="img-fluid checkmark">
            </div>
            <div class="payment-success-content mt-32">
                <div class="payment-success-content-full">
                    <h1>Payment Successful!</h1>
                    <p>Your payment has been processed successfully.</p>
                    <div class="success-track-btn">
                        <a href="{{ route('mobile.homepage') }}">Go Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>	
</section>

@endsection

