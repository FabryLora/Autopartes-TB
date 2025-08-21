@extends('layouts.default')
@section('title', 'Autopartes TB - ' . $producto->code)
@php
    use Illuminate\Support\Str;

    // Helper: detecta si una URL/route es video por extensión (soporta querystrings)
    $esVideo = function ($src) {
        $path = parse_url($src, PHP_URL_PATH) ?? $src;
        $ext = Str::lower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['mp4', 'webm', 'ogg', 'm4v']);
    };

    // 1) Armar lista de medios (imágenes y/o videos dentro de imagenes)
    $medios = [];
    $primerThumbImagen = null;

    foreach ($producto->imagenes as $img) {
        $src = $img->image;

        // guardo primer imagen para usar como thumb de video si hace falta
        if ($primerThumbImagen === null && !$esVideo($src)) {
            $primerThumbImagen = $src;
        }

        if ($esVideo($src)) {
            $medios[] = ['type' => 'video', 'src' => $src, 'thumb' => $primerThumbImagen];
        } else {
            $medios[] = ['type' => 'image', 'src' => $src, 'thumb' => $src];
        }
    }

    // 2) Si además hay $producto->video, agregalo sólo si no está ya en la lista
    if (!empty($producto->video)) {
        $videoSrc = $producto->video;
        $yaExiste = collect($medios)->contains(function ($m) use ($videoSrc) {
            return $m['type'] === 'video' && $m['src'] === $videoSrc;
        });

        if (!$yaExiste) {
            $medios[] = ['type' => 'video', 'src' => $videoSrc, 'thumb' => $primerThumbImagen];
        }
    }



    $tieneMedios = count($medios) > 0;
    $principal = $tieneMedios ? $medios[0] : null;
@endphp

@section('content')
    <div class="flex flex-col gap-10 max-sm:gap-6">
        <!-- Breadcrumb navigation -->
        <div class="hidden lg:block w-[1200px] mx-auto h-full mt-10 max-sm:hidden">
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
        <div class="flex flex-col lg:flex-row gap-6 w-[1200px] mx-auto max-sm:w-full max-sm:px-4 max-sm:gap-4">
            <!-- Sidebar (1/4 width) -->
            <div class="w-full lg:w-[380px] max-sm:w-full">
                <div class="relative border-t border-gray-200">
                    @foreach ($categorias as $cat)
                        <div class="border-b border-gray-200" x-data="{ 
                                                                                                                        open: {{ $modelo_id && $cat->subCategorias && $cat->subCategorias->where('id', $modelo_id)->count() > 0 ? 'true' : 'false' }} 
                                                                                                                    }">
                            <div
                                class="flex flex-row justify-between items-center py-3 px-2 transition-all duration-300 ease-in-out text-lg {{ $categoria && $cat->id == $categoria->id ? 'font-semibold' : '' }} max-sm:py-2 max-sm:text-base">
                                <a href="{{ route('productos', ['id' => $cat->id]) }}" class="block flex-1">
                                    {{ $cat->name }}
                                    @if ($cat->productos_count)
                                        <span
                                            class="ml-1 px-2 py-1 bg-red-500 text-white text-xs rounded-full transition-opacity duration-300 max-sm:px-1.5 max-sm:py-0.5">
                                            {{ $cat->productos_count }}
                                        </span>
                                    @endif
                                </a>
                                @if ($cat->subCategorias && $cat->subCategorias->count() > 0)
                                    <button @click="open = !open"
                                        class="p-1 hover:bg-gray-100 rounded transition-colors duration-200 max-sm:p-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="8" viewBox="0 0 13 8" fill="none"
                                            class="transform transition-transform duration-200 max-sm:w-3 max-sm:h-2"
                                            :class="{ 'rotate-180': open }">
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
                                            class="block pl-4 py-2 text-[16px] hover:bg-gray-50 transition-colors duration-200 {{ $modelo_id && $subCategoria->id == $modelo_id ? 'font-semibold bg-gray-50' : '' }} max-sm:pl-3 max-sm:py-1.5 max-sm:text-sm">
                                            {{ $subCategoria->name }}
                                            @if ($subCategoria->productos_count)
                                                <span
                                                    class="ml-1 px-2 py-1 bg-red-500 text-white text-xs rounded-full transition-opacity duration-300 max-sm:px-1.5 max-sm:py-0.5">
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
            <div class="w-full md:w-3/4 max-sm:w-full">
                <div class="flex flex-col md:flex-row gap-5 max-sm:gap-4">
                    <!-- Image Gallery -->
                    <div class="w-full md:w-1/2 relative max-sm:w-full">
                        <!-- Main media -->
                        <div class="flex items-center justify-center h-[410px] max-sm:h-[280px]">
                            @if($tieneMedios)
                                @if($principal['type'] === 'image')
                                    <img id="mainMedia" src="{{ $principal['src'] }}" data-type="image"
                                        alt="{{ $producto->titulo }}"
                                        class="w-full h-full object-cover object-center transition-opacity duration-300 ease-in-out opacity-0">
                                @else
                                    <video id="mainMedia" data-type="video" src="{{ $principal['src'] }}"
                                        class="w-full h-full object-cover object-center transition-opacity duration-300 ease-in-out opacity-0"
                                        autoplay playsinline></video>
                                @endif
                            @else
                                <div
                                    class="w-full h-full bg-gray-100 text-gray-400 flex items-center justify-center transition-opacity duration-300 ease-in-out">
                                    <span class="text-sm max-sm:text-xs">Sin media disponible</span>
                                </div>
                            @endif
                        </div>

                        <!-- Thumbnails -->
                        @if($tieneMedios)
                            <div
                                class="absolute -bottom-24 mt-5 flex lg:justify-start justify-center gap-2 overflow-x-auto max-sm:static max-sm:mt-4 max-sm:justify-start max-sm:gap-1.5">
                                @foreach ($medios as $i => $m)
                                    <div class="border border-gray-200 w-[80px] h-[80px] cursor-pointer hover:border-main-color flex-shrink-0 max-sm:w-[60px] max-sm:h-[60px]
                                                                        {{ $loop->first ? 'border-main-color' : '' }}"
                                        onclick="changeMainMedia('{{ $m['src'] }}', '{{ $m['type'] }}', this)">

                                        @if($m['type'] === 'image')
                                            <img src="{{ $m['thumb'] ?? $m['src'] }}" alt="Thumbnail"
                                                class="w-full h-full object-cover">
                                        @else
                                            {{-- Siempre ícono de play para videos --}}
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="w-8 h-8 fill-current text-black max-sm:w-6 max-sm:h-6">
                                                    <path d="M8 5v14l11-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="w-full md:w-1/2 flex flex-col min-h-full justify-between max-sm:w-full max-sm:mt-6">
                        <div>
                            <h1 class="text-[28px] font-semibold leading-[1] max-sm:text-xl max-sm:leading-tight">
                                {{ $producto->name }}
                            </h1>
                            <div class="prose max-w-none py-2 custom-summernote max-sm:py-1 max-sm:text-sm">
                                {!! $producto->desc_visible !!}
                            </div>

                            <!-- Características técnicas -->
                            <div class="mb-6 max-sm:mb-4">
                                <div class="border-t border-gray-200">
                                    <div class="flex border-b border-gray-200 py-3.5 max-sm:py-2.5">
                                        <div class="w-1/2 max-sm:text-sm">Código</div>
                                        <div class="w-1/2 text-right max-sm:text-sm">{{ $producto->code }}</div>
                                    </div>

                                    <div class="flex border-b border-gray-200 py-3.5 max-sm:py-2.5">
                                        <div class="w-1/2 max-sm:text-sm">Código OEM</div>
                                        <div class="w-1/2 text-right max-sm:text-sm">{{ $producto->code_oem }}</div>
                                    </div>

                                    <div class="flex border-b border-gray-200 py-3.5 max-sm:py-2.5">
                                        <div class="w-1/2 max-sm:text-sm">Medidas</div>
                                        <div class="w-1/2 text-right max-sm:text-sm">{{ $producto->medida ?? null }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('contacto', ['mensaje' => $producto->name]) }}"
                            class="w-full flex justify-center items-center bg-primary-orange text-white font-bold h-[41px] max-sm:h-[36px] max-sm:text-sm">
                            Consultar
                        </a>
                    </div>
                </div>

                <div class="mt-16 flex flex-col md:mt-30 max-sm:mt-8">
                    <div class="hidden h-[52px] grid-cols-5 items-center bg-[#F5F5F5] px-4 md:grid max-sm:hidden">
                        <p>Marca</p>
                        <p>Modelo</p>
                    </div>

                    @foreach($producto->modelos as $modelo)
                        <div
                            class="flex flex-col border-b border-[#E0E0E0] py-3 text-[#74716A] md:grid md:min-h-[52px] md:grid-cols-5 md:items-center md:px-4 md:py-0 max-sm:py-2">
                            <div class="flex justify-between md:block max-sm:text-sm">
                                <p class="font-semibold md:hidden md:font-normal">Marca:</p>
                                <p>{{ $modelo->modelo->categoria->name }}</p>
                            </div>
                            <div class="flex justify-between md:block max-sm:text-sm">
                                <p class="font-semibold md:hidden md:font-normal">Modelo:</p>
                                {{ $modelo->modelo->name }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Productos relacionados -->
                <div class="py-20 max-sm:py-10">
                    <h2 class="text-[28px] font-bold mb-8 max-sm:text-xl max-sm:mb-6">Productos relacionados</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-sm:grid-cols-1 max-sm:gap-4">
                        @forelse($productosRelacionados as $prodRelacionado)
                            <a href="{{ "/p/" . $prodRelacionado->code }}" class="border-gray-200 transition transform hover:-translate-y-1 hover:shadow-lg duration-300
                                            h-[349px] max-sm:h-auto flex flex-col">
                                <div class="h-full flex flex-col">
                                    @if ($prodRelacionado->imagenes->count() > 0)
                                        <img src="{{ $prodRelacionado->imagenes->first()->image }}"
                                            alt="{{ $prodRelacionado->name }}"
                                            class="bg-gray-100 w-full min-h-[243px] max-sm:h-[200px] object-cover ">
                                    @else
                                        <div
                                            class="w-full min-h-[243px] max-sm:min-h-[200px] bg-gray-100 flex items-center justify-center text-gray-500 ">
                                            <span>Sin imagen</span>
                                        </div>
                                    @endif
                                    <div class="flex flex-col justify-center h-full max-sm:p-3">
                                        <h3
                                            class="text-primary-orange group-hover:text-green-700 text-[16px] max-sm:text-sm transition-colors duration-300">
                                            {{ $prodRelacionado->code }}
                                        </h3>
                                        <div class="flex flex-row gap-2">
                                            @foreach ($prodRelacionado->marcas as $marca)
                                                <p class="text-gray-800 max-sm:text-sm transition-colors duration-300 ">
                                                    {{ $marca->marca->name ?? 'Marca no disponible' }} -
                                                </p>
                                            @endforeach
                                        </div>

                                        <p
                                            class="text-gray-800 text-[15px] max-sm:text-sm font-semibold transition-colors duration-300 line-clamp-2 overflow-hidden break-words">
                                            {{ $prodRelacionado->name }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-3 py-8 text-center text-gray-500 max-sm:col-span-1 max-sm:py-6">
                                No hay productos relacionados disponibles.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fadeOut(el, cb) {
            el.style.opacity = '0';
            setTimeout(cb, 300);
        }

        function fadeIn(el) {
            // Forzar reflow para reiniciar transición cuando reemplazamos nodos
            void el.offsetWidth;
            el.style.opacity = '1';
        }

        function changeMainMedia(src, type, thumbnail) {
            const container = document.querySelector('#mainMedia')?.parentElement;
            const current = document.getElementById('mainMedia');

            if (!container) return;

            const buildNode = () => {
                if (type === 'video') {
                    const v = document.createElement('video');
                    v.id = 'mainMedia';
                    v.setAttribute('data-type', 'video');
                    v.src = src;
                    v.className = 'w-full h-full object-cover object-center transition-opacity duration-300 ease-in-out opacity-0';
                    v.setAttribute('controls', '');
                    v.setAttribute('playsinline', '');
                    return v;
                } else {
                    const img = document.createElement('img');
                    img.id = 'mainMedia';
                    img.setAttribute('data-type', 'image');
                    img.src = src;
                    img.alt = 'Imagen';
                    img.className = 'w-full h-full object-cover object-center transition-opacity duration-300 ease-in-out opacity-0';
                    return img;
                }
            };

            if (current) {
                fadeOut(current, () => {
                    const newNode = buildNode();
                    container.replaceChild(newNode, current);
                    // actualizar borde de miniaturas
                    const thumbsWrap = thumbnail.parentElement.parentElement;
                    thumbsWrap.querySelectorAll('div').forEach(thumb => thumb.classList.remove('border-main-color'));
                    thumbnail.classList.add('border-main-color');
                    // fade in
                    requestAnimationFrame(() => fadeIn(newNode));
                });
            }

        }

        // Ensure media visible on initial load
        document.addEventListener('DOMContentLoaded', () => {
            const main = document.getElementById('mainMedia');
            if (main) main.style.opacity = '1';
        });
    </script>

    <style>
        #mainMedia {
            opacity: 0;
        }
    </style>
@endsection