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
                                <button class="nav-link active custom-home1-tab-btn" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-selected="true">All</button>
                            </li>
                            @foreach ($tags as $tag)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link custom-home1-tab-btn" id="pills-{{ $tag->slug }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $tag->slug }}" type="button" role="tab" aria-selected="false">{{ $tag->name }}</button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-all" role="tabpanel" tabindex="0">
                                @foreach ($products as $product)
                                    <div class="container">
                                        <div class="homepage1-tab-details">
                                            <div class="homepage1-tab-details-wrapper">
                                                <div class="home1-tab-img">
                                                    <img src="{{ asset('assets/images/produk/product-1.png') }}" alt="watch-img">
                                                </div>
                                                <div class="home1-tab-details w-100">
                                                    <div class="home1-tab-details-full">
                                                        <p class="tab-home1-txt1">{{ $product->name }}</p>
                                                        <h3 class="tab-home1-txt2">Rp. {{ number_format($product->selling_price,0) }}</h3>
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
                                @endforeach
                            </div>
                            @foreach ($tags as $tag)
                                <div class="tab-pane fade" id="pills-{{ $tag->slug }}" role="tabpanel" tabindex="0">
                                    @foreach ($productsByTag[$tag->slug] as $product)
                                        <div class="container">
                                            <div class="homepage1-tab-details">
                                                <div class="homepage1-tab-details-wrapper">
                                                    <div class="home1-tab-img">
                                                        <img src="{{ asset('assets/images/produk/product-1.png') }}" alt="watch-img">
                                                    </div>
                                                    <div class="home1-tab-details w-100">
                                                        <div class="home1-tab-details-full">
                                                            <p class="tab-home1-txt1">{{ $product->name }}</p>
                                                            <h3 class="tab-home1-txt2">Rp. {{ number_format($product->selling_price,0) }}</h3>
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
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
