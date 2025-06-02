@if($orders->isEmpty())
    <p class="text-muted">No hay pedidos.</p>
@else
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light sticky-top" style="top: 0; z-index: 1;">
                <tr>
                    <th>Fecha</th>
                    <th>Precio Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($order->fecha)->format('d/m/Y') }}</td>
                        <td>{{ number_format($order->precio_total, 2) }} €</td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ ucfirst(str_replace('_', ' ', $order->estatus)) }}
                            </span>
                        </td>
                        <td>
                            <!-- Botón para ver el pedido -->
                            <a href="{{ route('orders.show', $order->id) }}" class="material-symbols-outlined text-decoration-none text-primary">visibility</a>

                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <!-- Botón para actualizar el pedido -->
                                @if ($order->estatus === 'pendiente_envio' || $order->estatus === 'pendiente_recogida')
                                    <form action="{{ route('orders.update', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn p-0 border-0 bg-transparent text-success">
                                            <span class="material-symbols-outlined">check_circle</span>
                                        </button>
                                    </form>
                                @endif

                                <!-- Botón para eliminar el pedido -->
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn p-0 border-0 bg-transparent text-danger" onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido?');">
                                        <span class="material-symbols-outlined">cancel</span>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
