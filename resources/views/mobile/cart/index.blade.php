@extends('mobile.layouts.app')

@section('content')
<section id="cart-without-promocode">
    <form action="{{ route('mobile.checkout', ['token' => md5(strtotime("now")), 'kode_meja' => Request::get('kode_meja')]) }}" method="POST" class="">
        @csrf
        <div class="container">
            <h1 class="d-none">Cart Details</h1>
            <h2 class="d-none">Cart</h2>
            @forelse ($dataCarts as $key => $dataCart)
            <div class="cart-without-promocode-full">
                <div class="cart-without-promocode-first">
                    <div class="cart-without-promocode-first-full">
                        <div>
                            @if ($dataCart->attributes['product']['picture'] == null)
                                <div class="cart-without-img-sec">
                                    <img src="{{ asset('assets/images/cart-without-promocode/clothes-1.png') }}" alt="clothes-img">
                                </div>
                            @else
                                <div class="cart-without-img-sec">
                                    <img src="{{ asset('images/products/'.$dataCart->attributes['product']['picture']) }}" alt="clothes-img">
                                </div>
                            @endif
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
                                        {{-- <a href="javascript:void(0)" class="product__minus sub">
                                            <span>
                                                <img src="{{ asset('assets/svg/minus-icon.svg') }}" alt="minus-icon">
                                            </span>
                                        </a> --}}
                                        <input readonly name="quantity[]" type="text" class="product__input" value="{{ $dataCart->quantity }}">
                                        {{-- <a href="javascript:void(0)" class="product__plus add">
                                            <span>
                                                <img src="{{ asset('assets/svg/plus-icon.svg') }}" alt="plus-icon">
                                            </span>
                                        </a> --}}
                                        <a href="{{ route('mobile.delete-item', $key)}}" class="" style="border-bottom: 1px dashed red;">
                                            <i class='bx bx-trash font-14 text-danger'>trash</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cart-boder mt-16"></div>
                </div>
            </div>
            @empty
            <div class="card-without-price-sec mt-0">
                <div class="price-code-txt3 ">
                    <span>No Product Added</span>
                </div>
            </div>
            @endforelse

            @if ($dataCarts->count() >= 1)
                <div class="check-page-bottom mt-24">
                    <div class="check-page-bottom-deatails">
                        <div class="check-price-name1">
                            <p>Subtotal</p>
                        </div>
                        <div class="check-price-list1">
                            <p>Rp.{{ number_format($subTotal,0) }}</p>
                        </div>
                    </div>
                    <div class="check-page-bottom-deatails mt-8">
                        <div class="check-price-name">
                            <p>Layanan</p>
                        </div>
                        <div>
                            <p class="col-green">Rp.{{ number_format($layanan,0)}}</p>
                        </div>
                    </div>
                    <div class="check-page-bottom-deatails mt-8">
                        <div class="check-price-name">
                            <p>PPN</p>
                        </div>
                        <div>
                            <p class="col-red">Rp.{{ number_format($ppn,0)}}</p>
                        </div>
                    </div>
                    <div class="cart-boder mt-24"></div>
                </div>
                <div class="without-code-last mt-24">
                    <div class="without-code-last-full">
                        <div>
                            <p class="total-txt">Total:</p>
                            <p class="price-txt">Rp.{{ number_format($total,0)}}</p>
                        </div>
                        <div class="proceed-to check-btn">
                            <button class="btn btn" type="submit">Proceed To Checkout</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </form>
</section>
@endsection
