@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/home.css') }}">
@endsection

@section('content')
<div class="container mt-3">
    <div class="card">
        <div class="card-header">{{ __('Dashboard') }}</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Opciones de administrador -->
            @if (Auth::user()->role === 'admin')
                <div class="admin-grid mt-4">
                    <!-- Lista de pedidos -->
                    <a href="{{ route('orders.index') }}">
                        <div class="admin-card p-3 border rounded">
                            Pedidos
                        </div>
                    </a>

                    <!-- Crear Item -->
                    <a href="{{ route('items.create') }}">
                        <div class="admin-card p-3 border rounded mb-3">
                            Crear Item
                        </div>
                    </a>

                    <!-- Editar/Eliminar Item -->
                    <a href="{{ route('items.adminList') }}">
                        <div class="admin-card p-3 border rounded">
                            Editar/Eliminar Item
                        </div>
                    </a>
                </div>
            @endif

            <!-- Opciones de usuario -->
            <div class="admin-grid mt-4">
                    <!-- Lista de pedidos -->
                    <a href="{{ route('addresses.index') }}">
                        <div class="admin-card p-3 border rounded">
                            Direcciones
                        </div>
                    </a>
                </div>

        </div>
    </div>
</div>
@endsection
