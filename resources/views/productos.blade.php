@extends('layouts.default')
@section('title', 'Autopartes TB')

@section('content')
    <div class="flex flex-col gap-10 max-sm:gap-6">

        <!-- Breadcrumb navigation -->
        <div class="hidden lg:block w-[1200px] max-sm:w-full max-sm:px-4 mx-auto h-full mt-10 max-sm:mt-6">
            <div class="text-black">
                <a href="{{ route('home') }}" class="hover:underline transition-all duration-300 font-bold">Inicio</a>
                <span class="mx-[2px]">/</span>
                <a href="{{ route('productos') }}" class="hover:underline transition-all duration-300 ">Productos</a>
            </div>
        </div>

        <x-search-bar :categorias="$categorias" :subcategorias="$subcategorias" />

        <!-- Main content with sidebar and products -->
        <div class="flex flex-col lg:flex-row gap-6 max-sm:gap-4 w-[1200px] max-sm:w-full max-sm:px-4 mx-auto">

            {{-- Sidebar with categories --}}
            <div class="w-full lg:w-[380px]">
                <div class="relative border-t border-gray-200">
                    @foreach ($categorias as $cat)
                        <div class="border-b border-gray-200"
                            x-data="{ 
                                open: {{ $modelo_id && $cat->subCategorias && $cat->subCategorias->where('id', $modelo_id ?? null)->count() > 0 ? 'true' : 'false' }} 
                             }">
                            <div
                                class="flex flex-row justify-between items-center py-3 max-sm:py-2 px-2 transition-all duration-300 ease-in-out text-lg max-sm:text-base {{ $categoria && $cat->id == $categoria->id ? 'font-semibold' : '' }}">
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
                                            class="transform transition-transform duration-200 max-sm:w-3 max-sm:h-2" :class="{ 'rotate-180': open }">
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
                                            class="block pl-4 max-sm:pl-3 py-2 max-sm:py-1.5 text-[16px] max-sm:text-sm hover:bg-gray-50 transition-colors duration-200 {{ $modelo_id && $subCategoria->id == $modelo_id ? 'font-semibold bg-gray-50' : '' }}">
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

            <div class="w-full">
                <div class="grid grid-cols-1 md:grid-cols-3 max-sm:grid-cols-1 gap-6 max-sm:gap-4">
                    @forelse($productos as $producto)
                        <a href="{{ "/p/" . $producto->code }}" 
                            class="border-gray-200 transition transform hover:-translate-y-1 hover:shadow-lg duration-300
                            h-[349px] max-sm:h-auto flex flex-col">
                            <div class="h-full flex flex-col">
                                @if ($producto->imagenes->count() > 0)
                                    <img src="{{ $producto->imagenes->first()->image }}" alt="{{ $producto->name }}"
                                        class="bg-gray-100 w-full min-h-[243px] max-sm:h-[200px] object-cover ">
                                @else
                                    <div class="w-full min-h-[243px] max-sm:min-h-[200px] bg-gray-100 flex items-center justify-center text-gray-500 ">
                                        <span>Sin imagen</span>
                                    </div>
                                @endif
                                <div class="flex flex-col justify-center h-full max-sm:p-3">
                                    <h3
                                        class="text-primary-orange group-hover:text-green-700 text-[16px] max-sm:text-sm transition-colors duration-300">
                                        {{ $producto->code }}
                                    </h3>
                                    <div class="flex flex-row gap-2">
                                        @foreach ($producto->marcas as $marca)
                                            <p class="text-gray-800 max-sm:text-sm transition-colors duration-300 ">
                                                {{ $marca->marca->name ?? 'Marca no disponible' }} -
                                            </p>
                                        @endforeach
                                    </div>

                                    <p
                                        class="text-gray-800 text-[15px] max-sm:text-sm font-semibold transition-colors duration-300 line-clamp-2 overflow-hidden break-words">
                                        {{ $producto->name }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-3 max-sm:col-span-1 py-8 max-sm:py-6 text-center text-gray-500">
                            No hay productos disponibles en esta categoría.
                        </div>
                    @endforelse
                </div>

                {{-- Enlaces de paginación --}}
                @if($productos->hasPages())
                    <div class="mt-8 max-sm:mt-6 flex flex-col justify-center my-10">
                        <div class="pagination-wrapper">
                            {{ $productos->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection