@extends('layouts.main')

@section('content')
<div class="container mt-3">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card w-100" style="max-width: 500px;">
            <div class="card-header">
                <h5>Editar datos de la cuenta</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('account.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name', Auth::user()->name) }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" 
                               name="phone" value="{{ old('phone', Auth::user()->phone) }}" required>
                        @error('phone')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Contraseña para confirmar -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label text-warning">Confirmar contraseña</label>
                        <input id="current_password" type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               name="current_password" required autocomplete="current-password">
                        @error('current_password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Confirmar -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
