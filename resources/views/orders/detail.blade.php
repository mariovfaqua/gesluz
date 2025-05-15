@extends('layouts.main')

@section('content')
<div class="container mt-4">

    <div class="row mb-3">
        <div class="col-md-7 d-flex flex-column">
            <div class="d-flex flex-wrap align-items-center">
                <h4 class="me-2">Detalles del Pedido #{{ $order->id }}</h4>
                <span class="badge {{ $order->estatus ? 'bg-success' : 'bg-warning' }}">
                    {{ $order->estatus ? 'Completado' : 'Pendiente' }}
                </span>
            </div>

            <div class="mb-3">
                <strong>Fecha:</strong> {{ $order->fecha }}<br>
                <strong>Total:</strong> {{ $order->precio_total }}<br>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-info btn-sm w-25">Volver</a>
        </div>

        @if($address)
            <div class="col-md-5 p-3 border rounded">
                <!-- <strong class="fw-bold">Datos de contacto</strong>
                <p class="mb-1"> $user->name </p>
                <p class="mb-1"> $user->email </p>
                <p class="mb-1"> $user->phone </p>

                
                    <div id="shippingAddressSection">
                        <hr> -->
                        <div class="d-flex justify-content-between align-items-start position-relative">
                            <strong class="fw-bold">Dirección de envío</strong>
                        </div>
                        @if($address)
                            <p class="mb-1">{{ $address['destinatario'] }}</p>
                            <p class="mb-1">{{ $address['linea_1'] }}{{ $address['linea_2'] ? ', '.$address['linea_2'] : '' }}</p>
                            <p class="mb-1">{{ $address['codigo_postal'] }} {{ $address['ciudad'] }}, {{ $address['provincia'] }}</p>
                            <p class="mb-1">{{ $address['pais'] }}</p>
                        @endif
                    <!-- </div> -->
            </div>
        @endif
    </div>

    <h5>Artículos en el pedido</h5>
    <hr>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->nombre }}</td>
                <td>{{ number_format($item->precio, 2) }} €</td>
                <td>{{ $item->cantidad }}</td> 
                <td>{{ number_format($item->precio * $item->cantidad, 2) }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
