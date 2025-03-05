@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Editar dirección</div>
                        <form class="card-body" action="{{ route('addresses.update', $address->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $address->nombre }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="linea_1" class="form-label">Línea 1</label>
                                <input type="text" class="form-control" id="linea_1" name="linea_1" value="{{ $address->linea_1 }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="linea_2" class="form-label">Línea 2 (Opcional)</label>
                                <input type="text" class="form-control" id="linea_2" name="linea_2" value="{{ $address->linea_2 }}">
                            </div>
                            <div class="mb-3">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="pais" value="{{ $address->pais }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="provincia" class="form-label">Provincia</label>
                                <input type="text" class="form-control" id="provincia" name="provincia" value="{{ $address->provincia }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" value="{{ $address->ciudad }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="codigo_postal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="{{ $address->codigo_postal }}" required>
                            </div>

                            <!-- Botón de envío -->
                            <div class="d-flex justify-content-between gap-2">
                                <a href="{{ route('addresses.index') }}" class="btn btn-secondary w-50">Cancelar</a>
                                <button type="submit" class="btn btn-primary w-50">Guardar cambios</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection