@php
    $nested = '/*';
    $urlHome = route('home');
    $urlContact = route('contact.index');
    $urlShoppingCart = route('shopping.cart');

    $isHome = Request::is($urlHome);
    $isContact = Request::is($urlContact, $urlContact.$nested);
    $isShoppingCart = Request::is($urlShoppingCart, $urlShoppingCart.$nested);
@endphp

<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top shadow">
    <div class="container-fluid">
        <div class="row col-12 col-sm-3">
            <a href="{{ route('home') }}" class="col-9 text-decoration-none text-dark">
                <img
                    src="{{ asset('assets/img/lgo.cafe.png') }}"
                    width="15%"
                    alt="logo"
                >
                <span class="fw-bolder text-uppercase">Café Lima</span>
            </a>
            <div class="col">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar"
                        aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                    <img
                        src="{{ asset('assets/img/logo.png') }}"
                        alt="logo"
                        width="15%"
                    >
                    Café Lima
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link {{ ($isHome ? 'active' : '') }}" aria-current="page"
                           href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item {{ ($isContact ? 'active' : '') }}">
                        <a class="nav-link" href="{{ route('contact.index') }}">Contácto</a>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link">
                            <p class="text-success">
                                <strong>Q</strong>
                                <span id="cart-total-price">0.00</span>
                            </p>
                        </div>
                    </li>
                    <li class="nav-item  {{ ($isShoppingCart ? 'active' : '') }}">
                        <div class="nav-link">
                            <a href="{{ route('shopping.cart') }}" class="position-relative text-success">
                                <i class="bi bi-bag-fill"></i>
                                <span id="cart-count"
                                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill p-1 text-success fs-6">0</span>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link">
                            <div x-data="{ theme: localStorage.getItem('theme') || 'auto' }">
                                <i id="theme-icon" class="bi" data-bs-theme-value="light"
                                   :data-bs-theme-value="theme === 'dark' ? 'dark' : (theme === 'auto' ? 'auto' : 'light')"
                                   :class="{ 'bi-moon': theme === 'dark', 'bi-sun': theme === 'light', 'bi-circle-half': theme === 'auto' }"
                                   @click="theme = theme === 'light' ? 'dark' : (theme === 'dark' ? 'auto' : 'light');
                                    localStorage.setItem('theme', theme);
                                    if (theme === 'auto')
                                        matchMedia('(prefers-color-scheme: dark)').matches ?
                                        document.body.classList.add('dark') :
                                        document.body.classList.remove('dark')">
                                </i>
                            </div>
                        </div>
                    </li>
                    @if(auth()->check())
                        <li class="nav-item">
                            <div class="nav-link">
                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-left text-body"></i>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
