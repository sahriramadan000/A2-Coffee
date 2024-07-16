<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo/logo-vmond.png') }}" class="navbar-logo" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="{{ route('dashboard') }}" class="nav-link"> Vmond Truck POS </a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                </div>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu {{ request()->routeIs('pos') ? 'active' : '' }}">
                <a href="{{ route('pos') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        <span>POS</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                <a href="{{ route('attendances.index') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                        <span>Absensi</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="#dashboard" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Dashboard</span>
                        <span class="badge badge-warning sidebar-label"> &nbsp;</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('dashboard') ? 'show' : '' }}" id="dashboard" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}"> Analytics </a>
                    </li>
                    <li>
                        <a href="#!"> Sales </a>
                    </li>
                </ul>
            </li>

            <li class="menu {{ (request()->routeIs('suppliers.index') || request()->routeIs('materials.index')) ? 'active' : '' }}">
                <a href="#inventory" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                        <span>Master Data</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ (request()->routeIs('suppliers.index') || request()->routeIs('materials.index')) ? 'show' : '' }}" id="inventory" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
                        <a href="{{ route('suppliers.index') }}"> Suppliers </a>
                    </li>
                    <li class="{{ request()->routeIs('materials.index') ? 'active' : '' }}">
                        <a href="{{ route('materials.index') }}"> Materials </a>
                    </li>
                    <li class="{{ request()->routeIs('tags.index') ? 'active' : '' }}">
                        <a href="{{ route('tags.index') }}"> Tags </a>
                    </li>
                    <li class="{{ request()->routeIs('tables.index') ? 'active' : '' }}">
                        <a href="{{ route('tables.index') }}"> Table </a>
                    </li>
                    <li class="{{ request()->routeIs('addons.index') ? 'active' : '' }}">
                        <a href="{{ route('addons.index') }}"> Addons </a>
                    </li>
                    <li class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}"> Products </a>
                    </li>
                </ul>
            </li>

            <li class="menu {{ (request()->routeIs('suppliers.index') || request()->routeIs('materials.index')) ? 'active' : '' }}">
                <a href="#report" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <span>Report</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ (request()->routeIs(['report.sales.report-gross', 'payment-method.index'])) ? 'show' : '' }}" id="report" data-bs-parent="#accordionExample">
                    {{-- <li>
                        <a href="#sales" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">
                            Sales
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                        <ul class="collapse list-unstyled sub-submenu" id="sales" data-bs-parent="#pages">
                            <li>
                                <a href="{{ route('report.sales.report-gross') }}"> Gross Profit </a>
                            </li>
                            <li>
                                <a href="{{ route('report.sales.payment-method') }}"> Payment Method </a>
                            </li>
                        </ul>
                    </li> --}}
                    <li class="{{ request()->routeIs('report.sales.report-gross') ? 'active' : '' }}">
                        <a href="{{ route('report.sales.report-gross') }}"> Gross Profit </a>
                    </li>
                    <li class="{{ request()->routeIs('report.sales.payment-method') ? 'active' : '' }}">
                        <a href="{{ route('report.sales.payment-method') }}"> Payment Method </a>
                    </li>
                    <li class="{{ request()->routeIs('materials.index') ? 'active' : '' }}">
                        <a href="{{ route('materials.index') }}"> Absensi </a>
                    </li>
                </ul>
            </li>

            <li class="menu {{ request()->routeIs('coupons.index') ? 'active' : '' }}">
                <a href="{{ route('coupons.index') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-gift"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>
                        <span>Coupon</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ request()->routeIs('customers.index') ? 'active' : '' }}">
                <a href="{{ route('customers.index') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Customer</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ request()->routeIs('users.index') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Users</span>
                    </div>
                </a>
            </li>

            <li class="menu {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>Roles</span>
                    </div>
                </a>
            </li>

            {{-- <li class="menu">
                <a href="#menuLevel1" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>Item Level</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="menuLevel1" data-bs-parent="#accordionExample">
                    <li>
                        <a href="javascript:void(0);"> Item Level 1a </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"> Item Level 1b </a>
                    </li>

                    <li>
                        <a href="#level-three" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed"> Item Level 1c <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                        <ul class="collapse list-unstyled sub-submenu" id="level-three" data-bs-parent="#pages">
                            <li>
                                <a href="javascript:void(0);"> Item Level 2a </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"> Item Level 2b </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"> Item Level 2c </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li> --}}

            {{-- <li class="menu">
                <a target="_blank" href="../../documentation/index.html" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        <span>Documentation</span>
                    </div>
                </a>
            </li> --}}
        </ul>
    </nav>
</div>
