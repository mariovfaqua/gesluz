@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    @if (Auth::user()->role === 'admin') <!-- Comprobación del rol -->
                        <div class="admin-grid mt-4">
                            <!-- Crear Item -->
                            <a href="{{ route('items.create') }}">
                                <div class="admin-card p-3 border rounded mb-3">
                                    Crear Item
                                </div>
                            </a>

                            <!-- Editar/Eliminar Item -->
                            <a href="{{ route('items.index') }}">
                                <div class="admin-card p-3 border rounded">
                                    Editar/Eliminar Item
                                </div>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
