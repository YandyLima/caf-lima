@extends('auth.app')
@section('title', 'Iniciar Sesión')
@section('content')
    <h1 class="heading-section text-center">Inicio de Sesión</h1>
    <h3 class="text-center mb-4">Bienvenido, por favor ingresa tus credenciales.</h3>
    <form class="login-form" id="form-login" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control rounded-left"  id="email" name="email" value="{{ old('email') }}" placeholder="Correo Electrónico" required autofocus>
        </div>
        <div class="form-group d-flex">
            <input type="password" class="form-control rounded-left" id="password" name="password" value="{{ old('password') }}" placeholder="Contraseña" required>
        </div>

        @if (Route::has('password.request'))
            <div class="form-group">
                <div class="text-right">
                    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                </div>
            </div>
        @endif
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary rounded submit mt-3 p-3 px-5">Iniciar Sesión</button>
        </div>
        <div class="form-group">
            <div class="text-center">
                <a href="{{ route('register.index') }}">¿No tienes una cuenta?</a>
            </div>
        </div>
    </form>
@endsection
