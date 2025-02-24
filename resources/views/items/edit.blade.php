@extends('layouts.main')

@section('styles')
    <!-- <link rel="stylesheet" href="{{ asset('styles/index.css') }}"> -->
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h4>Editar item</h4>
                <div class="card mt-3">
                    <div class="card-header">Editar item</div>
                    <form class="card-body" action="{{ route('items.update', $item->id) }}" method="POST">
                    @method('PUT')
                    @csrf

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $item->nombre }}" required>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required>{{ $item->descripcion }}</textarea>
                        </div>

                        <!-- Precio -->
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio (euros)</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="{{ $item->precio }}" required>
                        </div>

                        <!-- Distribución -->
                        <div class="mb-3">
                            <label for="distribucion" class="form-label">Distribución</label>
                            <select class="form-select" id="distribucion" name="distribucion" required>
                                <option value="salón" {{ $item->distribucion == 'salón' ? 'selected' : '' }}>Salón</option>
                                <option value="dormitorio" {{ $item->distribucion == 'dormitorio' ? 'selected' : '' }}>Dormitorio</option>
                                <option value="cocina" {{ $item->distribucion == 'cocina' ? 'selected' : '' }}>Cocina</option>
                                <option value="baño" {{ $item->distribucion == 'baño' ? 'selected' : '' }}>Baño</option>
                                <option value="jardín" {{ $item->distribucion == 'jardín' ? 'selected' : '' }}>Jardín</option>
                            </select>
                        </div>

                        <!-- Material -->
                        <div class="mb-3">
                            <label for="material" class="form-label">Material</label>
                            <select class="form-select" id="material" name="material" required>
                                <option value="plástico" {{ $item->material == 'plástico' ? 'selected' : '' }}>Plástico</option>
                                <option value="metal" {{ $item->material == 'metal' ? 'selected' : '' }}>Metal</option>
                                <option value="madera" {{ $item->material == 'madera' ? 'selected' : '' }}>Madera</option>
                                <option value="vidrio" {{ $item->material == 'vidrio' ? 'selected' : '' }}>Vidrio</option>
                            </select>
                        </div>

                        <!-- Stock -->
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="{{ $item->stock }}" min="0" required>
                        </div>

                        <!-- Marca -->
                        <div class="mb-3">
                            <label for="id_brand" class="form-label">Marca</label>
                            <select class="form-select" id="id_brand" name="id_brand">
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $item->id_brand == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tags -->
                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            <div id="tags-container" class="row">
                                @foreach($tags as $tag)
                                    <div class="col-12 col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tag{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}"
                                            {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="tag{{ $tag->id }}">{{ $tag->nombre }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Botón para añadir nuevo tag -->
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-primary px-3 text-nowrap" id="add-tag-button">Añadir tag</button>
                                <input type="text" id="new-tag" class="form-control d-none w-100" placeholder="Escriba aquí el nuevo tag">
                                <div id="tag-error" class="text-danger mt-2 d-none"></div>
                            </div>
                        </div>

                        <!-- Botón de envío -->
                         <div class="d-flex justify-content-between gap-2">
                            <a href="{{ route('items.adminList') }}" class="btn btn-secondary w-50">Cancelar</a>
                            <button type="submit" class="btn btn-primary w-50">Guardar cambios</button>
                         </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('scripts/newTag.js') }}"></script>
@endpush