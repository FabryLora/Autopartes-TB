<!-- resources/views/layouts/default.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi sitio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- si usás Vite --}}
    @stack('head') {{-- Para inyectar scripts o estilos específicos --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script src="//unpkg.com/alpinejs" defer></script>

</head>

<body class="font-sans text-gray-900 antialiased">

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Contenido principal --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer (opcional) --}}
    @includeIf('components.footer')

    @stack('scripts') {{-- Scripts específicos de cada vista --}}
</body>

</html>