@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
@endsection

@section('content')
    <div class="header_image"></div>
    <div class="container mt-5">
        <div class="card_container">
            <a href="{{ route('items.quickTag', ['tag' => 'Dormitorio']) }}" class="dorm">DORMITORIO</a>
            <a href="{{ route('items.quickTag', ['tag' => 'Baño']) }}" class="bano">BAÑO</a>
            <a href="{{ route('items.quickTag', ['tag' => 'Jardín']) }}" class="jard">JARDÍN</a>
            <a href="{{ route('items.quickTag', ['tag' => 'Cocina']) }}" class="kit">COCINA</a>
            <a href="{{ route('items.quickTag', ['tag' => 'Salón']) }}" class="lv">SALÓN</a>
            <a href="{{ route('items.index') }}" class="otros">OTROS</a>
        </div>
    </div>
@endsection
