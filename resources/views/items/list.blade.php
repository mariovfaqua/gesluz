@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/list.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h4>Resultados de la búsqueda</h4><hr>

        <!-- Filtrado -->
        @if(session('filters'))
            <div class="mb-3 d-flex gap-3">
                <strong>Filtros:</strong>

                <!-- Etiquetas de filtros -->
                <div id="activeFilters" class="d-flex align-items-center flex-wrap gap-2">
                    @foreach(session('filters') as $key => $value)
                        @php
                            // Nombre legible de cada categoría de filtro
                            $label = match ($key) {
                                'query' => 'Búsqueda',
                                'minValue' => 'Precio mínimo',
                                'maxValue' => 'Precio máximo',
                                'tipo' => 'Tipo',
                                'brand' => 'Marca',
                                'tags' => 'Tag',
                                default => ucfirst($key),
                            };
                        @endphp

                        @if($key === 'tags')
                            @foreach($value as $v)
                                @php
                                    $tag = $tags->firstWhere('id', $v);
                                    $text = $tag?->nombre ?? $v;
                                @endphp
                                <span class="badge bg-secondary">{{ $label }}: {{ $text }}</span>
                            @endforeach
                        @else
                            @php
                                $text = $value;

                                if ($key === 'brand') {
                                    $brand = $brands->firstWhere('id', $value);
                                    $text = $brand?->nombre ?? $value;
                                }
                            @endphp
                            <span class="badge bg-secondary">{{ $label }}: {{ $text }}</span>
                        @endif
                    @endforeach
                </div>

                <!-- Botón para editar filtros -->
                <button id="toggleFilters" type="button" class="btn btn-sm btn-outline-primary ms-2">Editar filtros</button>
            </div>

            <!-- Formulario de edición de filtros -->
            <form id="filterForm" class="d-none" action="{{ route('items.index') }}" method="GET">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">

                    <!-- Filtro de búsqueda por nombre (si hay filtro, lo muestra) -->
                    <div class="input-group input-group-sm w-auto">
                        <input type="text" name="form[query]" value="{{ session('filters.query') ?? '' }}" class="form-control" placeholder="Buscar..." {{ session('filters.query') ? 'value=' . session('filters.query') : '' }}>
                    </div>

                    <!-- Filtro de rango de precios -->
                    <div class="input-group input-group-sm w-auto">
                        <input type="number" step="0.01" name="form[minValue]" value="{{ session('filters.minValue') ?? '' }}" class="form-control" style="width: 80px;" placeholder="Mín €">
                        <input type="number" step="0.01" name="form[maxValue]" value="{{ session('filters.maxValue') ?? '' }}" class="form-control" style="width: 80px;" placeholder="Máx €">
                    </div>

                    <!-- Filtro de tipo -->
                    <select name="form[tipo]" class="form-select form-select-sm w-auto">
                        <option disabled selected value="Ninguno">Seleccione un tipo</option>
                        <option value="Ninguno">Ninguno</option>
                        @foreach(App\Models\Item::getTipos() as $tipo)
                            <option value="{{ $tipo }}" {{ session('filters.tipo') == $tipo ? 'selected' : '' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filtro de marca -->
                    <select name="form[brand]" class="form-select form-select-sm w-auto">
                        <option disabled selected value="Ninguno">Seleccione una marca</option>
                        <option value="Ninguno">Ninguna</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ session('filters.brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filtro de distribución -->
                    <select name="form[distribucion]" class="form-select form-select-sm w-auto">
                        <option disabled selected value="Ninguno">Seleccione una distribución</option>
                        <option value="Ninguno">Ninguna</option>
                        @foreach(App\Models\Item::getDistribucion() as $distribucion)
                            <option value="{{ $distribucion }}" {{ session('filters.distribucion') == $distribucion ? 'selected' : '' }}>
                                {{ $distribucion }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filtro de tags -->
                    <div class="accordion w-100 mt-1" id="tagsAccordion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" iid="headingTags">
                                <button class="accordion-button collapsed bg-transparent p-2 shadow-sm" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapseTags"
                                    aria-expanded="false" 
                                    aria-controls="collapseTags">
                                    
                                    Selección de tags
                                </button>
                            </h2>
                            <div id="collapseTags" class="accordion-collapse collapse" aria-labelledby="headingTags" data-bs-parent="#tagsAccordion">
                                <div class="accordion-body px-1 py-2">
                                    <input type="text" id="searchTags" class="form-control form-control-sm mb-3" placeholder="Buscar tags...">
                                    <div class="row" id="tagsContainer">
                                        @foreach($tags as $tag)
                                            <div class="col-md-4 tag-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="form[tags][]" id="tag_{{ $tag->id }}" value="{{ $tag->id }}"
                                                        {{ in_array($tag->id, session('filters.tags', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tag_{{ $tag->id }}">
                                                        {{ $tag->nombre }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <!-- Botón para quitar los filtros -->
                    <a href="{{ route('items.clearFilters')}}" class="btn btn-outline-danger btn-sm">
                        Quitar filtros
                    </a>
                    <!-- Botón para aplicar los filtros -->
                    <button class="btn btn-primary btn-sm" type="submit">
                        Aplicar cambios
                    </button>
                </div>
            </form>
        @endif

        <div class="card_container">
            @forelse($items as $item)
            <x-card :item="$item" />
            @empty
                <p>No se han encontrado productos.</p>
            @endforelse
        </div>

        <nav aria-label="Page navigation">
            {{ $items->links('pagination.bootstrap-5-custom') }}
        </nav>

        <!-- <div class="card_container">
            @foreach ($items as $item)
                <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none card card_custom">
                    <img src="..." 
                    class="card-img-top" 
                    onerror="this.onerror=null; this.src='{{ asset('images/no_image.jpg') }}';"
                    >

                    <div class="card-body">
                        <h5 class="card-title">{{ $item->precio }}€</h5>
                        <p class="card-text">{{ $item->nombre }}</p>
                        <p class="stock_text">
                            <span class="material-symbols-outlined {{ $item->stock > 0 ? '' : 'text-danger' }}">
                                {{ $item->stock > 0 ? 'check_circle' : 'cancel' }}
                            </span>

                            <span class="{{ $item->stock > 0 ? '' : 'text-danger' }}">{{ $item->stock > 0 ? 'En stock' : 'Agotado' }}</span>
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
        <nav aria-label="Page navigation">
            {{ $items->links('pagination.bootstrap-5-custom') }}
        </nav> -->
    </div>
@endsection

<script src="{{ asset('scripts/filter.js') }}"></script>

