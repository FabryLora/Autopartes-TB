@extends('layouts.default')
@section('title', 'Autopartes TB')

@section('content')
    <div class="flex flex-col gap-10">


        <!-- Breadcrumb navigation -->
        <div class="hidden lg:block  w-[1200px] mx-auto h-full mt-10">
            <div class="text-black">
                <a href="{{ route('home') }}" class="hover:underline transition-all duration-300 font-bold">Inicio</a>
                <span class="mx-[2px]">/</span>
                <a href="{{ route('productos') }}" class="hover:underline transition-all duration-300 ">Productos</a>

            </div>
        </div>


        <div class="w-full bg-primary-orange">
            <form action="{{ route('productos') }}" method="GET"
                class="flex flex-row gap-4 w-[1200px] mx-auto  h-[123px] items-center">
                <select value="{{ request('id') ?? '' }}" name="id" class="h-[47px] border bg-white w-full">
                    <option value="">Marca</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                    @endforeach
                </select>

                <select value="{{ request('modelo_id') ?? '' }}" name="modelo_id" class="h-[47px] border bg-white w-full">
                    <option value="">Modelo</option>
                    @foreach($subcategorias as $subcategoria)
                        <option value="{{ $subcategoria->id }}">{{ $subcategoria->name }}</option>
                    @endforeach
                </select>

                <input type="text" value="{{ request('medida') }}" name="medida" placeholder="Medida"
                    class="h-[47px] pl-2 border bg-white w-full placeholder:text-black" />

                <input type="text" value="{{ request('code') }}" name="code" placeholder="Código"
                    class="h-[47px] pl-2 border bg-white w-full placeholder:text-black" />
                <input type="text" value="{{ request('code_oem') }}" name="code_oem" placeholder="Cód. OEM"
                    class="h-[47px] pl-2 border bg-white w-full placeholder:text-black" />
                <input type="text" value="{{ request('desc') }}" name="desc" placeholder="Descripción"
                    class="h-[47px] pl-2 border bg-white w-full placeholder:text-black" />

                <button type="submit"
                    class="border border-white text-white h-[47px] w-full hover:bg-white hover:text-black transition duration-300">
                    Buscar
                </button>
            </form>
        </div>

        <!-- Main content with sidebar and products -->
        <div class="flex flex-col lg:flex-row gap-6  w-[1200px] mx-auto">

            {{-- Sidebar with categories --}}
            <div class="w-full lg:w-[380px]">
                <div class="relative border-t border-gray-200">
                    @foreach ($categorias as $cat)
                        <div class="border-b border-gray-200"
                            x-data="{ 
                                                                                                                                                                                                                                    open: {{ $modelo_id && $cat->subCategorias && $cat->subCategorias->where('id', $modelo_id ?? null)->count() > 0 ? 'true' : 'false' }} 
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

            <div class="w-full ">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($productos as $producto)
                        <a href="{{ "/p/" . $producto->code }}" {{-- route('producto', ['codigo'=> $producto->code]) --}}
                            class=" border-gray-200 transition transform hover:-translate-y-1 hover:shadow-lg duration-300
                            h-[349px] flex flex-col">
                            <div class="h-full flex flex-col">
                                @if ($producto->imagenes->count() > 0)
                                    <img src="{{ $producto->imagenes->first()->image }}" alt="{{ $producto->name }}"
                                        class="bg-gray-100 w-full min-h-[243px] object-cover ">
                                @else
                                    <div class="w-full min-h-[243px] bg-gray-100 flex items-center justify-center text-gray-500 ">
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
                            No hay productos disponibles en esta categoría.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection