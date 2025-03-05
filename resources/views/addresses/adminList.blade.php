@extends('layouts.main')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex gap-3 align-items-center">
        <h4>Administrar direcciones</h4>
        <a class="btn btn-primary" href="{{ route('addresses.create') }}">Añadir dirección</a>
        <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addressModal">
            Añadir dirección
        </button> -->
    </div>
    <hr>

    @if($addresses->isEmpty())
        <p>No hay direcciones guardadas.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Detalles</th>
                    <th>Primaria</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($addresses as $address)
                    <tr>
                        <td>{{ $address->nombre }}</td>
                        <td>
                            {{ $address->linea_1 }}<br>
                            {{ $address->linea_2 }}<br>
                            {{ $address->ciudad . ", " . $address->provincia }}<br>
                            {{ $address->codigo_postal }}
                            {{ $address->pais }}
                        </td>
                        <td>
                            @if($address->primaria)
                                <span class="material-symbols-outlined text-primary">
                                    check_circle
                                </span>
                            @else
                                <a href="{{ route('addresses.primary', $address->id) }}">
                                    <span class="material-symbols-outlined text-secondary">
                                        check_circle
                                    </span>
                                </a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('addresses.edit', $address) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="d-inline">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta dirección?');">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Modal de dirección -->
    <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir nueva dirección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de nueva dirección -->
                    <form id="newAddressForm" action="{{ route('addresses.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="linea_1" class="form-label">Línea 1</label>
                            <input type="text" class="form-control" id="linea_1" name="linea_1" required>
                        </div>
                        <div class="mb-3">
                            <label for="linea_2" class="form-label">Línea 2 (Opcional)</label>
                            <input type="text" class="form-control" id="linea_2" name="linea_2">
                        </div>
                        <div class="mb-3">
                            <label for="pais" class="form-label">País</label>
                            <input type="text" class="form-control" id="pais" name="pais" required>
                        </div>
                        <div class="mb-3">
                            <label for="provincia" class="form-label">Provincia</label>
                            <input type="text" class="form-control" id="provincia" name="provincia" required>
                        </div>
                        <div class="mb-3">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                        </div>
                        <div class="mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
