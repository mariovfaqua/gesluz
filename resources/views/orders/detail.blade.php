@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h2>Detalles del Pedido #{{ $order->id }}</h2>
    <p><strong>Fecha:</strong> {{ $order->fecha }}</p>
    <p><strong>Total:</strong> {{ number_format($order->precio_total, 2) }} €</p>

    <h3>Dirección de Envío</h3>
    <p>{{ $order->address->nombre }}</p>
    <p>{{ $order->address->linea_1 }}, {{ $order->address->linea_2 }}</p>
    <p>{{ $order->address->ciudad }}, {{ $order->address->provincia }}, {{ $order->address->pais }}</p>
    <p><strong>Código Postal:</strong> {{ $order->address->codigo_postal }}</p>

    <h3>Productos del Pedido</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->nombre }}</td>
                <td>{{ number_format($item->precio, 2) }} €</td>
                <td>{{ $item->pivot->cantidad }}</td> <!-- Cantidad desde la tabla intermedia -->
                <td>{{ number_format($item->precio * $item->pivot->cantidad, 2) }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
