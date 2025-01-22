<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Gesluz')</title>

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS de Google Icons & Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&family=Lexend+Zetta:wght@100..900&display=swap" rel="stylesheet" />
    
    <!-- Estilos adicionales -->
    <!-- <link rel="stylesheet" href="/resources/css/app.css"> -->
    <link rel="stylesheet" href="{{ asset('styles/mainLayout.css') }}">
    @yield('styles')
</head>
<body>
    <!-- Encabezado / Navbar -->
    <header>
        <!-- Sidenav -->
        <div id="sidenav" class="offcanvas offcanvas-start bg-light" tabindex="-1" aria-labelledby="sidenavLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title " id="sidenavLabel">Buscar productos</h5>
                <div class="toggle-container" data-bs-dismiss="offcanvas" aria-label="Close">
                    <span class="btn-close sidebar-close-icon"></span>
                </div>
            </div>
            <form action="{{ route('items.index') }}" method="GET" class="offcanvas-body">
                <!-- Barra de búsqueda -->
                <div class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Nombre del producto" name="form[query]">
                        <button type="submit" class="btn btn-outline-secondary" type="button">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="filtros">
                    <!-- Rango de precios -->
                    <div class="accordion" id="priceAccordion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed bg-transparent p-2 shadow-none fw-bold" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapseOne" 
                                    aria-expanded="false" 
                                    aria-controls="collapseOne">
                                    
                                    Rango de precios
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#priceAccordion">
                                <div class="accordion-body px-0 py-2">
                                    <div class="d-flex justify-content-between p-1">
                                        <input type="number" class="form-control w-45" placeholder="0,00€" name="form[minValue]">
                                        <input type="number" class="form-control w-45" placeholder="1500,00€" name="form[maxValue]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Material -->
                    <div class="accordion" id="materialAccordion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed bg-transparent p-2 shadow-none fw-bold" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapseTwo" 
                                    aria-expanded="false" 
                                    aria-controls="collapseTwo">
                                    
                                    Material
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#materialAccordion">
                                <div class="accordion-body px-1 py-2">
                                    <select class="form-select" id="form[material]" name="form[material]">
                                        <option selected>Ninguno</option>
                                        <option>Plástico</option>
                                        <option>Metal</option>
                                        <option>Madera</option>
                                        <option>Vidrio</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Marca -->
                    <div class="accordion" id="brandAccordion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed bg-transparent p-2 shadow-none fw-bold" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapseThree" 
                                    aria-expanded="false" 
                                    aria-controls="collapseThree">
                                    
                                    Marca
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#brandAccordion">
                                <div class="accordion-body px-1 py-2">
                                    <select class="form-select" id="form[brand]" name="form[brand]">
                                        <option selected>Ninguno</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="accordion" id="tagAccordion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed bg-transparent p-2 shadow-none fw-bold" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapseThree" 
                                    aria-expanded="false" 
                                    aria-controls="collapseThree">
                                    Tags
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#tagAccordion">
                                <div class="accordion-body px-1 py-2">
                                    <!-- Checkbox list -->
                                    @foreach($tags as $tag)
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                type="checkbox" 
                                                id="form[tags][{{ $tag->id }}]" 
                                                name="form[tags][]" 
                                                value="{{ $tag->id }}">
                                            <label class="form-check-label" for="form[tags][{{ $tag->id }}]">
                                                {{ $tag->nombre }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Color -->
                    <!-- <div class="accordion" id="colorAccordion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed bg-transparent p-2 shadow-none fw-bold" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapseFour" 
                                    aria-expanded="false" 
                                    aria-controls="collapseFour">
                                    
                                    Color
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#colorAccordion">
                                <div class="accordion-body px-1 py-2">
                                    <select class="form-select" id="form[color]" name="form[color]">
                                        <option selected>Ninguno</option>
                                        <option value="1">Opción 1</option>
                                        <option value="2">Opción 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    
                </div>
            </form>
        </div>

        <!-- Navbar -->
        <nav class="navbar">
            <div class="toggle-container" data-bs-toggle="offcanvas" data-bs-target="#sidenav" aria-controls="sidenav">
                <span class="navbar-toggler-icon"></span>
            </div>
            <div>
                <a class="title" href="{{ route('inicio') }}">GESLUZ</a>
            </div>
            <div class="toggle-container">
                <span data-bs-toggle="modal" data-bs-target="#loginModal">Login</span>
                <a class="material-symbols-outlined icon" href="{{ route('admin.dashboard') }}">account_circle</a>
                <span class="material-symbols-outlined icon">shopping_cart</span>
            </div>
        </nav>

        <!-- Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">iniciar sesión</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enlaces de acceso rápido -->
        <ul class="nav justify-content-center bg-dark">
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Interior</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Exterior</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Tipos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Estilos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Distribución</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Marcas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Ofertas</a>
            </li>
        </ul>
    </header>

    <!-- Contenido dinámico -->
    <main class="container-fluid">
        @yield('content')
    </main>

    <!-- Pie de página -->
    <footer class="bg-dark text-center py-3">
        <!-- <p>&copy; {{ date('Y') }} Gesluz </p> -->
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts adicionales -->
    @stack('scripts')
</body>
</html>