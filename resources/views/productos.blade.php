@extends('layouts.default')
@section('title', 'Autopartes TB')

@section('content')
    <div class="max-w-[80%] xl:max-w-[1200px] mx-auto">
        <!-- Breadcrumb navigation -->
        <div class="hidden lg:block h-[120px]">
            <div class="text-black py-4">
                <a {{-- href="{{ route('home') }}" --}} class="hover:underline transition-all duration-300">Inicio</a>
                <span class="mx-[5px]">&gt;</span>
                <a {{-- href="{{ route('categorias') }}" --}}
                    class="hover:underline transition-all duration-300">Productos</a>
                <span class="mx-[5px]">&gt;</span>
                <a {{-- href="{{ route('productos', ['id' => $categoria->id]) }}" --}}
                    class="font-light hover:underline transition-all duration-300">{{ $categoria->titulo }}</a>
            </div>
        </div>

        <!-- Main content with sidebar and products -->
        <div class="flex flex-col lg:flex-row gap-6 py-10 lg:py-0 lg:mb-27">
            <div class="w-full lg:w-[380px]">
                <div class="relative border-t border-gray-200">
                    @foreach ($categorias as $cat)
                    <div class="flex flex-row justify-between items-center py-3 px-2 border-b border-gray-200 hover:bg-gray-100 hover:pl-3 transition-all duration-300 ease-in-out text-lg {{ $cat->id == $categoria->id ? 'font-bold bg-gray-50' : '' }}">
                            <a {{-- href="{{ route('productos', ['id' => $cat->id]) }}" --}}
                            class="block">
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
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.65703 7.071L2.66411e-05 1.414L1.41403 -4.94551e-07L6.36403 4.95L11.314 -6.18079e-08L12.728 1.414L7.07103 7.071C6.8835 7.25847 6.62919 7.36379 6.36403 7.36379C6.09886 7.36379 5.84455 7.25847 5.65703 7.071Z" fill="black"/>
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

            <div class="w-full ">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($productos as $producto)
                        <a
                            href="#"
                            class=" border-gray-200 transition-transform transform hover:-translate-y-1 hover:shadow-lg duration-300 h-[349px] flex flex-col">
                            <div class="h-full flex flex-col">
                                @if ($producto->imagenes->count() > 0)
                                    <img src="{{ asset('storage/' . $producto->imagenes->first()->path) }}"
                                        alt="{{ $producto->name }}"
                                        class="bg-gray-100 w-full min-h-[243px] object-cover transition-transform duration-500 hover:scale-105">
                                @else
                                    <div
                                        class="w-full min-h-[243px] bg-gray-100 flex items-center justify-center text-gray-500 transition-colors duration-300 hover:text-gray-700">
                                        <span>Sin imagen</span>
                                    </div>
                                @endif
                                <div class=" transition-colors duration-300 hover:bg-gray-50 flex flex-col justify-center h-full">
                                    <h3
                                        class="text-primary-orange  group-hover:text-green-700 text-[16px] transition-colors duration-300">
                                        {{ $producto->code }}
                                    </h3>
                                    <p class="text-gray-800 transition-colors duration-300 ">
                                        {{ $producto->marca->name ?? 'Marca no disponible' }}
                                    </p>
                                    <p
                                        class="text-gray-800 text-[20px] font-semibold transition-colors duration-300 ">
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