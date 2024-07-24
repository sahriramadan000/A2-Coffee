@extends('mobile.layouts.app')

@section('content')
<section id="Checkout-sec" class="checkout-main">
    <div class="container">
        @forelse ($dataCarts as $key => $dataCart)
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
                                    <p class="price-code-txt1">{{ $dataCart->name }}</p>
                                    <p class="price-code-txt2">{{ number_format($dataCart->price,0) }}</p>
                                    <div class="mt-2"></div>
                                    @foreach ($dataCart->attributes['addons'] as $addon)
                                        <div class="card-without-price-sec mt-0">
                                            <div class="price-code-txt3 ">
                                                <span>{{ $addon['name'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="card-without-promocode-increment">
                                        <div class="product-incre">
                                            <input name="quantity[]" type="text" class="product__input" value="{{ $dataCart->quantity }}">
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
        <h1 class="d-none">Checkout Page</h1>
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
                <a href="enter-pin-screen.html">Confirm Order</a>
            </div>
        </div>
    </div>
</section>
@endsection
