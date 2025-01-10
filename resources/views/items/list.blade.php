@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/list.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="card_container">
            @foreach ($items as $item)
                <div class="card card_custom">
                    <img src="..." 
                    class="card-img-top" 
                    onerror="this.onerror=null; this.src='{{ asset('images/no_image.jpg') }}';">

                    <div class="card-body">
                        <h5 class="card-title">{{ $item->precio }}€</h5>
                        <p class="card-text">{{ $item->nombre }}</p>
                        <p class="stock_text">
                            <span class="material-symbols-outlined">
                                {{ $item->stock > 0 ? 'check_circle' : 'cancel' }}
                            </span>

                            <span>{{ $item->stock > 0 ? 'En stock' : 'Agotado' }}</span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- <table class="table table-striped">
            <thead class='bg-secondary text-white'>
                <tr>
                    <th>#</th>
                    <th>Artículo</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->descripcion }}</td>
                        <td>{{ $item->precio }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> -->
    </div>
@endsection