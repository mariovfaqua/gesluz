@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/admin.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="admin-grid">
            <!-- Crear Item -->
            <a href="{{ route('items.create') }}">
                <div class="admin-card">
                    Crear Item
                </div>
            </a>

            <!-- Editar/Eliminar Item -->
            <a href="{{ route('items.index') }}">
                <div class="admin-card">
                    Editar/Eliminar Item
                </div>
            </a>
        </div>
    </div>
@endsection
