@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Sección izquierda: Tabla de productos -->
        <div class="col-md-7">

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

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
            <div class="p-4 bg-light rounded shadow">
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
                </div>

                @php
                    $address = session('address', []);
                @endphp

                @if(count($items) > 0)
                    @if(!$address)
                    <button class="btn btn-secondary w-100 mt-3 p-2" data-bs-toggle="modal" data-bs-target="#addressModal">
                        Añadir datos de contacto
                    </button>
                    @else
                        <!-- Mostrar resumen de la dirección -->
                        <div class="p-3 mt-3 border rounded bg-light position-relative">
                            <strong class="fw-bold">Datos de contacto</strong>
                            <p class="mb-1">{{ $address['nombre'] }}</p>

                            <p class="mb-1">{{ $address['email'] }}</p>
                            <p class="mb-1">{{ $address['telefono'] }}</p>

                            @if(isset($address['linea_1']))
                                <p class="mb-1">{{ $address['destinatario'] }}</p>
                                <p class="mb-1"> {{ $address['linea_1'] }}{{ $address['linea_2'] ? ', ' . $address['linea_2'] : '' }}</p>
                                <p class="mb-1"> {{ $address['ciudad'] }}, {{ $address['provincia'] }} {{ $address['pais'] }} {{ $address['codigo_postal'] }}</p>
                            @endif

                            <!-- Botón para editar la dirección -->
                            <button class="btn btn-secondary w-100 mt-2" data-bs-toggle="modal" data-bs-target="#addressModal">
                                Editar dirección
                            </button>

                            <!-- Botón para borrar la sesión -->
                            <form action="{{ route('cart.clearAddress') }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-none border-0 bg-transparent" title="Eliminar dirección">
                                    <span class="btn-close"></span>
                                </button>
                            </form>
                        </div>

                        <!-- Botón para finalizar pedido activado -->
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <input id="precio_total" name="precio_total" type="hidden" value="{{ number_format($items->sum(fn($item) => $item->cantidad * $item->precio), 2) }}">
                            <button type="submit" class="btn btn-warning w-100 mt-3 p-2 fw-bold">
                                Finalizar pedido
                            </button>
                        </form>
                    @endif
                @endif
            </div>

            <!-- Modal de datos de contacto -->
            <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Complete los datos del pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Direcciones guardadas -->
                            @if(auth()->check()) 
                                <div class="accordion" id="savedAddressAccordion">
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header" id="headingSavedAddress">
                                            <button class="accordion-button bg-transparent p-2 shadow-none fw-bold" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapseSavedAddress" 
                                                aria-expanded="true" 
                                                aria-controls="collapseSavedAddress">
                                                Datos almacenados
                                            </button>
                                        </h2>
                                        <div id="collapseSavedAddress" class="accordion-collapse collapse show" aria-labelledby="collapseSavedAddress" data-bs-parent="#savedAddressAccordion">
                                            <div class="accordion-body px-1 py-2">
                                                <form id="savedAddressForm" action="{{ route('cart.storeAddress') }}" method="POST">
                                                    @csrf
                                                    <div class="list-group">
                                                        @foreach ($addresses as $saved_address)
                                                            <label class="list-group-item d-flex align-items-start">
                                                                <input class="form-check-input me-2" 
                                                                    type="radio"
                                                                    name="selected_address" 
                                                                    value="{{ $saved_address->id }}" 
                                                                    {{ session('address.id') == $saved_address->id ? 'checked' : '' }}
                                                                >
                                                                <div>
                                                                    <strong>{{ $saved_address->nombre }}</strong><br>
                                                                    {{ $saved_address->linea_1 }} {{ $saved_address->linea_2 }}<br>
                                                                    {{ $saved_address->ciudad }}, {{ $saved_address->provincia }} ({{ $saved_address->codigo_postal }})<br>
                                                                    {{ $saved_address->pais }}
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    <button type="submit" class="btn btn-primary w-100 mt-3">Confirmar selección</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Añadir nueva dirección -->
                            <div class="accordion" id="newAddressAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingNewAddress">
                                        <button class="accordion-button {{ !auth()->check() ? '' : 'collapsed' }} bg-transparent p-2 shadow-none fw-bold" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapseNewAddress" 
                                            aria-expanded="false" 
                                            aria-controls="collapseNewAddress">
                                            Añadir nuevos datos de contacto
                                        </button>
                                    </h2>
                                    <div id="collapseNewAddress" class="accordion-collapse collapse {{ !auth()->check() ? 'show' : '' }}" aria-labelledby="collapseNewAddress" data-bs-parent="#newAddressAccordion">
                                        <div class="accordion-body px-1 py-2">
                                            <!-- Formulario de nueva dirección -->
                                            <form id="newAddressForm" action="{{ route('cart.storeAddress') }}" method="POST">
                                                @csrf
                                                <!-- Nombre -->
                                                <div class="mb-3">
                                                    <label for="nombre" class="form-label">Nombre de contacto</label>
                                                    <input
                                                        class="form-control" 
                                                        id="nombre" 
                                                        name="nombre"
                                                        value="{{ $address['nombre'] ?? '' }}"
                                                        required
                                                    >
                                                </div>

                                                <!-- Email -->
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Correo electrónico</label>
                                                    <input 
                                                        type="email" 
                                                        class="form-control" 
                                                        id="email" 
                                                        name="email"
                                                        value="{{ $address['email'] ?? '' }}"
                                                        required
                                                    >
                                                </div>

                                                <!-- Teléfono -->
                                                <div class="mb-3">
                                                    <label for="telefono" class="form-label">Teléfono</label>
                                                    <input 
                                                        type="tel" 
                                                        class="form-control" 
                                                        id="telefono" 
                                                        name="telefono" 
                                                        value="{{ $address['telefono'] ?? '' }}"
                                                        required
                                                    >
                                                </div>

                                                <!-- Checkbox para mostrar/ocultar la sección de dirección -->
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="toggleAddress" name="send_home">
                                                    <label class="form-check-label" for="toggleAddress">
                                                        <strong>¿Quieres que te enviemos el pedido a casa?</strong> Indícanos tu dirección y nos pondremos en contacto contigo
                                                    </label>
                                                </div>

                                                <!-- Contenedor colapsable con todos los campos de dirección -->
                                                <div id="addressFields" class="collapse">
                                                    <!-- Sección de dirección -->
                                                    <div class="mb-3">
                                                        <label for="destinatario" class="form-label">Nombre destinatario</label>
                                                        <input 
                                                            type="text" 
                                                            class="form-control" 
                                                            id="destinatario" 
                                                            name="destinatario"
                                                            value="{{ $address['destinatario'] ?? '' }}"
                                                        >
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="linea_1" class="form-label">Línea 1</label>
                                                        <input 
                                                            type="text" 
                                                            class="form-control" 
                                                            id="linea_1" 
                                                            name="linea_1" 
                                                            placeholder="Escribe la dirección" 
                                                            value="{{ $address['linea_1'] ?? '' }}"
                                                        >
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="linea_2" class="form-label">Línea 2 (Opcional)</label>
                                                        <input 
                                                            type="text" 
                                                            class="form-control" 
                                                            id="linea_2" 
                                                            name="linea_2" 
                                                            placeholder="Información adicional de dirección" 
                                                            value="{{ $address['linea_2'] ?? '' }}"
                                                        >
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="pais" class="form-label">País</label>
                                                        <input 
                                                            type="text" 
                                                            class="form-control" 
                                                            id="pais" 
                                                            name="pais" 
                                                            value="{{ $address['pais'] ?? '' }}"
                                                        >
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="provincia" class="form-label">Provincia</label>
                                                        <input 
                                                            type="text" 
                                                            class="form-control" 
                                                            id="provincia" 
                                                            name="provincia" 
                                                            value="{{ $address['provincia'] ?? '' }}"
                                                        >
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="ciudad" class="form-label">Ciudad</label>
                                                        <input 
                                                            type="text" 
                                                            class="form-control" 
                                                            id="ciudad" 
                                                            name="ciudad" 
                                                            value="{{ $address['ciudad'] ?? '' }}"
                                                        >
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="codigo_postal" class="form-label">Código Postal</label>
                                                        <input 
                                                            type="text" 
                                                            class="form-control" 
                                                            id="codigo_postal" 
                                                            name="codigo_postal" 
                                                            value="{{ $address['codigo_postal'] ?? '' }}"
                                                        >
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100 mt-2">
                                                    Guardar cambios
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>
@endsection

<script src="{{ asset('scripts/toggleDirection.js') }}"></script>
