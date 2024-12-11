<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Gesluz')</title>

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS de Google Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    
    <!-- Estilos adicionales -->
    <!-- <link rel="stylesheet" href="/resources/css/app.css"> -->
    <link rel="stylesheet" href="{{ asset('styles/mainLayout.css') }}">
</head>
<body>
    <!-- Encabezado / Navbar -->
    <header>
        <!-- Sidenav -->
        <div id="sidenav" class="offcanvas offcanvas-start bg-light" tabindex="-1" aria-labelledby="sidenavLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title " id="sidenavLabel">Menú</h5>
                <div class="toggle-container" data-bs-dismiss="offcanvas" aria-label="Close">
                    <span class="btn-close sidebar-close-icon"></span>
                </div>
            </div>
            <div class="offcanvas-body">
                <ul class="list-unstyled">
                    <li>
                        <a href="#" class=" d-flex align-items-center">
                            <i class="far fa-smile fa-fw me-2"></i> Link 1
                        </a>
                    </li>
                    <li>
                        <a href="#" class=" d-flex align-items-center mt-3">
                            <i class="fas fa-grin fa-fw me-2"></i> Category 1
                        </a>
                        <ul class="list-unstyled ps-3">
                            <li><a href="#" class="">Link 2</a></li>
                            <li><a href="#" class="">Link 3</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Navbar -->
        <nav class="navbar">
            <div class="toggle-container" data-bs-toggle="offcanvas" data-bs-target="#sidenav" aria-controls="sidenav">
                <span class="navbar-toggler-icon"></span>
            </div>
            <div>
                GESLUZ
            </div>
            <div class="toggle-container">
                <span data-bs-toggle="modal" data-bs-target="#loginModal">Login</span>
                <span class="material-symbols-outlined icon">account_circle</span>
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