@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/list.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h4 class="search_title">Resultados de la búsqueda</h4>

        <form action="{{ route('items.index') }}" method="GET" class="bg-light border rounded p-3 mb-4 shadow-sm">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2 mb-md-0">

                    <strong class="me-2">Filtros aplicados:</strong>

                    @if(session('filters.query'))
                        <div class="input-group input-group-sm w-auto">
                            <input type="text" name="form[query]" value="{{ session('filters.query') }}" class="form-control" placeholder="Buscar...">
                        </div>
                    @endif

                    @if(session('filters.minValue') || session('filters.maxValue'))
                        <div class="input-group input-group-sm w-auto">
                            <input type="number" step="0.01" name="form[minValue]" value="{{ session('filters.minValue') }}" class="form-control" style="width: 80px;" placeholder="Mín €">
                            <input type="number" step="0.01" name="form[maxValue]" value="{{ session('filters.maxValue') }}" class="form-control" style="width: 80px;" placeholder="Máx €">
                        </div>
                    @endif

                    @if(session('filters.material') && session('filters.material') !== 'Ninguno')
                        <select name="form[material]" class="form-select form-select-sm w-auto">
                            <option value="Ninguno">Material</option>
                            <option {{ session('filters.material') === 'Plástico' ? 'selected' : '' }}>Plástico</option>
                            <option {{ session('filters.material') === 'Metal' ? 'selected' : '' }}>Metal</option>
                            <option {{ session('filters.material') === 'Madera' ? 'selected' : '' }}>Madera</option>
                            <option {{ session('filters.material') === 'Vidrio' ? 'selected' : '' }}>Vidrio</option>
                        </select>
                    @endif

                    @if(session('filters.brand') && session('filters.brand') !== 'Ninguno')
                        <select name="form[brand]" class="form-select form-select-sm w-auto">
                            <option value="Ninguno">Marca</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ session('filters.brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->nombre }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    @if(session('filters.tags'))
                        <select name="form[tags][]" multiple class="form-select form-select-sm w-auto" style="min-width: 120px;">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, session('filters.tags')) ? 'selected' : '' }}>
                                    {{ $tag->nombre }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('items.index') }}" class="btn btn-outline-danger btn-sm">
                        Quitar filtros
                    </a>
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
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const resultados = document.getElementById("resultados");

        function getFormParams(form) {
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (const [key, value] of formData.entries()) {
                if (value !== "") {
                    params.append(key, value);
                }
            }

            return params.toString();
        }

        form.querySelectorAll("input, select").forEach(input => {
            input.addEventListener("change", () => {
                const query = getFormParams(form);

                fetch("{{ route('items.index') }}?" + query, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => res.text())
                .then(html => {
                    resultados.innerHTML = html;
                    window.history.pushState({}, "", `?${query}`);
                });
            });
        });
    });
</script>
