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
                    <span class="btn-close sidebar-close-icon toggle-content"></span>
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
                                    data-bs-target="#collapseFour" 
                                    aria-expanded="false" 
                                    aria-controls="collapseFour">
                                    Tags
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#tagAccordion">
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
                <span class="navbar-toggler-icon toggle-content"></span>
            </div>
            <a class="title-container" href="{{ route('inicio') }}">
                <span class="title" >DLG</span>
                <span class="subtitle">DOMÓTICA, LUZ Y GESTIÓN</span>
            </a>
            <div class="toggle-container">
                <!-- Authentication Links -->
                @guest
                    <strong class="toggle-content" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar sesión</strong>
                @else
                    <div class="dropdown">
                        <div class=" dropdown-toggle d-flex toggle-content align-items-center" type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="material-symbols-outlined icon">account_circle</span>
                            <!-- <strong>{{ Auth::user()->name }}</strong> -->
                            <strong>Mi cuenta</strong>
                        </div>

                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('home') }}">Home</a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                Cerrar sesión
                            </a></li>
                        </ul>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                @endguest

                <a href="{{ route('cart') }}" class="material-symbols-outlined icon toggle-content text-decoration-none">shopping_cart</a>
            </div>
        </nav>

        <!-- Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Iniciar sesión</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Correo Electrónico -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Contraseña -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Recuérdame -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Recuérdame</label>
                            </div>

                            <!-- Confirmar petición -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </form>

                        <!-- Enlace para Registrarse -->
                        <div class="d-flex flex-column text-center gap-3">
                            <a class="btn btn-link text-center" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                            <a href="{{ route('register') }}">¿No tienes cuenta? Haz click aquí</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enlaces de acceso rápido -->
        <ul class="nav justify-content-center bg-dark">
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'interior']) }}">Interior</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'exterior']) }}">Exterior</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Tipos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Estilos</a>
            </li>
            <li class="nav-item">
                <div class="dropdown">
                    <a class="nav-link text-white" href="#" id="distributionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Distribución
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="distributionDropdown">
                        <li><a class="dropdown-item" href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'dormitorio']) }}">Dormitorio</a></li>
                        <li><a class="dropdown-item" href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'Baño']) }}">Baño</a></li>
                        <li><a class="dropdown-item" href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'Jardín']) }}">Jardín</a></li>
                        <li><a class="dropdown-item" href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'Cocina']) }}">Cocina</a></li>
                        <li><a class="dropdown-item" href="{{ route('items.quickLink', ['type' => 'distribucion', 'value' => 'Salón']) }}">Salón</a></li>
                    </ul>
                </div>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link text-white" href="#">Marcas</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('items.quickLink', ['type' => 'tag', 'value' => 'ofertas']) }}">Ofertas</a>
            </li>
        </ul>
    </header>

    <!-- Contenido dinámico -->
    <main class="container-fluid">
        @yield('content')
    </main>

    <!-- Pie de página -->
    <footer class="bg-dark text-light text-center mt-3 py-3">
        <strong>Contacta con nosotros</strong>
        <ul class="contact_list">
            <li>Teléfono: 123 45 67 98</li>
            <li>Ubicación: C/ Ejemplo 12 24010 LEÓN </li>
        </ul>
        <!-- <p>&copy; {{ date('Y') }} Gesluz </p> -->
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts adicionales -->
    @stack('scripts')
</body>
</html>