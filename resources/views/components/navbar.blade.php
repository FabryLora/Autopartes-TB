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
        showModal: false,
        modalType: 'login',
        scrolled: false,
        searchOpen: false,
        logoPrincipal: '{{ $logos->logo_principal ?? '' }}',
        logoSecundario: '{{ $logos->logo_secundario ?? '' }}',
        switchToLogin() {
            this.modalType = 'login';
        },
        switchToRegister() {
            this.modalType = 'register';
        },
        openModal(type = 'login') {
            this.modalType = type;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        }
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

            <button @click="openModal('login')"
                class="text-sm text-white border border-white h-[33px] w-[184px] hover:bg-white hover:text-black">
                Zona Privada
            </button>
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

    <!-- Overlay del modal -->
    <div x-show="showModal" x-transition.opacity x-cloak class="fixed inset-0 bg-black/50 z-50" @click="closeModal()">
    </div>
    <!-- Modal de Login -->
    <div x-show="showModal && modalType === 'login'" x-transition.opacity x-cloak
        class="fixed inset-0 flex items-center justify-center z-50">
        <form method="POST" action="{{ route('login') }}" @click.away="closeModal()"
            class="relative bg-white rounded-lg shadow-lg w-[400px] max-w-[90vw] p-6">

            <!-- Botón cerrar -->
            <button type="button" @click="closeModal()"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            @csrf
            <h2 class="text-2xl font-semibold mb-6 text-center">Iniciar Sesión</h2>

            <div class="space-y-4">
                <div>
                    <label for="login_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de usuario o correo electrónico
                    </label>
                    <input name="name" type="text" id="login_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                </div>

                <div>
                    <label for="login_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <input name="password" type="password" id="login_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                </div>

                <button type="submit"
                    class="w-full bg-primary-orange text-white py-2 px-4 rounded-md hover:bg-primary-orange/80 transition-colors">
                    Iniciar Sesión
                </button>
            </div>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    ¿No tienes cuenta?
                    <button type="button" @click="switchToRegister()"
                        class="text-primary-orange hover:underline font-medium">
                        Regístrate aquí
                    </button>
                </p>
            </div>
        </form>
    </div>

    <!-- Modal de Registro -->
    <div x-show="showModal && modalType === 'register'" x-transition.opacity x-cloak
        class="fixed inset-0 flex items-center justify-center z-50">
        <form method="POST" action="{{ route('register') }}" @click.away="closeModal()"
            class="relative bg-white rounded-lg shadow-lg w-[500px] max-w-[90vw] p-6">

            <!-- Botón cerrar -->
            <button type="button" @click="closeModal()"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            @csrf
            <h2 class="text-2xl font-semibold mb-6 text-center">Crear Cuenta</h2>

            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label for="register_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de usuario
                    </label>
                    <input name="name" type="text" id="register_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                </div>

                <div>
                    <label for="password">Contraseña</label>
                    <input name="password" type="password" id="register_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                </div>


                <div>
                    <label for="register_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar contraseña
                    </label>
                    <input name="password_confirmation" type="password" id="register_password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                </div>

                {{-- mail--}}
                <div>
                    <label for="register_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo electrónico
                    </label>
                    <input name="email" type="email" id="register_email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                </div>

                {{-- cuit --}}
                <div>
                    <label for="register_cuit" class="block text-sm font-medium text-gray-700 mb-2">
                        CUIT
                    </label>
                    <input name="cuit" type="text" id="register_cuit"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                </div>

                {{-- direccion --}}
                <div>
                    <label for="register_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Dirección
                    </label>
                    <input name="direccion" type="text" id="register_address"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                </div>

                {{-- telefono --}}

                <div>
                    <label for="register_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono
                    </label>
                    <input name="telefono" type="text" id="register_phone"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                </div>

                {{-- provincia --}}
                <div>
                    <label for="register_provincia" class="block text-sm font-medium text-gray-700 mb-2">
                        Provincia
                    </label>
                    <select name="provincia" id="register_provincia"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                        <option value="">Seleccione una provincia</option>
                        @foreach($provincias as $provincia)
                            <option value="{{ $provincia->name }}">{{ $provincia->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- localidad --}}
                <div>
                    <label for="register_localidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Localidad
                    </label>
                    <select name="localidad" id="register_localidad"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-orange">
                        <option value="">Seleccione una localidad</option>
                        @foreach($provincias as $provincia)
                            @foreach($provincia->localidades as $localidad)
                                <option value="{{ $localidad->name }}">{{ $localidad->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>



                <button type="submit"
                    class="w-full bg-primary-orange text-white py-2 px-4 rounded-md hover:bg-orange-600 transition-colors col-span-2">
                    Crear Cuenta
                </button>
            </div>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    ¿Ya tienes cuenta?
                    <button type="button" @click="switchToLogin()"
                        class="text-primary-orange hover:underline font-medium">
                        Inicia sesión aquí
                    </button>
                </p>
            </div>
        </form>
    </div>
</div>

</div>


</form>
</div>
</div>