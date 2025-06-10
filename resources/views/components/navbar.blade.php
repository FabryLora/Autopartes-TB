<!-- resources/views/components/navbar.blade.php -->
@php
    $location = request()->path();
    $isPrivate = str_contains($location, 'privada');
    $defaultLinks = [
        ['title' => 'Empresa', 'href' => '/empresa'],
        ['title' => 'Productos', 'href' => '/productos'],
        ['title' => 'Calidad', 'href' => '/calidad'],
        ['title' => 'Lanzamientos', 'href' => '/lanzamientos'],
        ['title' => 'Contacto', 'href' => '/contacto'],
    ];
    $privateLinks = [
        ['title' => 'Productos', 'href' => '/privada/productos'],
        ['title' => 'Carrito', 'href' => '/privada/carrito'],
        ['title' => 'Mis pedidos', 'href' => '/privada/mispedidos'],
        ['title' => 'Lista de precios', 'href' => '/privada/listadeprecios'],
    ];
@endphp

<div x-data="{ showLogin: false, showRegister: false }"
    class="fixed top-0 z-50 w-full bg-white h-[131px] shadow-md flex flex-col">
    <div class="min-h-[49px] bg-primary-orange">
        <div class="w-[1200px] mx-auto h-full  flex justify-end flex-row items-center">
            <button>lupa</button>
            <button @click="showLogin = true"
                class="text-sm text-white border border-white h-[33px] w-[184px] hover:bg-white hover:text-black">
                Zona Privada
            </button>
        </div>

    </div>
    <div class="mx-auto flex h-full w-[1200px] items-center justify-between ">
        <a href="/">
            <img src="{{ $logos['logo_secundario'] ?? '' }}" class="h-10" alt="Logo" />
        </a>

        <div class="hidden md:flex gap-6 items-center">
            @foreach(($isPrivate ? $privateLinks : $defaultLinks) as $link)
                <a href="{{ $link['href'] }}" class="text-sm text-black hover:text-yellow-500">
                    {{ $link['title'] }}
                </a>
            @endforeach

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-white bg-orange-500 px-4 py-2 rounded">
                        {{ ucfirst(auth()->user()->name) }}
                    </button>
                </form>


            @endauth
        </div>
    </div>

    @include('components.login-modal')
    @include('components.register-modal')
</div>