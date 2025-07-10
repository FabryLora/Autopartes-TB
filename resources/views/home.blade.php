@extends('layouts.default')

@section('title', 'Autopartes TB')

@section('content')
    <x-slider :sliders="$sliders" />
    <div class="w-full bg-primary-orange">
        <form action="{{ route('productos') }}" method="GET"
            class="flex flex-row gap-4 w-[1200px] mx-auto  h-[123px] items-center">
            <select name="id" class="h-[47px] border bg-white w-full">
                <option value="">Marca</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                @endforeach
            </select>

            <select name="modelo_id" class="h-[47px] border bg-white w-full">
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
    <div class="mx-auto flex w-[1200px] flex-col gap-5 my-10">
        <div class="flex flex-row items-center justify-between">
            <h2 class="text-[32px] font-semibold">Productos destacados</h2>
            <a href="{{ url('/productos') }}"
                class="text-primary-orange border-primary-orange hover:bg-primary-orange flex h-[41px] w-[127px] items-center justify-center border text-base font-semibold transition duration-300 hover:text-white">
                Ver todos
            </a>
        </div>

        <div class="flex flex-row gap-5">
            @foreach ($productos as $producto)
                <a href="{{ "/p/" . $producto->code }}" {{-- route('producto', ['codigo'=> $producto->code]) --}}
                    class=" border-gray-200 transition transform hover:-translate-y-1 hover:shadow-lg duration-300
                    h-[349px] flex flex-col w-[288px]">
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
            @endforeach
        </div>
    </div>
    <x-banner-portada :bannerPortada="$bannerPortada" />
    <x-novedades-inicio :novedades="$novedades" />

@endsection