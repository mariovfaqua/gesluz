@extends('layouts.main')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h4>Lista de pedidos</h2>
    <hr>

    @if($pendientes->isEmpty())
        <p>No hay pedidos pendientes.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Precio Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendientes as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->fecha }}</td>
                        <td>{{ number_format($order->precio_total, 2) }} €</td>
                        <td>
                            <span class="badge bg-warning">Pendiente</span>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">Ver</a>
                            <form action="{{ route('orders.update', $order) }}" method="POST" class="d-inline">
                                @method('PUT')
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Completar</button>
                            </form>
                            <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido?');">Cancelar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="accordion" id="orderAccordion">
        <div class="accordion-item border-1">
            <h2 class="accordion-header" id="completedHeading">
                <button class="accordion-button collapsed bg-transparent p-2 shadow-none fw-bold" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseHeading" 
                    aria-expanded="false" 
                    aria-controls="collapseHeading">
                    Pedidos completados
                </button>
            </h2>
            <div id="collapseHeading" class="accordion-collapse collapse" aria-labelledby="collapseHeading" data-bs-parent="#orderAccordion">
                <div class="accordion-body px-1 py-2">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Precio Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($completados as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->fecha }}</td>
                                    <td>{{ number_format($order->precio_total, 2) }} €</td>
                                    <td>
                                        <span class="badge bg-secondary">Completado</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation">
                        {{ $completados->links('pagination.bootstrap-5-custom') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
