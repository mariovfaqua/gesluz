<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Gesluz')</title>

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Estilos adicionales -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- Encabezado / Navbar -->
    <header>
    </header>

    <!-- Contenido dinámico -->
    <main class="container-fluid">
        @yield('content')
    </main>

    <!-- Pie de página -->
    <footer class="bg-dark text-center py-3">
        <p>&copy; {{ date('Y') }} Gesluz </p>
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts adicionales -->
    @stack('scripts')
</body>
</html>