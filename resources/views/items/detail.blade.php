@extends('layouts.main')

@section('styles')
    <!-- <link rel="stylesheet" href="{{ asset('styles/list.css') }}"> -->
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <!-- Imagen del producto -->
            <div class="col-md-6">
                <div id="carouselItemImages" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @if ($item->images->isNotEmpty()) 
                            @foreach ($item->images as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset($image->url) }}" 
                                        class="d-block w-100 img-fluid rounded shadow" 
                                        alt="{{ $item->nombre }}">
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <img src="{{ asset('images/no_image.jpg') }}" 
                                    class="d-block w-100 img-fluid rounded shadow" 
                                    alt="Imagen no disponible">
                            </div>
                        @endif
                    </div>

                    <!-- Controles del carrusel -->
                    @if ($item->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselItemImages" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselItemImages" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    @endif
                </div>
                
                <!-- Tags -->
                <div class="mt-3">
                    @foreach($item->tags as $tag)
                        <a href="{{ route('items.quickLink', ['type' => 'tag', 'value' => $tag->nombre]) }}" 
                        class="badge bg-dark text-white text-decoration-none">
                            {{ $tag->nombre }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Información del producto -->
            <div class="col-md-6 d-flex flex-column pt-4 pb-5">
                <h3 class="fw-bold">{{ $item->nombre }}</h3>
                <hr>

                <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                    <div class="d-flex align-items-center gap-4">
                        <!-- Precio -->
                        <h4 class="fw-bold text-dark">{{ number_format($item->precio, 2) }}€</h4>

                        <!-- Cantidad -->
                        <div class="d-flex gap-2 badge bg-dark text-white align-items-center">
                            <label for="cantidad" class="fw-bold">Cantidad: </label>
                            <input type="number" 
                                id="cantidad" 
                                name="cantidad" 
                                value="1" 
                                min="1" 
                                max="{{ $item->stock }}"  
                                class="form-control text-center"
                            >
                            <div id="error" class="text-danger mt-2"></div>
                        </div>
                    </div>

                    <!-- Agregar al carrito -->
                    <input type="hidden" name="id_item" value="{{ $item->id }}">
                    <button type="submit" class="mt-3 btn btn-dark w-100">Agregar al carrito</button>
                </form>


                <!-- Descripción -->
                <div class="mt-auto">
                    <strong>DESCRIPCIÓN</strong>
                    <p>{{ $item->descripcion }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection