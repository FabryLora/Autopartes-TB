@extends('layouts.default')

@section('title', 'Calidad - Autopartes TB')

@section('content')
    <div class="mx-auto grid grid-cols-2 w-full max-w-[1200px]   px-4  gap-10  py-20">
        <div class="h-auto w-full lg:h-[476px]">
            <img class="h-full w-full object-cover" src="{{ $calidad->image ?? null }}" alt="Imagen nosotros">
        </div>
        <div class="h-full w-full py-4 lg:py-10">
            <div class="flex flex-col gap-4 lg:gap-6">
                <h2 class="text-2xl font-bold sm:text-3xl">{{ $calidad->title ?? null }}</h2>
                <div class="" {!! $calidad->text ?? null !!}></div>
            </div>
        </div>
        @foreach ($archivos as $archivo)
            <div class="w-full border border-primary-orange flex flex-row justify-between items-center px-4 h-[59px]">
                <div class="flex flex-row gap-3 items-center">
                    <img src={{$archivo->image ?? null}} class="w-[27px] h-[27px]" alt="">
                    <p>{{$archivo->name ?? null}}</p>
                </div>
                <a href={{asset("storage/" . $archivo->archivo)}} download="{{$archivo->name ?? null}}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15M7 10L12 15M12 15L17 10M12 15V3"
                            stroke="#0072C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        @endforeach
    </div>

@endsection