@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Sección izquierda: Tabla de productos -->
        <div class="col-md-7">
            <h4>Mi carrito de compra</h4>

            @if(count($items) > 0)
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    <img src="{{ asset($item->image ?? 'images/no_image.jpg') }}" 
                                         alt="{{ $item->name }}" 
                                         class="img-thumbnail"
                                         width="70">
                                </td>
                                <td><a href="{{ route('items.show', $item->id) }}">{{ $item->nombre }}</a></td>
                                <td>{{ number_format($item->precio, 2) }} €</td>
                                <td>{{ $item->cantidad }}</td>
                                <td>{{ number_format($item->cantidad * $item->precio, 2) }} €</td>
                                <td>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="material-symbols-outlined text-danger border-0 bg-transparent p-0">
                                            delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No tienes productos en el carrito.</p>
            @endif
        </div>

        <!-- Sección derecha: Resumen del pedido -->
        <div class="col-md-5">
            <form action="{{ route('orders.store') }}" method="POST" class="p-4 bg-light rounded shadow">
                <h4 class="mb-4"><strong>Resumen del pedido</strong></h4>
                <div class="d-flex justify-content-between">
                    <span>Productos:</span>
                    <span>{{ number_format($items->sum(fn($item) => $item->cantidad * $item->precio), 2) }} €</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Gastos de envío (IVA inc.)</span>
                    <span>Desconocido</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total:</span>
                    <span>{{ number_format($items->sum(fn($item) => $item->cantidad * $item->precio), 2) }} €</span>
                    <input id="precio_total" name="precio_total" type="hidden" value="{{ number_format($items->sum(fn($item) => $item->cantidad * $item->precio), 2) }}">
                </div>

                <button class="btn btn-secondary w-100 mt-3 p-2" data-bs-toggle="modal" data-bs-target="#addressModal">
                    Añadir dirección de envío
                </button>

                <!-- Modal -->
                <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Añade una dirección de envío</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addressForm" action="{{ route('cart.storeAddress') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="linea_1" class="form-label">Línea 1</label>
                                        <input type="text" class="form-control" id="linea_1" name="linea_1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="linea_2" class="form-label">Línea 2 (Opcional)</label>
                                        <input type="text" class="form-control" id="linea_2" name="linea_2">
                                    </div>
                                    <div class="mb-3">
                                        <label for="pais" class="form-label">País</label>
                                        <input type="text" class="form-control" id="pais" name="pais" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="provincia" class="form-label">Provincia</label>
                                        <input type="text" class="form-control" id="provincia" name="provincia" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ciudad" class="form-label">Ciudad</label>
                                        <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="codigo_postal" class="form-label">Código Postal</label>
                                        <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 mt-3 p-2 fw-bold">
                    Finalizar pedido
                </button>
            </form>
        </div>

        
    </div>
</div>
@endsection
