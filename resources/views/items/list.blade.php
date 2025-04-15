@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/list.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h4 class="search_title">Resultados de la búsqueda</h4>

        <!-- Etiquetas de filtros -->
        <div id="activeFilters" class="mb-3">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <strong>Filtros:</strong>

                @if(session('filters'))
                    @foreach(session('filters') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <span class="badge bg-secondary">{{ is_numeric($v) ? ($tags->firstWhere('id', $v)?->nombre ?? $v) : $v }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-secondary">{{ $value }}</span>
                        @endif
                    @endforeach
                @endif

                <!-- Botón para editar filtros -->
                <button type="button" class="btn btn-sm btn-outline-primary ms-2" id="toggleFilters">Editar filtros</button>
            </div>
        </div>

        <!-- Formulario de edición de filtros -->
        <form id="filterForm" action="{{ route('items.index') }}" method="GET" style="display: none;">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2 mb-md-0">

                    <strong class="me-2">Filtros aplicados:</strong>

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
                        <option selected>Ninguno</option>
                        @foreach(App\Models\Item::getTipos() as $tipo)
                            <option>{{ $tipo }}</option>
                        @endforeach
                    </select>

                    <!-- Filtro de marca -->
                    <select name="form[brand]" class="form-select form-select-sm w-auto">
                        <option value="Ninguno">Marca</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ session('filters.brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filtro de tags -->
                    <select name="form[tags][]" multiple class="form-select form-select-sm w-auto" style="min-width: 120px;">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, session('filters.tags', [])) ? 'selected' : '' }}>
                                {{ $tag->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <!-- Botón para quitar los filtros -->
                    <a href="{{ route('items.index') }}" class="btn btn-outline-danger btn-sm">
                        Quitar filtros
                    </a>
                    <!-- Botón para aplicar los filtros -->
                    <button class="btn btn-primary btn-sm" type="submit">
                        Aplicar cambios
                    </button>
                </div>
            </div>
        </form>

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

<script>
    const toggleButton = document.getElementById('toggleFilters');
    const filterForm = document.getElementById('filterForm');
    const activeFilters = document.getElementById('activeFilters');

    toggleButton?.addEventListener('click', () => {
        const isFormVisible = filterForm.style.display === 'block';
        filterForm.style.display = isFormVisible ? 'none' : 'block';
        activeFilters.style.display = isFormVisible ? 'flex' : 'none';
    });
</script>


<script>
    const filterForm = document.getElementById('filterForm');

    filterForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar que se envíe el formulario de forma tradicional

        // Recoger los datos del formulario
        const formData = new FormData(filterForm);

        // Enviar la solicitud AJAX
        fetch('{{ route('items.index') }}', {
            method: 'GET',
            body: formData
        })
        .then(response => response.text()) // Esperar la respuesta como texto
        .then(data => {
            // Actualizar el contenido de los ítems en la página
            document.getElementById('itemsContainer').innerHTML = data;
        })
        .catch(error => {
            console.error('Error al actualizar los resultados:', error);
        });
    });
</script>

