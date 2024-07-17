@extends('mobile.layouts.app')

@section('content')
<section id="cart-without-promocode">
    <div class="container">
        <h1 class="d-none">Cart Details</h1>
        <h2 class="d-none">Cart</h2>
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
                            <p class="price-code-txt1">Preneum Women's Georgette a-line Knee-Long</p>
                            <p class="price-code-txt2">$150.00</p>
                            <div class="mt-2"></div>
                            <div class="card-without-price-sec mt-0">
                                <div class="price-code-txt3 ">
                                    <span>Sugar:</span>
                                    <span>Normal</span>
                                </div>
                            </div>
                            <div class="card-without-price-sec mt-0">
                                <div  class="price-code-txt3">
                                    <span>Ice:</span>
                                    <span>Normal</span>
                                </div>
                            </div>
                            <div class="card-without-promocode-increment">
                                <div class="product-incre">
                                    <a href="javascript:void(0)" class="product__minus sub">
                                        <span>
                                            <img src="{{ asset('assets/svg/minus-icon.svg') }}" alt="minus-icon">
                                        </span>
                                    </a>
                                    <input name="quantity" type="text" class="product__input" value="1">
                                    <a href="javascript:void(0)" class="product__plus add">
                                        <span>
                                            <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart-boder mt-16"></div>
            </div>
        </div>
        {{-- <div class="without-code-second">
            <div class="without-code-second-full">
                <p>Promo Code:</p>
                <div class="code-details mt-16">
                    <div class="enter-code-promocode1">
                        <input type="text" value="20firstorder">
                    </div>
                    <div class="code-plus-btn code-cancel-btn">
                        <a href="javascript:void(0)">
                            <img src="{{ asset('assets/svg/cancel-icon.svg') }}" alt="cancel-icon">
                        </a>
                    </div>
                </div>
            </div>
            <div class="cart-boder mt-24"></div>
        </div> --}}
        <div class="check-page-bottom mt-24">
            <div class="check-page-bottom-deatails">
                <div class="check-price-name1">
                    <p>Subtotal</p>
                </div>
                <div class="check-price-list1">
                    <p>$300.00</p>
                </div>
            </div>
            <div class="check-page-bottom-deatails mt-8">
                <div class="check-price-name">
                    <p>Discount</p>
                </div>
                <div>
                    <p class="col-green">$0.00</p>
                </div>
            </div>
            <div class="check-page-bottom-deatails mt-8">
                <div class="check-price-name">
                    <p>Delivery</p>
                </div>
                <div>
                    <p class="col-red">+$15.00</p>
                </div>
            </div>
            <div class="cart-boder mt-24"></div>
        </div>
        <div class="without-code-last mt-24">
            <div class="without-code-last-full">
                <div>
                    <p class="total-txt">Total:</p>
                    <p class="price-txt">$255.00</p>
                </div>
                <div class="proceed-to check-btn">
                    <a href="{{ route('mobile.checkout') }}">Proceed To Checkout</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
