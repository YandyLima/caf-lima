<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME') }}</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/logo.svg') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <!-- Styles CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/frontend.css') }}">
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Alpinejs -->
    <script src="//unpkg.com/alpinejs" defer></script>

    @stack('styles')
</head>

<body>
<div class="preloader" id="loader">
    <svg class="cart" role="img" aria-label="Shopping cart line animation" viewBox="0 0 128 128" width="128px"
         height="128px" xmlns="http://www.w3.org/2000/svg">
        <g fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="8">
            <g class="cart__track" stroke="hsla(0,10%,10%,0.1)">
                <polyline points="4,4 21,4 26,22 124,22 112,64 35,64 39,80 106,80"/>
                <circle cx="43" cy="111" r="13"/>
                <circle cx="102" cy="111" r="13"/>
            </g>
            <g class="cart__lines" stroke="currentColor">
                <polyline class="cart__top" points="4,4 21,4 26,22 124,22 112,64 35,64 39,80 106,80"
                          stroke-dasharray="338 338" stroke-dashoffset="-338"/>
                <g class="cart__wheel1" transform="rotate(-90,43,111)">
                    <circle class="cart__wheel-stroke" cx="43" cy="111" r="13" stroke-dasharray="81.68 81.68"
                            stroke-dashoffset="81.68"/>
                </g>
                <g class="cart__wheel2" transform="rotate(90,102,111)">
                    <circle class="cart__wheel-stroke" cx="102" cy="111" r="13" stroke-dasharray="81.68 81.68"
                            stroke-dashoffset="81.68"/>
                </g>
            </g>
        </g>
    </svg>
    <div class="preloader__text">
        <p class="preloader__msg ms-2"> Cargando…</p>
        <p class="preloader__msg preloader__msg--last">Esto está tomando más de lo esperado...</p>
    </div>
</div>

<div class="bg-body">
    @include('frontend.layouts.navbar')
    @yield('content')
    @include('frontend.layouts.footer')
</div>


@if(session('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast bg-success-subtle" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="toast-header">
                <strong class="me-auto"><i class="bi bi-check-circle-fill text-success fs-3 me-2"></i><span>Éxito</span></strong>
                <small>{{ date('H:i:s') }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <strong class="mr-auto"> {{ session('success') }}</strong>
            </div>
        </div>
    </div>
@endif
@if(session('warning'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="toast-header">
                <strong class="me-auto"><i class="bi bi-exclamation-circle-fill text-warning fs-3 me-2"></i><span>Ha ocurrido algo inesperado</span></strong>
                <small>{{ date('H:i:s') }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <strong class="mr-auto"> {{ session('warning') }}</strong>
            </div>
        </div>
    </div>
@endif
@if(session('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast bg-danger-subtle" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="toast-header">
                <strong class="me-auto"><i
                        class="bi bi-x-circle-fill text-danger fs-3 me-2"></i><span>Error</span></strong>
                <small>{{ date('H:i:s') }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <strong class="mr-auto"> {{ session('error') }}</strong>
            </div>
        </div>
    </div>
@endif

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">
                <i class="bi bi-x-circle-fill text-danger fs-3 me-2"></i><span>Title</span></strong>
            <small>time and date for humans</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            message
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/frontend/app.js') }}"></script>

@stack('scripts')

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>

<!-- Bootstrap THEME -->
<script src="{{ asset('assets/js/frontend/theme.js') }}"></script>

</body>
</html>
