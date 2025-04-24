@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
@endsection

@section('content')
    <div class="header_image"></div>
    <div class="container mt-3">
        <div class="card_container">
            <a href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'Dormitorio']) }}" class="dorm">DORMITORIO</a>
            <a href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'Baño']) }}" class="bano">BAÑO</a>
            <a href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'Jardín']) }}" class="jard">JARDÍN</a>
            <a href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'Cocina']) }}" class="kit">COCINA</a>
            <a href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'Salón']) }}" class="lv">SALÓN</a>
            <a href="{{ route('items.quickLink', ['type' => 'tipo', 'value' => 'Repuesto']) }}" class="otros">REPUESTOS Y BOMBILLAS</a>
        </div>
        <div class="bg-dark text-light mt-3 consignas">
            <div>
                <span class="material-symbols-outlined consignas_icon">local_shipping</span>
                <p>Envío seguro en un tiempo reducido</p>
            </div>
            <div>
                <span class="material-symbols-outlined consignas_icon">headset_mic</span>
                <p>Atención al cliente personalizada</p>
            </div>
            <div>
                <span class="material-symbols-outlined consignas_icon">credit_score</span>
                <p>Pago rápido y seguro de manera virtual</p>
            </div>
        </div>
    </div>
@endsection
