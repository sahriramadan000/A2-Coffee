<div class="bottom-tabbar">
    <div class="bottom-tabbar-full">
        <nav>
            <a href="{{ route('mobile.homepage') }}" class="{{ request()->routeIs('mobile.homepage') ? 'active' : '' }}">
                <img src="{{ asset('assets/images/tabbar/home.svg') }}" alt="home-icon">
                <span>
                    Home
                </span>
            </a>
            <a href="{{ route('mobile.cart') }}" class="{{ request()->routeIs('mobile.cart') ? 'active' : '' }}">
                <img src="{{ asset('assets/images/tabbar/cart.svg') }}" alt="cart-icon">
                <span>
                    Cart({{count(\Cart::session('guest')->getContent())}})
                </span>
            </a>
            <a href="{{ route('mobile.pesanan') }}" class="{{ request()->routeIs('mobile.pesanan') ? 'active' : '' }}" >
                <img src="{{ asset('assets/images/tabbar/rectangle-list.svg') }}" width="20" alt="log-out-icon">
                <span>
                    Pesanan
                </span>
            </a>
        </nav>
    </div>
</div>
