@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/list.css') }}">
@endsection

@section('content')
    <div class="container mt-5">
        <h4 class="search_title">Resultados de la búsqueda</h4>
        <div class="card_container">
            @foreach ($items as $item)
                <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none card card_custom">
                    <img src="..." 
                    class="card-img-top" 
                    onerror="this.onerror=null; this.src='{{ asset('images/no_image.jpg') }}';"
                    >

                    <div class="card-body">
                        <h5 class="card-title">{{ $item->precio }}€</h5>
                        <p class="card-text">{{ $item->nombre }}</p>
                        <p class="stock_text">
                            <span class="material-symbols-outlined {{ $item->stock > 0 ? '' : 'text-danger' }}">
                                {{ $item->stock > 0 ? 'check_circle' : 'cancel' }}
                            </span>

                            <span class="{{ $item->stock > 0 ? '' : 'text-danger' }}">{{ $item->stock > 0 ? 'En stock' : 'Agotado' }}</span>
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
        <nav aria-label="Page navigation">
            {{ $items->links('pagination.bootstrap-5-custom') }}
        </nav>
    </div>
@endsection