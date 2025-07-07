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

            <select name="medida" class="h-[47px] border bg-white w-full">
                <option value="">Medida</option>
                {{-- @foreach($medidas as $medida)
                <option value="{{ $medida }}">{{ $medida }}</option>
                @endforeach --}}
            </select>

            <input type="text" value="{{ request('code') }}" name="code" placeholder="Código"
                class="h-[47px] pl-2 border bg-white w-full" />
            <input type="text" value="{{ request('code_oem') }}" name="code_oem" placeholder="Cód. OEM"
                class="h-[47px] pl-2 border bg-white w-full" />
            <input type="text" value="{{ request('desc') }}" name="desc" placeholder="Descripción"
                class="h-[47px] pl-2 border bg-white w-full" />

            <button type="submit"
                class="border border-white text-white h-[47px] w-full hover:bg-white hover:text-black transition duration-300">
                Buscar
            </button>
        </form>
    </div>
    <x-productos-destacados />
    <x-banner-portada :bannerPortada="$bannerPortada" />
    <x-novedades-inicio :novedades="$novedades" />

@endsection