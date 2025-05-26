@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/adminList.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h4 class="search_title">Listado de items</h4>
        
        <!-- Barra de búsqueda -->
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Buscar...">
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width=5%">#</th>
                        <th style="width=15%">Imagen</th>
                        <th style="width=5%">Nombre</th>
                        <th style="width=15%">Descripción</th>
                        <th style="width=15%">Precio</th>
                        <th style="width=15%">Disponible</th>
                        <th style="width=30%">Acciones</th>
                    </tr>
                </thead>
                <tbody id="itemsTable">
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                <img src="{{ asset('images/' . $item->imagen) }}" width="50" height="50"
                                onerror="this.onerror=null; this.src='{{ asset('images/no_image.jpg') }}';"
                                >
                            </td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ Str::limit($item->descripcion, 50, '...') }}</td>
                            <td>{{ number_format($item->precio, 2) }} €</td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input disponibilidad-switch"
                                        type="checkbox"
                                        data-id="{{ $item->id }}"
                                        {{ $item->disponibilidad ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('items.edit', $item->id) }}" class="material-symbols-outlined text-decoration-none">edit_note</a>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @method('DELETE')
                                    @csrf
                                    <span type="submit" class="material-symbols-outlined text-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este item?');">
                                        delete
                                    </span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('scripts/adminList.js') }}"></script>
@endpush