@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Datos de la cuenta -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Datos de la cuenta</h5>
            <a href="{{ route('account.edit') }}" class="btn btn-outline-primary btn-sm">Editar</a>
        </div>
        <div class="card-body">
            <p class="mb-1"><strong>Nombre:</strong> {{ Auth::user()->name }}</p>
            <p class="mb-1"><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p class="mb-0"><strong>Teléfono:</strong> {{ Auth::user()->phone }}</p>
        </div>
    </div>

    <!-- Administrar direcciones  -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Administrar direcciones</h5>
                <a class="btn btn-primary btn-sm" href="{{ route('addresses.create') }}">Añadir dirección</a>
            </div>
            <hr>

            @if($addresses->isEmpty())
                <p class="text-muted">No hay direcciones guardadas.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Destinatario</th>
                                <th>Detalles</th>
                                <th class="text-center">Primaria</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($addresses as $address)
                                <tr>
                                    <td>{{ $address->destinatario }}</td>
                                    <td>
                                        {{ $address->linea_1 }}<br>
                                        {{ $address->linea_2 ? $address->linea_2 . '<br>' : '' }}
                                        {{ $address->ciudad }}, {{ $address->provincia }}<br>
                                        {{ $address->codigo_postal }} {{ $address->pais }}
                                    </td>
                                    <td class="text-center">
                                        @if($address->primaria)
                                            <span class="text-primary material-symbols-outlined">check_circle</span>
                                        @else
                                            <a href="{{ route('addresses.primary', $address->id) }}">
                                                <span class="text-secondary opacity-75 material-symbols-outlined">check_circle</span>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('addresses.edit', $address) }}" class="material-symbols-outlined text-decoration-none">edit_note</a>
                                        <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <span type="submit" class="material-symbols-outlined text-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta dirección?');">
                                                delete
                                            </span>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal: añadir nueva dirección -->
    <!-- <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressModalLabel">Añadir nueva dirección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
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
                            <label for="linea_2" class="form-label">Línea 2 (opcional)</label>
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
    </div> -->
</div>
@endsection
