@extends('mobile.layouts.app')


@section('content')
<section id="homescreen1-deatils-page" class="homescreen1-main">
    <div class="homescreen1-deatils-page-full">
        <div class="homescreen-third-sec">
            <div class="container">
                <div class="homescreen-third-wrapper">
                    <h3>&nbsp;</h3>
                    <p>&nbsp;</p>
                    <div class="home1-shop-now-btn mt-32">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
        <div class="homescreen-second-sec mt-32">
            <div class="homescreen-second-wrapper">
                <div class="container">
                    <div class="homescreen-second-wrapper-top">
                        <div class="categories-first">
                            <h2 class="home1-txt3">Categories</h2>
                            <h3 class="d-none">Hidden</h3>
                        </div>
                        <div class="view-all-second">
                            <a href="category-page.html"><p class="view-all-txt">View all<span><img src="{{ asset('assets/svg/right-icon.svg') }}" alt="right-arrow"></span></p></a>
                        </div>
                    </div>
                </div>
                <div class="homescreen-second-wrapper-bottom mt-16">
                    <div class="homescreen-second-wrapper-slider">
                        <div class="category-slide redirect-clothes">
                            <img src="{{ asset('assets/images/category/category-1.jpg') }}" alt="category-img">
                            <div class="category-slide-content">
                                <h4>Milky Base</h4>
                                <h5>1856 Items</h5>
                            </div>
                        </div>
                        <div class="category-slide redirect-electronic">
                            <img src="{{ asset('assets/images/category/category-2.jpg') }}" alt="category-img">
                            <div class="category-slide-content">
                                <h4>Coffee Base</h4>
                                <h5>845 Items</h5>
                            </div>
                        </div>
                        <div class="category-slide redirect-clothes">
                            <img src="{{ asset('assets/images/category/category-3.jpg') }}" alt="category-img">
                            <div class="category-slide-content">
                                <h4>Tea Base</h4>
                                <h5>286 Items</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="homescreen-eight-sec mt-32">
            <div class="homescreen-eight-wrapper">
                <div class="container">
                    <div class="homescreen-second-wrapper-top">
                        <div class="categories-first">
                            <h2 class="home1-txt3">Menu</h2>
                        </div>
                        <div class="view-all-second">
                            <a href="arrivals.html"><p class="view-all-txt">View all<span><img src="{{ asset('assets/svg/right-icon.svg') }}" alt="right-arrow"></span></p></a>
                        </div>
                    </div>
                </div>
                <div class="homescreen-eight-wrapper-bottom mt-16">
                    <div class="homescreen-eight-bottom-full">
                        <ul class="nav nav-pills mb-3" id="homepage1-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active custom-home1-tab-btn" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab"  aria-selected="true">All</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link custom-home1-tab-btn" id="pills-coffee-tab" data-bs-toggle="pill" data-bs-target="#pills-coffee" type="button" role="tab"  aria-selected="false">Coffee</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link custom-home1-tab-btn" id="pills-milk-tab" data-bs-toggle="pill" data-bs-target="#pills-milk" type="button" role="tab"  aria-selected="false">Milk Base</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link custom-home1-tab-btn" id="pills-icecream-tab" data-bs-toggle="pill" data-bs-target="#pills-icecream" type="button" role="tab"  aria-selected="false">Ice Cream</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-all" role="tabpanel"  tabindex="0">
                                <div class="container">
                                    <div class="homepage1-tab-details">
                                        <div class="homepage1-tab-details-wrapper">
                                            <div class="home1-tab-img">
                                                <img src="{{ asset('assets/images/produk/product-1.png') }}" alt="watch-img">
                                            </div>
                                            <div class="home1-tab-details w-100">
                                                <div class="home1-tab-details-full">
                                                    <p class="tab-home1-txt1">Milk Shake</p>
                                                    <h3 class="tab-home1-txt2">Rp. 25.000</h3>
                                                    <div class="orange-star-tab">
                                                        <span>
                                                            <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                        </span>
                                                        <span class="tab-home1-txt3">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="home1-tab-favourite">
                                                <div class="home-page-arrival-favourite">
                                                    <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                        <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                    </a>
                                                </div>
                                                <div class="plus-bnt-home1">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="homepage1-tab-details">
                                        <div class="homepage1-tab-details-wrapper">
                                            <div class="home1-tab-img">
                                                <img src="{{ asset('assets/images/produk/product-3.png') }}" alt="watch-img">
                                            </div>
                                            <div class="home1-tab-details w-100">
                                                <div class="home1-tab-details-full">
                                                    <p class="tab-home1-txt1">Iced Coffee</p>
                                                    <h3 class="tab-home1-txt2">Rp. 25.000</h3>
                                                    <div class="orange-star-tab">
                                                        <span>
                                                            <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                        </span>
                                                        <span class="tab-home1-txt3">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="home1-tab-favourite">
                                                <div class="home-page-arrival-favourite">
                                                    <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                        <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                    </a>
                                                </div>
                                                <div class="plus-bnt-home1">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="homepage1-tab-details">
                                        <div class="homepage1-tab-details-wrapper">
                                            <div class="home1-tab-img">
                                                <img src="{{ asset('assets/images/produk/product-5.png') }}" alt="watch-img">
                                            </div>
                                            <div class="home1-tab-details w-100">
                                                <div class="home1-tab-details-full">
                                                    <p class="tab-home1-txt1">Ice Cream 1</p>
                                                    <h3 class="tab-home1-txt2">Rp. 25.000</h3>
                                                    <div class="orange-star-tab">
                                                        <span>
                                                            <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                        </span>
                                                        <span class="tab-home1-txt3">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="home1-tab-favourite">
                                                <div class="home-page-arrival-favourite">
                                                    <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                        <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                    </a>
                                                </div>
                                                <div class="plus-bnt-home1">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="homepage1-tab-details">
                                        <div class="homepage1-tab-details-wrapper">
                                            <div class="home1-tab-img">
                                                <img src="{{ asset('assets/images/produk/product-2.png') }}" alt="watch-img">
                                            </div>
                                            <div class="home1-tab-details w-100">
                                                <div class="home1-tab-details-full">
                                                    <p class="tab-home1-txt1">Ice Cream 2</p>
                                                    <h3 class="tab-home1-txt2">Rp. 25.000</h3>
                                                    <div class="orange-star-tab">
                                                        <span>
                                                            <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                        </span>
                                                        <span class="tab-home1-txt3">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="home1-tab-favourite">
                                                <div class="home-page-arrival-favourite">
                                                    <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                        <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                    </a>
                                                </div>
                                                <div class="plus-bnt-home1">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="homepage1-tab-details">
                                        <div class="homepage1-tab-details-wrapper">
                                            <div class="home1-tab-img">
                                                <img src="{{ asset('assets/images/produk/product-4.png') }}" alt="watch-img">
                                            </div>
                                            <div class="home1-tab-details w-100">
                                                <div class="home1-tab-details-full">
                                                    <p class="tab-home1-txt1">Ice Cream 3</p>
                                                    <h3 class="tab-home1-txt2">Rp. 25.000</h3>
                                                    <div class="orange-star-tab">
                                                        <span>
                                                            <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                        </span>
                                                        <span class="tab-home1-txt3">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="home1-tab-favourite">
                                                <div class="home-page-arrival-favourite">
                                                    <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                        <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                    </a>
                                                </div>
                                                <div class="plus-bnt-home1">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-coffee" role="tabpanel" tabindex="0">
                                <div class="container">
                                    <div class="homepage1-tab-details">
                                        <div class="homepage1-tab-details-wrapper">
                                            <div class="home1-tab-img">
                                                <img src="{{ asset('assets/images/produk/product-3.png') }}" alt="watch-img">
                                            </div>
                                            <div class="home1-tab-details w-100">
                                                <div class="home1-tab-details-full">
                                                    <p class="tab-home1-txt1">Iced Coffee</p>
                                                    <h3 class="tab-home1-txt2">Rp. 25.000</h3>
                                                    <div class="orange-star-tab">
                                                        <span>
                                                            <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                        </span>
                                                        <span class="tab-home1-txt3">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="home1-tab-favourite">
                                                <div class="home-page-arrival-favourite">
                                                    <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                        <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                    </a>
                                                </div>
                                                <div class="plus-bnt-home1">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-milk" role="tabpanel" tabindex="0">
                                <div class="container">
                                    <div class="homepage1-tab-details">
                                        <div class="homepage1-tab-details-wrapper">
                                            <div class="home1-tab-img">
                                                <img src="{{ asset('assets/images/produk/product-1.png') }}" alt="watch-img">
                                            </div>
                                            <div class="home1-tab-details w-100">
                                                <div class="home1-tab-details-full">
                                                    <p class="tab-home1-txt1">Milk Shake</p>
                                                    <h3 class="tab-home1-txt2">Rp. 25.000</h3>
                                                    <div class="orange-star-tab">
                                                        <span>
                                                            <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                        </span>
                                                        <span class="tab-home1-txt3">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="home1-tab-favourite">
                                                <div class="home-page-arrival-favourite">
                                                    <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                        <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                    </a>
                                                </div>
                                                <div class="plus-bnt-home1">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-icecream" role="tabpanel" tabindex="0">
                                <div class="container">
                                    <div class="homepage1-tab-details">
                                        <div class="homepage1-tab-details-wrapper">
                                            <div class="home1-tab-img">
                                                <img src="{{ asset('assets/images/produk/product-5.png') }}" alt="watch-img">
                                            </div>
                                            <div class="home1-tab-details w-100">
                                                <div class="home1-tab-details-full">
                                                    <p class="tab-home1-txt1">Ice Cream 1</p>
                                                    <h3 class="tab-home1-txt2">Rp. 25.000</h3>
                                                    <div class="orange-star-tab">
                                                        <span>
                                                            <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                        </span>
                                                        <span class="tab-home1-txt3">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="home1-tab-favourite">
                                                <div class="home-page-arrival-favourite">
                                                    <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                        <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                    </a>
                                                </div>
                                                <div class="plus-bnt-home1">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="homepage1-tab-details">
                                        <div class="homepage1-tab-details-wrapper">
                                            <div class="home1-tab-img">
                                                <img src="{{ asset('assets/images/produk/product-2.png') }}" alt="watch-img">
                                            </div>
                                            <div class="home1-tab-details w-100">
                                                <div class="home1-tab-details-full">
                                                    <p class="tab-home1-txt1">Ice Cream 2</p>
                                                    <h3 class="tab-home1-txt2">Rp. 25.000</h3>
                                                    <div class="orange-star-tab">
                                                        <span>
                                                            <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                        </span>
                                                        <span class="tab-home1-txt3">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="home1-tab-favourite">
                                                <div class="home-page-arrival-favourite">
                                                    <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                        <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                    </a>
                                                </div>
                                                <div class="plus-bnt-home1">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="homepage1-tab-details">
                                        <div class="homepage1-tab-details-wrapper">
                                            <div class="home1-tab-img">
                                                <img src="{{ asset('assets/images/produk/product-4.png') }}" alt="watch-img">
                                            </div>
                                            <div class="home1-tab-details w-100">
                                                <div class="home1-tab-details-full">
                                                    <p class="tab-home1-txt1">Ice Cream 3</p>
                                                    <h3 class="tab-home1-txt2">Rp. 25.000</h3>
                                                    <div class="orange-star-tab">
                                                        <span>
                                                            <img src="{{ asset('assets/svg/orange-star18.svg') }}" alt="star-img">
                                                        </span>
                                                        <span class="tab-home1-txt3">4.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="home1-tab-favourite">
                                                <div class="home-page-arrival-favourite">
                                                    <a href="javascript:void(0);" class="item-bookmark" tabindex="-1">
                                                        <img src="{{ asset('assets/svg/unfill-heart.svg') }}" alt="unfill-heart">
                                                    </a>
                                                </div>
                                                <div class="plus-bnt-home1">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
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
