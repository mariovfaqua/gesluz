@extends('layouts.main')

@section('content')
<div class="container mt-5">

    <!-- Mensajes de éxito y error -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

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
        @php
            $precio_total= number_format($items->sum(fn($item) => $item->cantidad * $item->precio), 2);
        @endphp

        <div class="col-md-5">
            <div class="p-4 bg-light rounded shadow">
                <h4 class="mb-4"><strong>Resumen del pedido</strong></h4>
                <div class="d-flex justify-content-between">
                    <span>Productos:</span>
                    <span>{{ $precio_total }} €</span>
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

                @if(count($items) > 0)
                    @if(Auth::check())
                        @php
                            $user = Auth::user();
                            $address = session('address');
                        @endphp
                        <form action="{{ route('checkout') }}" method="GET">
                        <!-- <form action="{{ route('orders.store') }}" method="POST"> -->
                            @csrf
                            <div class="p-3 mt-3 border rounded bg-light">
                                <strong class="fw-bold">Datos de contacto</strong>
                                <p class="mb-1">{{ $user->name }}</p>
                                <p class="mb-1">{{ $user->email }}</p>
                                <p class="mb-1">{{ $user->phone }}</p>

                                @if(session('address'))
                                    <div id="shippingAddressSection">
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-start position-relative">
                                            <strong class="fw-bold">Dirección de envío</strong>

                                            <!-- <form action="{{ route('cart.clearAddress') }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                                @csrf
                                                <button type="submit" class="btn-close" aria-label="Eliminar dirección"></button>
                                            </form> -->
                                        </div>
                                        @if($address)
                                            <p class="mb-1">{{ $address['destinatario'] }}</p>
                                            <p class="mb-1">{{ $address['linea_1'] }}{{ $address['linea_2'] ? ', '.$address['linea_2'] : '' }}</p>
                                            <p class="mb-1">{{ $address['codigo_postal'] }} {{ $address['ciudad'] }}, {{ $address['provincia'] }}</p>
                                            <p class="mb-1">{{ $address['pais'] }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="form-check mt-4">
                                <input id="toggleAddress" name="send_home" class="form-check-input" type="checkbox"
                                    {{ session('success') ? 'checked' : '' }}
                                >
                                <strong class="form-check-label" for="toggleAddress">
                                    ¿Quieres que te enviemos el pedido a casa?
                                </strong>
                                <small>Indícanos tu dirección y nos pondremos en contacto contigo</small>
                            </div>

                            <!-- Botón para editar dirección -->
                            <button
                                id="editAddressBtn"
                                type="button"
                                class="btn w-100 btn-primary fw-bold mt-2 {{ !session('success') ? 'd-none' : '' }}"
                                data-bs-toggle="modal"
                                data-bs-target="#addressModal"
                            >
                                Editar dirección
                            </button>

                            <input id="precio_total" name="precio_total" type="hidden" value="{{ $precio_total }}">

                            <button id="submitBtn" type="submit" class="btn btn-warning w-100 mt-4 fw-bold">
                                Finalizar pedido
                            </button>
                        </form>

                    @else
                        <!-- Botón que lanza el modal de login -->
                        <button class="btn btn-primary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#loginModal">
                            Inicia sesión para continuar
                        </button>
                    @endif
                @endif

            <!-- Modal de datos de contacto -->
            @if(auth()->check()) 
                <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Seleccionar dirección</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>ç

                            <div class="modal-body">
                                <!-- Direcciones guardadas -->
                                <div class="accordion" id="savedAddressAccordion">
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header" id="headingSavedAddress">
                                            <button class="accordion-button bg-transparent p-2 shadow-none fw-bold" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapseSavedAddress" 
                                                aria-expanded="true" 
                                                aria-controls="collapseSavedAddress">
                                                Direcciones guardadas
                                            </button>
                                        </h2>
                                        <div id="collapseSavedAddress" class="accordion-collapse collapse show" aria-labelledby="collapseSavedAddress" data-bs-parent="#savedAddressAccordion">
                                            <div class="accordion-body px-1 py-2">
                                                <form id="savedAddressForm" action="{{ route('cart.storeAddress') }}" method="POST">
                                                    @csrf
                                                    <div class="list-group">
                                                        @foreach ($addresses as $saved_address)
                                                            <label class="list-group-item d-flex align-items-center">
                                                                <input class="form-check-input me-2" 
                                                                    type="radio"
                                                                    name="selected_address" 
                                                                    value="{{ $saved_address->id }}" 
                                                                    {{ session('address.id') == $saved_address->id ? 'checked' : '' }}
                                                                >
                                                                <div>
                                                                    <strong>{{ $saved_address->destinatario }}</strong><br>
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
                                                Añadir nueva dirección
                                            </button>
                                        </h2>
                                        <div id="collapseNewAddress" class="accordion-collapse collapse {{ !auth()->check() ? 'show' : '' }}" aria-labelledby="collapseNewAddress" data-bs-parent="#newAddressAccordion">
                                            <div class="accordion-body px-1 py-2">
                                                <!-- Formulario de nueva dirección -->
                                                <form id="newAddressForm" action="{{ route('cart.storeAddress') }}" method="POST">
                                                    @csrf
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
            @endif
        </div>

        
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleAddress = document.getElementById('toggleAddress');
        const addressSection = document.getElementById('shippingAddressSection');
        const editAddressBtn = document.getElementById('editAddressBtn');
        const submitBtn = document.getElementById('submitBtn');

        const addressIsEmpty = {{ empty(session('address')) ? 'true' : 'false' }};
        const isUserLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

        function handleAddressToggle(triggeredByUser = false) {
            if (toggleAddress.checked) {
                editAddressBtn.classList.remove('d-none');

                if (addressIsEmpty) {
                    submitBtn.disabled = true;
                    submitBtn.classList.remove('btn-warning');
                    submitBtn.classList.add('btn-secondary');
                    addressSection.classList.add('d-none');
                } else {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-warning');
                    addressSection.classList.remove('d-none');
                }
            } else {
                editAddressBtn.classList.add('d-none');
                submitBtn.disabled = !isUserLoggedIn;
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-warning');
                addressSection.classList.add('d-none');
                // if (triggeredByUser) {
                //     window.location.href = "{{ route('cart.clearAddress') }}";
                // }
            }
        }

        if (toggleAddress) {
            toggleAddress.addEventListener('change', () => handleAddressToggle(true));
            handleAddressToggle(); // Ejecutar al cargar para reflejar estado
        }
    });
</script>

