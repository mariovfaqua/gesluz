@extends('layouts.main')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h4>Administrar direcciones</h4>
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
                            <a href="{{ route('addresses.show', $address->id) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('addresses.edit', $address->id) }}" class="btn btn-warning btn-sm">Editar</a>
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
</div>
@endsection
