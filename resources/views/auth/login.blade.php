@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-white">
                    <h5 class="mb-0">Iniciar sesión</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Correo Electrónico -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Recuérdame -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" 
                                   name="remember" id="remember" 
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Recuérdame</label>
                        </div>

                        <!-- Botón de enviar -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>

                        <!-- Enlaces -->
                        <div class="text-center d-flex flex-column gap-2">
                            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                            <a href="{{ route('register') }}">¿No tienes cuenta? Haz click aquí</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection