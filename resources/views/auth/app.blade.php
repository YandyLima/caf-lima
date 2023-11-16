<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Favicons -->
    <link href="assets/img/lgo.cafe.png" rel="icon">
    <link href="assets/img/lgo.cafe.png" rel="apple-touch-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Template -->
    <link rel="stylesheet" href="assets/css/login.css">
    <!-- Scripts -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- Title -->
    <title>@yield('title')</title>
</head>
<body>
<div class="main-container">
    <section class="ftco-section bg-img bg-cover content" style="background-image: url('/assets/img/background/coffee-login.jpg'); ">
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-6 col-lg-5">
                    <div class="login-wrap p-4 p-md-5">
                        <div class="icon d-flex align-items-center justify-content-center p-2">
                            <img src="{{ asset('assets/img/lgo.cafe.png') }}" alt="Image" class="img-fluid">
                            <span class="fa fa-user-o"></span>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer py-3 bg-primary text-white-50">
        <div class="container text-center ">
            <small>Café Lima &copy; <script>document.write(new Date().getFullYear());</script></small>
        </div>
    </footer>
</div>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    function showAlert(type, title) {
        Toast.fire({
            icon: type,
            title: title
        })
    }
</script>
@if (session()->has('success'))
    <script>
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        })
    </script>
@endif

@if (session()->has('error'))
    <script>
        Toast.fire({
            icon: 'error',
            title: '{{ session('error') }}'
        })
    </script>
@endif

</body>
</html>
