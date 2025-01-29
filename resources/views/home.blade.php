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

            <!-- {{ __('You are logged in!') }} -->

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


            <!-- Comprobación del rol -->
            @if (Auth::user()->role === 'admin')
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
@endsection
