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
    <div class="relative min-h-[49px] bg-primary-orange" x-data="searchComponent()">
        <div class="relative w-[1200px] mx-auto h-full flex justify-end items-center gap-6">
            <div @click.away="closeSearch()"
                class="relative flex items-center gap-2 px-2 py-1 transition-all duration-300"
                :class="{'border w-[200px]': searchOpen, 'border-none w-[0px]': !searchOpen}">

                <label class="cursor-pointer" for="searchInput" @click="searchOpen = !searchOpen">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path
                            d="M16.6 18L10.3 11.7C9.8 12.1 9.225 12.4167 8.575 12.65C7.925 12.8833 7.23333 13 6.5 13C4.68333 13 3.146 12.3707 1.888 11.112C0.63 9.85333 0.000667196 8.316 5.29101e-07 6.5C-0.000666138 4.684 0.628667 3.14667 1.888 1.888C3.14733 0.629333 4.68467 0 6.5 0C8.31533 0 9.853 0.629333 11.113 1.888C12.373 3.14667 13.002 4.684 13 6.5C13 7.23333 12.8833 7.925 12.65 8.575C12.4167 9.225 12.1 9.8 11.7 10.3L18 16.6L16.6 18ZM6.5 11C7.75 11 8.81267 10.5627 9.688 9.688C10.5633 8.81333 11.0007 7.75067 11 6.5C10.9993 5.24933 10.562 4.187 9.688 3.313C8.814 2.439 7.75133 2.00133 6.5 2C5.24867 1.99867 4.18633 2.43633 3.313 3.313C2.43967 4.18967 2.002 5.252 2 6.5C1.998 7.748 2.43567 8.81067 3.313 9.688C4.19033 10.5653 5.25267 11.0027 6.5 11Z"
                            fill="white" />
                    </svg>
                </label>

                <input id="searchInput" type="text" x-model="searchQuery" @input="handleSearch()"
                    @focus="searchOpen = true"
                    class="w-full bg-transparent border-none outline-none text-white placeholder:text-white text-sm"
                    placeholder="Buscar productos" />

                <!-- Modal de resultados -->
                <div x-show="showResults && searchQuery.length > 0"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 z-50 max-h-96 overflow-y-auto">

                    <!-- Loading state -->
                    <div x-show="isLoading" class="p-4 text-center text-gray-500">
                        <svg class="animate-spin h-5 w-5 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Buscando productos...
                    </div>

                    <!-- Results -->
                    <div x-show="!isLoading && results.length > 0">
                        <div class="p-2 border-b border-gray-100 bg-gray-50">
                            <span class="text-sm font-medium text-gray-600"
                                x-text="`${results.length} producto(s) encontrado(s)`"></span>
                        </div>

                        <template x-for="product in results" :key="product.id">
                            <div class="p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors duration-150"
                                @click="selectProduct(product)">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gray-200 rounded-md flex-shrink-0">
                                        <img :src="product.image || '/images/no-image.png'" :alt="product.name"
                                            class="w-full h-full object-cover rounded-md"
                                            onerror="this.src='/images/no-image.png'">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 truncate" x-text="product.name"></h4>
                                        <p class="text-sm text-gray-500 truncate" x-text="product.description"></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-sm font-semibold text-primary-orange"
                                                x-text="`$${product.price}`"></span>
                                            <span x-show="product.stock > 0" class="text-xs text-green-600">En
                                                stock</span>
                                            <span x-show="product.stock === 0"
                                                class="text-xs text-red-600">Agotado</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- No results -->
                    <div x-show="!isLoading && results.length === 0 && searchQuery.length > 0"
                        class="p-6 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p>No se encontraron productos</p>
                        <p class="text-sm mt-1">Intenta con otros términos de búsqueda</p>
                    </div>

                    <!-- Ver todos los resultados -->
                    <div x-show="!isLoading && results.length > 0" class="p-3 border-t border-gray-100 bg-gray-50">
                        <button @click="viewAllResults()"
                            class="w-full text-center text-sm font-medium text-primary-orange hover:text-primary-orange-dark transition-colors duration-150">
                            Ver todos los resultados
                        </button>
                    </div>
                </div>
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
                    <input name="usuario" type="text" id="login_name"
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
<script>
    function searchComponent() {
        return {
            searchOpen: false,
            searchQuery: '',
            showResults: false,
            isLoading: false,
            results: [],
            searchTimeout: null,

            handleSearch() {
                // Limpiar timeout anterior
                if (this.searchTimeout) {
                    clearTimeout(this.searchTimeout);
                }

                // Si el query está vacío, ocultar resultados
                if (this.searchQuery.trim() === '') {
                    this.showResults = false;
                    this.results = [];
                    return;
                }

                // Debounce la búsqueda
                this.searchTimeout = setTimeout(() => {
                    this.performSearch();
                }, 300);
            },

            async performSearch() {
                this.isLoading = true;
                this.showResults = true;

                try {
                    const response = await fetch('/api/search', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            query: this.searchQuery
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Error en la búsqueda');
                    }

                    const data = await response.json();
                    this.results = data.products || [];
                } catch (error) {
                    console.error('Error en la búsqueda:', error);
                    this.results = [];
                } finally {
                    this.isLoading = false;
                }
            },

            selectProduct(product) {
                // Redirigir al producto seleccionado
                window.location.href = `/productos/${product.id}`;
            },

            viewAllResults() {
                // Redirigir a la página de resultados completos
                window.location.href = `/buscar?q=${encodeURIComponent(this.searchQuery)}`;
            },

            closeSearch() {
                this.searchOpen = false;
                this.showResults = false;
                this.searchQuery = '';
                this.results = [];
            }
        }
    }
</script>
</div>