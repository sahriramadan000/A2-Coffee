@extends('mobile.layouts.app')

@section('content')
<section id="Checkout-sec" class="checkout-main">
    <div class="container">
        <h1 class="d-none">Checkout Page</h1>
        <div class="Checkout-sec-full">
            <div class="Checkout-first-sec">
                <div class="Checkout-first-sec-full">
                    <span>My Order</span>
                    <span>$255.00</span>
                </div>
                <div class="Checkout-border"></div>
            </div>
            <div class="Checkout-second-sec">
                <div class="Checkout-second-full">
                    <div class="check-deatils">
                        <span class="check-txt1">Preneum Women’s George...</span>
                        <span class="check-txt2">1 x $300.00</span>
                    </div>
                    <div class="check-deatils">
                        <span class="check-txt1">Go Buzz Bluetooth Calling...</span>
                        <span class="check-txt2">1 x $300.00</span>
                    </div>
                    <div class="check-deatils">
                        <span class="check-txt1">Discount</span>
                        <span class="check-txt2 col-green">1 x $300.00</span>
                    </div>
                    <div class="check-deatils">
                        <span class="check-txt1">Delivery</span>
                        <span class="check-txt2 col-red">1 x $300.00</span>
                    </div>
                </div>
            </div>
            <div class="Checkout-third-sec">
                <div class="Checkout-third-sec-full">
                    <a href="#checkout-modal" data-bs-toggle="modal" >
                        <div class="shopping-deatils">
                            <div class="check-icon-sec">
                                <img src="{{ asset('assets/svg/location-icon.svg') }}" alt="location-icon">
                            </div>
                            <div class="check-deatils-sec">
                                <p class="shipp-txt1">Shipping Details</p>
                                <p class="shipp-txt2">8000 S Kirkland Ave, Chicago, IL 6065</p>
                            </div>
                            <div class="check-back-sec">
                                <img src="{{ asset('assets/svg/right-icon.svg') }}" alt="right-icon">
                            </div>
                        </div>
                        <div class="shipping-boder"></div>
                    </a>
                    <a href="#checkout-modal-payment" data-bs-toggle="modal">
                        <div class="shopping-deatils mt-16">
                            <div class="check-icon-sec">
                                <img src="{{ asset('assets/images/account-screen/wallet.svg') }}" alt="wallet-icon">
                            </div>
                            <div class="check-deatils-sec">
                                <p class="shipp-txt1">Payment Method</p>
                                <p class="shipp-txt2">xxxx xxxx xxxx 4865</p>
                            </div>
                            <div class="check-back-sec">
                                <img src="{{ asset('assets/svg/right-icon.svg') }}" alt="right-icon">
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="Checkout-fourth-sec">
                <div class="Checkout-fourth-sec-full">
                    <form class="checkout-form">
                        <label>Additional Notes:</label>
                        <textarea rows="4" placeholder="Write a comment..." class="product-textarea"></textarea>
                    </form>
                </div>
            </div>
            <div class="confirm-order-btn">
                <a href="enter-pin-screen.html">Confirm Order</a>
            </div>
        </div>
    </div>
</section>

<!-- Checkout Shipping Modal Section Start -->
<div class="modal fade" id="checkout-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content checkout-modal-content">
            <div class="modal-header">
                <p class="checkout-modal-txt1">Choose Shipping Details</p>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-check border-bottom px-0 custom-radio">
                        <input class="form-check-input" type="radio" name="shipping" id="shipping1" value="shipping1" checked>
                        <label class="form-check-label checkout-modal-lbl" for="shipping1">
                            Home
                            <span>8000 S Kirkland Ave, Chicago, IL 6065</span>
                        </label>
                    </div>
                    <div class="form-check border-bottom px-0 custom-radio">
                        <input class="form-check-input" type="radio" name="shipping" id="shipping2" value="shipping2">
                        <label class="form-check-label checkout-modal-lbl" for="shipping2">
                            Work
                            <span>157 Parkview Ave, Chicago, IL 6058</span>
                        </label>
                    </div>
                    <div class="form-check border-bottom px-0 custom-radio">
                        <input class="form-check-input" type="radio" name="shipping" id="shipping3" value="shipping3">
                        <label class="form-check-label checkout-modal-lbl chek-1" for="shipping3">
                            Other
                            <span>1001 Latin Ave, Chicago, IL 6060</span>
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Checkout Shipping Modal Section End -->
<!-- Checkout Payment Modal Section Start -->
<div class="modal fade" id="checkout-modal-payment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content checkout-modal-content">
            <div class="modal-header">
                <p class="checkout-modal-txt1">Choose Payment Method</p>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-check border-bottom px-0 custom-radio ">
                        <input class="form-check-input" type="radio" name="Payment" id="Payment1" value="Payment1">
                        <label class="form-check-label checkout-modal-lbl-payment" for="Payment1">
                            <span class="payment-type">
                                <img src="{{ asset('assets/images/account-screen/wallet.svg') }}" alt="wallet-icon" class="black-icon">
                            </span>
                            <span class="wallet-txt1">My Wallet</span>
                        </label>
                    </div>
                    <div class="form-check border-bottom px-0 custom-radio">
                        <input class="form-check-input" type="radio" name="Payment" id="Payment2" value="Payment2" checked>
                        <label class="form-check-label checkout-modal-lbl-payment" for="Payment2">
                            <span class="payment-type">
                                <img src="{{ asset('assets/svg/payment1.svg') }}" alt="Payment-icon">
                            </span>
                            <span class="wallet-txt1">**** 4864</span>
                        </label>
                    </div>
                    <div class="form-check border-bottom px-0 custom-radio">
                        <input class="form-check-input" type="radio" name="Payment" id="Payment3" value="Payment3">
                        <label class="form-check-label checkout-modal-lbl-payment" for="Payment3">
                            <span class="payment-type">
                                <img src="{{ asset('assets/svg/payment2.svg') }}" alt="Payment-icon">
                            </span>
                            <span class="wallet-txt1">**** 3597</span>
                        </label>
                    </div>
                    <div class="form-check border-bottom px-0 custom-radio">
                        <input class="form-check-input" type="radio" name="Payment" id="Payment4" value="Payment4">
                        <label class="form-check-label checkout-modal-lbl-payment" for="Payment4">
                            <span class="payment-type">
                                <img src="{{ asset('assets/svg/payment3.svg') }}" alt="Payment-icon">
                            </span>
                            <span class="wallet-txt1">Connected</span>
                        </label>
                    </div>
                    <div class="form-check border-bottom px-0 custom-radio">
                        <input class="form-check-input" type="radio" name="Payment" id="Payment5" value="Payment5">
                        <label class="form-check-label checkout-modal-lbl-payment" for="Payment5">
                            <span class="payment-type">
                                <img src="{{ asset('assets/svg/payment4.svg') }}" alt="Payment-icon">
                            </span>
                            <span class="wallet-txt1">Connected</span>
                        </label>
                    </div>
                    <div class="form-check border-bottom px-0 custom-radio">
                        <input class="form-check-input" type="radio" name="Payment" id="Payment6" value="Payment6">
                        <label class="form-check-label checkout-modal-lbl-payment" for="Payment6">
                            <span class="payment-type">
                                <img src="{{ asset('assets/svg/payment5.svg') }}" alt="Payment-icon">
                            </span>
                            <span class="wallet-txt1">Connected</span>
                        </label>
                    </div>
                    <div class="form-check border-bottom px-0 custom-radio">
                        <input class="form-check-input" type="radio" name="Payment" id="Payment7" value="Payment7">
                        <label class="form-check-label checkout-modal-lbl-payment" for="Payment7">
                            <span class="payment-type">
                                <img src="{{ asset('assets/svg/payment6.svg') }}" alt="Payment-icon">
                            </span>
                            <span class="wallet-txt1">Not Connect</span>
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Checkout Payment Modal Section End -->
@endsection
