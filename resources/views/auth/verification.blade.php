@extends('auth.app')
@section('title', 'Verificación de Cuenta')
@section('content')
    <h1 class="heading-section text-center">Verificación de Cuenta</h1>
    <h3 class="text-center mb-4">Ingresa el código de verificación que hemos enviado a tu correo electrónico.</h3>

{{--    <!-- Mostrar mensaje de error si existe -->--}}
{{--    @if($errors->has('verification_error'))--}}
{{--        <div class="alert alert-danger">--}}
{{--            {{ $errors->first('verification_error') }}--}}
{{--        </div>--}}
{{--    @endif--}}

    <form class="login-form" id="form-verification" method="POST" action="{{ route('verify.code') }}">
        @csrf
        <div class="form-group">
            <input type="email" class="form-control rounded-left" id="email" name="email" value="{{ old('email') }}" placeholder="Correo Electrónico" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control rounded-left" id="verification_code" name="verification_code" placeholder="Código de Verificación" required>
        </div>
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary rounded submit mt-3 p-3 px-5">Verificar Cuenta</button>
        </div>
    </form>
    <p class="text-center">Después de verificar tu cuenta, podrás iniciar sesión.</p>
@endsection
