@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Lista de pedidos</h4>

    {{-- Panel de pedidos pendientes --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <strong>Pedidos pendientes</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-1">Pendientes para cómputo de gastos de envío</h6>
                    <div class="table-wrapper border rounded overflow-auto" style="max-height: 300px;">
                        @include('orders.partials.table', ['orders' => $pendientes_email])
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-1">Pendientes de confirmación</h6>
                    <div class="table-wrapper border rounded overflow-auto" style="max-height: 300px;">
                        @include('orders.partials.table', ['orders' => $pendientes_confirmacion])
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-1">Pendientes de envío</h6>
                    <div class="table-wrapper border rounded overflow-auto" style="max-height: 300px;">
                        @include('orders.partials.table', ['orders' => $pendientes_envio])
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-1">Pendientes de recogida</h6>
                    <div class="table-wrapper border rounded overflow-auto" style="max-height: 300px;">
                        @include('orders.partials.table', ['orders' => $pendientes_recogida])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pedidos completados --}}
    <div class="accordion" id="orderAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="completedHeading">
                <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHeading">
                    Pedidos completados
                </button>
            </h2>
            <div id="collapseHeading" class="accordion-collapse collapse" data-bs-parent="#orderAccordion">
                <div class="accordion-body">
                    @include('orders.partials.table', ['orders' => $completados])
                    <nav aria-label="Page navigation" class="mt-3">
                        {{ $completados->links('pagination.bootstrap-5-custom') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
