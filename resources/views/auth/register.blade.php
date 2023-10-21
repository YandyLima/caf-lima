@extends('auth.app')
@section('title', 'Registrarse')
@section('content')
    <h1 class="heading-section text-center">Regístrate</h1>
    <h3 class="text-center mb-4">Por favor ingresa tus datos.</h3>
    <form class="login-form" id="form-login" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control rounded-left"  id="name" name="name" value="{{ old('name') }}" placeholder="Nombre Completo" required autofocus>
        </div>
        <div class="form-group">
            <input type="email" class="form-control rounded-left"  id="email" name="email" value="{{ old('email') }}" placeholder="Correo Electrónico" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control rounded-left" minlength="8" id="password" name="password" value="{{ old('password') }}" placeholder="Contraseña" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control rounded-left" minlength="8" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Confirmar Contraseña" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control rounded-left"  id="address" name="address" value="{{ old('address') }}" placeholder="Dirección" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control rounded-left" maxlength="8"  id="phone" name="phone" value="{{ old('phone') }}" placeholder="Teléfono" required>
        </div><div class="form-group">
            <input type="number" class="form-control rounded-left" id="type" name="type" value="2" placeholder="Tipo"  required hidden="true">
        </div>
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary rounded submit mt-3 p-3 px-5">Crear Cuenta</button>
        </div>
    </form>
@endsection
