@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/index.css') }}">
@endsection

@section('content')
    <div class="header_image"></div>
    <div class="container mt-5">
        <div class="card_container">
            <a href="#" class="dorm">DORMITORIO</a>
            <a href="#" class="bano">BAÑO</a>
            <a href="#" class="jard">JARDÍN</a>
            <a href="#" class="kit">COCINA</a>
            <a href="#" class="lv">SALÓN</a>
            <a href="#" class="otros">OTROS</a>
        </div>
    </div>
@endsection
