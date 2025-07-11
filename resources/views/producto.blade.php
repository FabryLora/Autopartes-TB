@extends('layouts.default')
@section('title', 'Autopartes TB - ' . $producto->code)

@section('content')
    <div class="flex flex-col gap-10">
        <!-- Breadcrumb navigation -->
        <div class="hidden lg:block  w-[1200px] mx-auto h-full mt-10">
            <div class="text-black">
                <a href="{{ route('home') }}" class="hover:underline transition-all duration-300 font-bold">Inicio</a>
                <span class="mx-[2px]">/</span>
                <a href="{{ route('productos') }}"
                    class="hover:underline transition-all duration-300 font-bold">Productos</a>
                <span class="mx-[2px]">/</span>
                <a href="{{"/" . $producto->code }}"
                    class="font-light hover:underline transition-all duration-300">{{ $producto->code ?? '' }}</a>
            </div>
        </div>



        <!-- Main content with sidebar and product detail -->
        <div class="flex flex-col lg:flex-row gap-6  w-[1200px] mx-auto">
            <!-- Sidebar (1/4 width) -->
            <div class="w-full lg:w-[380px]">
                <div class="relative border-t border-gray-200">
                    @foreach ($categorias as $cat)
                        <div class="border-b border-gray-200"
                            x-data="{ 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            open: {{ $modelo_id && $cat->subCategorias && $cat->subCategorias->where('id', $modelo_id)->count() > 0 ? 'true' : 'false' }} 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         }">
                            <div
                                class="flex flex-row justify-between items-center py-3 px-2 transition-all duration-300 ease-in-out text-lg {{ $categoria && $cat->id == $categoria->id ? 'font-semibold' : '' }}">
                                <a href="{{ route('productos', ['id' => $cat->id]) }}" class="block flex-1">
                                    {{ $cat->name }}
                                    @if ($cat->productos_count)
                                        <span
                                            class="ml-1 px-2 py-1 bg-red-500 text-white text-xs rounded-full transition-opacity duration-300">
                                            {{ $cat->productos_count }}
                                        </span>
                                    @endif
                                </a>
                                @if ($cat->subCategorias && $cat->subCategorias->count() > 0)
                                    <button @click="open = !open"
                                        class="p-1 hover:bg-gray-100 rounded transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="8" viewBox="0 0 13 8" fill="none"
                                            class="transform transition-transform duration-200" :class="{ 'rotate-180': open }">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M5.65703 7.071L2.66411e-05 1.414L1.41403 -4.94551e-07L6.36403 4.95L11.314 -6.18079e-08L12.728 1.414L7.07103 7.071C6.8835 7.25847 6.62919 7.36379 6.36403 7.36379C6.09886 7.36379 5.84455 7.25847 5.65703 7.071Z"
                                                fill="black" />
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            @if ($cat->subCategorias && $cat->subCategorias->count() > 0)
                                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                    x-transition:leave-end="opacity-0 transform -translate-y-2">
                                    @foreach ($cat->subCategorias as $subCategoria)
                                        <a href="{{ route('productos', ['id' => $subCategoria->categoria->id, 'modelo_id' => $subCategoria->id]) }}"
                                            class="block pl-4 py-2 text-[16px] hover:bg-gray-50 transition-colors duration-200 {{ $modelo_id && $subCategoria->id == $modelo_id ? 'font-semibold bg-gray-50' : '' }}">
                                            {{ $subCategoria->name }}
                                            @if ($subCategoria->productos_count)
                                                <span
                                                    class="ml-1 px-2 py-1 bg-red-500 text-white text-xs rounded-full transition-opacity duration-300">
                                                    {{ $subCategoria->productos_count }}
                                                </span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Product Detail (3/4 width) -->
            <div class="w-full md:w-3/4">
                <div class="flex flex-col md:flex-row gap-5">
                    <!-- Image Gallery -->
                    <div class="w-full md:w-1/2 relative">
                        <!-- Main Image -->
                        <div class=" flex items-center justify-center h-[410px]">
                            @if ($producto->imagenes->first())
                                <img id="mainImage" src="{{ $producto->imagenes->first()->image }}"
                                    alt="{{ $producto->titulo }}"
                                    class="w-full h-full object-cover object-center transition-opacity duration-300 ease-in-out">
                            @else
                                <div
                                    class="w-full h-full bg-gray-100 text-gray-400 flex items-center justify-center transition-opacity duration-300 ease-in-out">
                                    <span class="text-sm">Sin imagen disponible</span>
                                </div>
                            @endif
                        </div>


                        <!-- Thumbnails -->
                        <div class="absolute -bottom-24 mt-5 flex lg:justify-start justify-center gap-2 overflow-x-auto">
                            @foreach ($producto->imagenes as $imagen)
                                <div class="border border-gray-200 w-[80px] h-[80px] cursor-pointer hover:border-main-color flex-shrink-0
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  {{ $loop->first ? 'border-main-color' : '' }}"
                                    onclick="changeMainImage('{{ $imagen->image }}', this)">
                                    <img src="{{ $imagen->image }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="w-full md:w-1/2 flex flex-col min-h-full justify-between">
                        <div>
                            <h1 class="text-[28px] font-semibold leading-[1]">{{ $producto->name }}</h1>
                            <div class="prose max-w-none py-2 custom-summernote">
                                {!! $producto->desc_visible !!}
                            </div>

                            <!-- Características técnicas -->
                            <div class="mb-6">

                                <div class="border-t border-gray-200">



                                    <div class="flex border-b border-gray-200 py-3.5">
                                        <div class="w-1/2 ">Código</div>
                                        <div class="w-1/2   text-right">{{ $producto->code }}</div>
                                    </div>


                                    <div class="flex border-b border-gray-200 py-3.5">
                                        <div class="w-1/2 ">Código OEM</div>
                                        <div class="w-1/2   text-right">{{ $producto->code_oem }}</div>
                                    </div>
                                    <div class="flex border-b border-gray-200 py-3.5">
                                        <div class="w-1/2 ">Medidas</div>
                                        <div class="w-1/2   text-right">{{ $producto->medida ?? null }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <a href="{{ route('contacto', ['mensaje' => $producto->name]) }}"
                            class="w-full flex justify-center items-center bg-primary-orange text-white font-bold h-[41px]">
                            Consultar
                        </a>


                    </div>
                </div>

                <div class="mt-16 flex flex-col md:mt-30">
                    <div class="hidden h-[52px] grid-cols-5 items-center bg-[#F5F5F5] px-4 md:grid">
                        <p>Marca</p>
                        <p>Modelo</p>

                    </div>

                    @foreach($producto->modelos as $modelo)
                        <div
                            class="flex flex-col border-b border-[#E0E0E0] py-3 text-[#74716A] md:grid md:min-h-[52px] md:grid-cols-5 md:items-center md:px-4 md:py-0">
                            <div class="flex justify-between md:block">
                                <p class="font-semibold md:hidden md:font-normal">Marca:</p>
                                <p>{{ $modelo->modelo->categoria->name }}</p>
                            </div>
                            <div class="flex justify-between md:block">
                                <p class="font-semibold md:hidden md:font-normal">Modelo:</p>
                                {{ $modelo->modelo->name }}
                            </div>

                        </div>
                    @endforeach

                </div>
                <!-- Productos relacionados -->
                <div class="py-20">
                    <h2 class="text-[28px] font-bold mb-8">Productos relacionados</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($productosRelacionados as $prodRelacionado)
                            <a href="{{ "/" . $producto->code }}" {{-- route('producto', ['codigo'=> $producto->code]) --}}
                                class=" border-gray-200 transition transform hover:-translate-y-1 hover:shadow-lg duration-300
                                h-[349px] flex flex-col">
                                <div class="h-full flex flex-col">
                                    @if ($producto->imagenes->count() > 0)
                                        <img src="{{ $producto->imagenes->first()->image }}" alt="{{ $producto->name }}"
                                            class="bg-gray-100 w-full min-h-[243px] object-cover ">
                                    @else
                                        <div
                                            class="w-full min-h-[243px] bg-gray-100 flex items-center justify-center text-gray-500 ">
                                            <span>Sin imagen</span>
                                        </div>
                                    @endif
                                    <div class="  flex flex-col justify-center h-full">
                                        <h3
                                            class="text-primary-orange  group-hover:text-green-700 text-[16px] transition-colors duration-300">
                                            {{ $producto->code }}
                                        </h3>
                                        <p class="text-gray-800 transition-colors duration-300 ">
                                            {{ $producto->marcas->first()->name ?? 'Marca no disponible' }}
                                        </p>
                                        <p class="text-gray-800 text-[20px] font-semibold transition-colors duration-300 ">
                                            {{ $producto->name }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-3 py-8 text-center text-gray-500">
                                No hay productos relacionados disponibles.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(src, thumbnail) {
            const mainImage = document.getElementById('mainImage');

            // Fade out effect
            mainImage.style.opacity = '0';

            // Change image after fade out completes
            setTimeout(() => {
                mainImage.src = src;

                // Fade in the new image
                mainImage.style.opacity = '1';

                // Update thumbnail borders
                document.querySelectorAll('.flex.gap-2 > div').forEach(thumb => {
                    thumb.classList.remove('border-main-color');
                });
                thumbnail.classList.add('border-main-color');
            }, 300);
        }

        // Ensure image is visible on initial load
        document.addEventListener('DOMContentLoaded', () => {
            const mainImage = document.getElementById('mainImage');
            mainImage.style.opacity = '1';
        });
    </script>

    <style>
        #mainImage {
            opacity: 0;
        }
    </style>
@endsection