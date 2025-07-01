@extends('layouts.default')
@section('title', ' Producto')

@section('content')
    <div class="max-w-[80%] xl:max-w-[1224px] mx-auto">
        <!-- Breadcrumb navigation -->
        <div class="hidden lg:block h-[120px]">
            <div class="text-black py-4">
                <a {{-- href="{{ route('home') }}" --}} class="hover:underline transition-all duration-300">Inicio</a>
                <span class="mx-[5px]">&gt;</span>
                <a {{-- href="{{ route('categorias') }}" --}}
                    class="hover:underline transition-all duration-300">Productos</a>
                <span class="mx-[5px]">&gt;</span>
                <a {{-- href="{{ route('productos', ['id' => $categoria->id]) }}" --}}
                    class="font-light hover:underline transition-all duration-300">{{ $categoria->name }}</a>
            </div>
        </div>

        <!-- Main content with sidebar and product detail -->
        <div class="flex gap-6 py-20 lg:py-0 lg:mb-27">
            <!-- Sidebar (1/4 width) -->
            <div class="w-full lg:w-[380px]">
                <div class="relative border-t border-gray-200">
                    @foreach ($categorias as $cat)
                        <div
                            class="flex flex-row justify-between items-center py-3 px-2 border-b border-gray-200 hover:bg-gray-100 hover:pl-3 transition-all duration-300 ease-in-out text-lg {{ $cat->id == $categoria->id ? 'font-bold bg-gray-50' : '' }}">
                            <a {{-- href="{{ route('productos', ['id' => $cat->id]) }}" --}} class="block">
                                {{ $cat->name }}
                                @if ($cat->productos_count)
                                    <span
                                        class="ml-1 px-2 py-1 bg-red-500 text-white text-xs rounded-full transition-opacity duration-300">
                                        {{ $cat->productos_count }}
                                    </span>
                                @endif
                            </a>
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="8" viewBox="0 0 13 8" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.65703 7.071L2.66411e-05 1.414L1.41403 -4.94551e-07L6.36403 4.95L11.314 -6.18079e-08L12.728 1.414L7.07103 7.071C6.8835 7.25847 6.62919 7.36379 6.36403 7.36379C6.09886 7.36379 5.84455 7.25847 5.65703 7.071Z"
                                        fill="black" />
                                </svg>
                            </button>
                        </div>

                        @if ($cat->subCategorias)
                            <div class="flex flex-col gap-3">
                                @foreach ($cat->subCategorias as $subCategoria)
                                    <a href="#" {{-- href="{{ route('productos', ['id' => $subCategoria->id]) }}" --}}
                                        class="block py-2 px-4 border-b border-gray-200 hover:bg-gray-100 hover:pl-3 transition-all duration-300 ease-in-out text-md
                                                                                                                                                                                                                            {{ $subCategoria->id == $categoria->id ? 'font-bold bg-gray-50' : '' }}">
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
                    @endforeach
                </div>
            </div>

            <!-- Product Detail (3/4 width) -->
            <div class="w-full md:w-3/4">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Image Gallery -->
                    <div class="w-full md:w-1/2">
                        <!-- Main Image -->
                        <div class="mb-4 flex items-center justify-center">
                            @if ($producto->imagenes->first())
                                <img id="mainImage" src="{{ asset('storage/' . $producto->imagenes->first()->path) }}"
                                    alt="{{ $producto->titulo }}"
                                    class="w-full object-contain transition-opacity duration-300 ease-in-out">
                            @else
                                <div
                                    class="w-full h-[400px] bg-gray-100 text-gray-400 flex items-center justify-center transition-opacity duration-300 ease-in-out">
                                    <span class="text-sm">Sin imagen disponible</span>
                                </div>
                            @endif
                        </div>


                        <!-- Thumbnails -->
                        <div class="flex lg:justify-start justify-center gap-2 overflow-x-auto">
                            @foreach ($producto->imagenes as $imagen)
                                <div class="border border-gray-200 w-24 h-24 cursor-pointer hover:border-main-color flex-shrink-0
                                                                                  {{ $loop->first ? 'border-main-color' : '' }}"
                                    onclick="changeMainImage('{{ asset('storage/' . $imagen->path) }}', this)">
                                    <img src="{{ asset('storage/' . $imagen->path) }}" alt="Thumbnail"
                                        class="w-full h-full object-contain">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="w-full md:w-1/2">

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
                                    <div class="w-1/2 ">Marca</div>
                                    <div class="w-1/2   text-right">{{ $producto->categoria->name }}</div>
                                </div>
                                <div class="flex border-b border-gray-200 py-3.5">
                                    <div class="w-1/2 ">Modelo</div>
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
                        @if ($producto->ficha)
                            <div class="flex items-center justify-center gap-6 mt-6">
                                <a href="{{ asset('storage/' . $producto->ficha) }}" download="{{ basename($producto->ficha) }}"
                                    class="btn-secondary-home">
                                    Ficha técnica
                                </a>
                                <a href="{{ route('contacto') }}" class="btn-primary-home">
                                    CONSULTAR
                                </a>
                            </div>
                        @else
                            <div class="flex items-center justify-center gap-6 mt-8">
                                <a href="{{ route('contacto') }}" class="btn-primary-home-largo w-full">
                                    CONSULTAR
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Productos relacionados -->
                <div class="py-20">
                    <h2 class="text-[28px] font-bold mb-8">Productos relacionados</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($productosRelacionados as $prodRelacionado)
                            <div
                                class="border border-gray-200 overflow-hidden transition-transform transform hover:-translate-y-1 hover:shadow-lg duration-300 h-[396px]">
                                <a {{--
                                    href="{{ route('producto', ['id' => $prodRelacionado->categoria->id, 'producto' => $prodRelacionado->id]) }}"
                                    --}}>
                                    @if ($prodRelacionado->imagenes->count() > 0)
                                        <img src="{{ asset('storage/' . $prodRelacionado->imagenes->first()->path) }}"
                                            alt="{{ $prodRelacionado->titulo }}"
                                            class="bg-gray-100 w-full h-72 object-cover transition-transform duration-500 hover:scale-105">
                                    @else
                                        <div
                                            class="w-full h-72 bg-gray-100 flex items-center justify-center text-gray-500 transition-colors duration-300 hover:text-gray-700">
                                            <span>Sin imagen</span>
                                        </div>
                                    @endif
                                    <div class="py-4 px-6 transition-colors duration-300 hover:bg-gray-50">
                                        <h3
                                            class="text-green-600 font-bold group-hover:text-green-700 transition-colors duration-300">
                                            {{ $prodRelacionado->codigo }}
                                        </h3>
                                        <p class="text-gray-800 mt-2 transition-colors duration-300 line-clamp-2">
                                            {{ $prodRelacionado->titulo }}
                                        </p>
                                    </div>
                                </a>
                            </div>
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