@extends('mobile.layouts.app')

@section('content')
<section id="order2-screen">
    <div class="container">
        <div class="order2-screen-full mt-24">
            <div class="order2-screen-full-wrapp">
                <ul class="nav nav-pills mb-3 order-screen2-tab" id="order-status" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link order-screen2-tab-btn active  " id="order2-ongoing" data-bs-toggle="pill" data-bs-target="#ongoing-status" type="button" role="tab"  aria-selected="true">Ongoing</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link order-screen2-tab-btn" id="order2-completed" data-bs-toggle="pill" data-bs-target="#completed-status" type="button" role="tab"  aria-selected="false">Completed</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="ongoing-status" role="tabpanel"  tabindex="0">
                        <div class="myorder1-content-wrap">
                            <div class="myorder1-img mt-24">
                                <img src="{{ asset('assets/images/oder-successfull/no-order.png') }}" alt="img-fluid">
                            </div>
                            <div class="myorder1-content mt-24">
                                <h1>You don’t have an order yet</h1>
                                <p>You don’t have an ongoing orders at this time.</p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="completed-status" role="tabpanel"  tabindex="0">
                        <div class="ongoing-status-sec">
                            <div class="ongoing-status-sec-full">
                                <div class="ongoing-img-sec">
                                    <img src="{{ asset('assets/images/order-screen/complete-1.png') }}" alt="watch-img">
                                </div>
                                <div class="ongoing-content-sec">
                                    <div class="ongoing-content-sec-full">
                                        <p class="status-txt1">Fire-Boltt Phoenix Smart Wat...</p>
                                        <div class="status-txt2">
                                            <span>Color:tilt</span>
                                            <span>Qty:1</span>
                                        </div>
                                        <div class="status-txt3">
                                            <p >Delivered</p>
                                        </div>
                                        <div class="status-price-sec">
                                            <div class="status-price-sec-full">
                                                <div class="status-txt4">
                                                    <p>$150.00</p>
                                                </div>
                                                <div class="status-txt5">
                                                    <a href="leave-review.html">
                                                        <p>Leave Review</p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ongoing-status-sec">
                            <div class="ongoing-status-sec-full">
                                <div class="ongoing-img-sec">
                                    <img src="{{ asset('assets/images/order-screen/complete-2.png') }}" alt="cosmetic-img">
                                </div>
                                <div class="ongoing-content-sec">
                                    <div class="ongoing-content-sec-full">
                                        <p class="status-txt1">Girl’s Alloy Rose Gold Plated D...</p>
                                        <div class="status-txt2">
                                            <span>Color:Rose</span>
                                            <span>Qty:1</span>
                                        </div>
                                        <div class="status-txt3">
                                            <p>Delivered</p>
                                        </div>
                                        <div class="status-price-sec">
                                            <div class="status-price-sec-full">
                                                <div class="status-txt4">
                                                    <p>$450.00</p>
                                                </div>
                                                <div class="status-txt5">
                                                    <a href="leave-review.html">
                                                        <p>Leave Review</p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
