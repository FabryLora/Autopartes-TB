@php
    $location = request()->path();
    $isHome = $location === '/';
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
        scrolled: false,
        searchOpen: false,
        logoPrincipal: '{{ $logos->logo_principal ?? '' }}',
        logoSecundario: '{{ $logos->logo_secundario ?? '' }}'
    }" x-init="
        @if ($isHome)
            window.addEventListener('scroll', () => {
                scrolled = window.scrollY > 0;
            });
        @else
            scrolled = true;
        @endif
    " :class="{
        'bg-white shadow-md': scrolled || !{{ $isHome ? 'true' : 'false' }},
        'bg-transparent': !scrolled && {{ $isHome ? 'true' : 'false' }},
        'fixed top-0': {{ $isHome ? 'true' : 'false' }},
        'sticky top-0': {{ $isHome ? 'false' : 'true' }}
    }" class="z-50 w-full transition-colors duration-300 h-[131px] flex flex-col">
    <!-- Franja superior -->
    <div class="relative min-h-[49px] bg-primary-orange">
        <div class="relative w-[1200px] mx-auto h-full flex justify-end items-center gap-6">
            <div @click.away="searchOpen = false" class="flex items-center gap-2 px-2 py-1 transition-all duration-300"
                :class="{'border w-[200px]': searchOpen, 'border-none  w-[0px]': !searchOpen}">

                <label class="cursor-pointer" for="searchInput" @click="searchOpen = !searchOpen"><svg
                        xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path
                            d="M16.6 18L10.3 11.7C9.8 12.1 9.225 12.4167 8.575 12.65C7.925 12.8833 7.23333 13 6.5 13C4.68333 13 3.146 12.3707 1.888 11.112C0.63 9.85333 0.000667196 8.316 5.29101e-07 6.5C-0.000666138 4.684 0.628667 3.14667 1.888 1.888C3.14733 0.629333 4.68467 0 6.5 0C8.31533 0 9.853 0.629333 11.113 1.888C12.373 3.14667 13.002 4.684 13 6.5C13 7.23333 12.8833 7.925 12.65 8.575C12.4167 9.225 12.1 9.8 11.7 10.3L18 16.6L16.6 18ZM6.5 11C7.75 11 8.81267 10.5627 9.688 9.688C10.5633 8.81333 11.0007 7.75067 11 6.5C10.9993 5.24933 10.562 4.187 9.688 3.313C8.814 2.439 7.75133 2.00133 6.5 2C5.24867 1.99867 4.18633 2.43633 3.313 3.313C2.43967 4.18967 2.002 5.252 2 6.5C1.998 7.748 2.43567 8.81067 3.313 9.688C4.19033 10.5653 5.25267 11.0027 6.5 11Z"
                            fill="white" />
                    </svg></label>
                <input id="searchInput" type="text"
                    class="w-full bg-transparent border-none outline-none text-white placeholder:text-white text-sm"
                    placeholder="Buscar productos" />
            </div>

            <button @click="showLogin = true"
                class="text-sm text-white border border-white h-[33px] w-[184px] hover:bg-white hover:text-black">
                Zona Privada
            </button>
            <div x-show="showLogin" x-transition.opacity x-cloak class="fixed inset-0 bg-black/50"></div>
            <form x-show="showLogin" method="POST" action="{{ route('login') }}" x-transition.opacity
                @click.away="showLogin = false" x-cloak
                class="absolute border top-12 right-0 flex flex-col bg-white w-fit h-fit gap-5 items-center pt-5 px-5 pb-10">
                @csrf
                <h2 class="text-[24px] font-semibold">Área de clientes</h2>
                <div class="flex flex-col gap-2">
                    <label for="username">Nombre de usuario o correo electrónico</label>
                    <input name="name" type="text" class="w-[328px] h-[48px] border border-gray-200 px-2">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="password">Contraseña</label>
                    <input name="password" type="password" id="password"
                        class="w-[328px] h-[48px] border border-gray-200 px-2">
                </div>

                <div class="flex flex-col">
                    <button class="bg-primary-orange w-[328px] h-[48px] text-white font-bold">Iniciar sesión</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Contenido principal navbar -->
    <div class="mx-auto flex h-full w-[1200px] items-center justify-between">
        <a href="/">
            <img :src="scrolled ? logoSecundario : logoPrincipal" class="h-10 transition-all duration-300" alt="Logo" />
        </a>

        <div class="hidden md:flex gap-6 items-center">
            @foreach(($isPrivate ? $privateLinks : $defaultLinks) as $link)
                <a href="{{ $link['href'] }}" :class="scrolled ? 'text-black' : 'text-white'"
                    class="text-sm hover:text-primary-orange transition-colors duration-300 
                                                                                                                                        {{ Request::is(ltrim($link['href'], '/')) ? 'font-bold' : '' }} 
                                                                                                                                            ">
                    {{ $link['title'] }}
                </a>
            @endforeach


        </div>

    </div>
</div>