@extends('layouts.main')

@section('styles')
    <!-- <link rel="stylesheet" href="{{ asset('styles/index.css') }}"> -->
@endsection

@section('content')
    <div class="container mt-5">
        <form action="{{ route('items.store') }}" method="POST">
        @csrf <!-- Token de seguridad obligatorio en formularios de Laravel -->

        <!-- Nombre -->
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del item" required>
        </div>

        <!-- Descripción -->
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del item" required></textarea>
        </div>

        <!-- Precio -->
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" placeholder="Precio del item" required>
        </div>

        <!-- Distribución -->
        <div class="mb-3">
            <label for="distribucion" class="form-label">Distribución</label>
            <select class="form-select" id="distribucion" name="distribucion" required>
                <option value="" selected disabled>Selecciona una distribución</option>
                <option value="salón">Salón</option>
                <option value="dormitorio">Dormitorio</option>
                <option value="cocina">Cocina</option>
                <option value="baño">Baño</option>
                <option value="jardín">Jardín</option>
                <option value="otros">Otros</option>
            </select>
        </div>

        <!-- Material -->
        <div class="mb-3">
            <label for="material" class="form-label">Material</label>
            <select class="form-select" id="material" name="material" required>
                <option value="" selected disabled>Selecciona un material</option>
                <option value="plástico">Plástico</option>
                <option value="metal">Metal</option>
                <option value="madera">Madera</option>
                <option value="vidrio">Vidrio</option>
            </select>
        </div>

        <!-- Stock -->
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" placeholder="Cantidad en stock" required>
        </div>

        <!-- Marca -->
        <div class="mb-3">
            <label for="id_brand" class="form-label">Marca</label>
            <select class="form-select" id="id_brand" name="id_brand">
                <option value="" selected disabled>Selecciona una marca</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tags -->
        <div class="mb-3">
            <label class="form-label">Tags</label>
            <div class="d-flex flex-wrap">
                @foreach($tags as $tag)
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" id="tag{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}">
                        <label class="form-check-label" for="tag{{ $tag->id }}">{{ $tag->nombre }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Botón de envío -->
        <button type="submit" class="btn btn-primary">Crear Item</button>
    </form>
    </div>
@endsection
