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

<div x-data="{
        showLogin: false,
        showRegister: false,
        scrolled: false,
        logoPrincipal: '{{ $logos->logo_principal ?? '' }}',
        logoSecundario: '{{ $logos->logo_secundario ?? '' }}'
    }" x-init="
        window.addEventListener('scroll', () => {
            scrolled = window.scrollY > 0;
        })
    " :class="scrolled ? 'bg-white shadow-md' : 'bg-transparent'"
    class="fixed top-0 z-50 w-full transition-colors duration-300 h-[131px] flex flex-col">

    <div class="min-h-[49px] bg-primary-orange">
        <div class="w-[1200px] mx-auto h-full flex justify-end items-center">
            <button>lupa</button>
            <button @click="showLogin = true"
                class="text-sm text-white border border-white h-[33px] w-[184px] hover:bg-white hover:text-black">
                Zona Privada
            </button>
        </div>
    </div>

    <div class="mx-auto flex h-full w-[1200px] items-center justify-between">
        <a href="/">
            <img :src="scrolled ? logoSecundario : logoPrincipal" class="h-10 transition-all duration-300" alt="Logo" />

        </a>

        <div class="hidden md:flex gap-6 items-center">
            @foreach(($isPrivate ? $privateLinks : $defaultLinks) as $link)
                <a href="{{ $link['href'] }}" :class="[scrolled ? 'text-black' : 'text-white', 'text-sm hover:text-yellow-500 transition-colors duration-300']">

                    {{ $link['title'] }}
                </a>
            @endforeach

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button :class="scrolled ? 'bg-orange-500 text-white' : 'bg-white text-black'"
                        class="text-sm px-4 py-2 rounded transition-colors duration-300">
                        {{ ucfirst(auth()->user()->name) }}
                    </button>
                </form>
            @endauth
        </div>
    </div>

    @include('components.login-modal')
    @include('components.register-modal')
</div>