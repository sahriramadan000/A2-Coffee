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
    <div class="container">
        @forelse ($order_products as $orderProduct)
            <div class="container">
                <h1 class="d-none">Checkout</h1>
                <div class="cart-without-promocode-full">
                    <div class="cart-without-promocode-first">
                        <div class="cart-without-promocode-first-full">
                            <div>
                                <div class="cart-without-img-sec">
                                    <img src="{{ asset('assets/images/cart-without-promocode/clothes-1.png') }}" alt="clothes-img">
                                </div>
                            </div>
                            <div class="cart-without-content-sec">
                                <div class="cart-without-content-sec-full">
                                    <p class="price-code-txt1">{{ $orderProduct->name }}</p>
                                    <p class="price-code-txt2">{{ number_format($orderProduct->selling_price,0) }}</p>
                                    <div class="mt-2"></div>
                                    @forelse ($orderProduct->orderProductAddons as $addon)
                                        <div class="card-without-price-sec mt-0">
                                            <div class="price-code-txt3 ">
                                                <span>{{ $addon['name'] }}</span>
                                                <span>{{ number_format($addon['price'],0) }}</span>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                    <div class="card-without-promocode-increment">
                                        <div class="product-incre">
                                            <input readonly name="quantity[]" type="text" class="product__input" value="{{ $orderProduct->qty }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cart-boder mt-16"></div>
                    </div>
                </div>
            </div>
        @empty
        <div class="card-without-price-sec mt-0">
            <div class="price-code-txt3 ">
                <span>No Product Added</span>
            </div>
        </div>
        @endforelse
        <h1 class="d-none">Orders</h1>
        <div class="Checkout-sec-full">
            <div class="Checkout-first-sec">
                <div class="Checkout-first-sec-full">
                    <span>My Order</span>
                    <span>Rp.{{ number_format($orders->total,0) }}</span>
                </div>
                <div class="Checkout-border"></div>
            </div>
            <div class="Checkout-second-sec">
                <div class="Checkout-second-full">
                    <div class="check-deatils">
                        <span class="check-txt1">Sub Total</span>
                        <span class="check-txt2">Rp.{{ number_format($orders->subtotal) }}</span>
                    </div>
                    <div class="check-deatils">
                        <span class="check-txt1">Layanan</span>
                        <span class="check-txt2">Rp.{{ number_format($orders->service) }}</span>
                    </div>
                    <div class="check-deatils">
                        <span class="check-txt1">pb01</span>
                        <span class="check-txt2 col-green">Rp.{{ number_format($orders->pb01) }}</span>
                    </div>
                </div>
            </div>
            <div class="confirm-order-btn">
                <button type="submit">Confirm Order</button>
            </div>
        </div>
    </div>
</section>

@endsection

