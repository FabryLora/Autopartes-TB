@extends('layouts.default')

@section('title', 'Lanzamientos - Autopartes TB')

@section('content')
    <div class="w-[1200px] mx-auto my-20">
        <div class="flex flex-row gap-6">
            @foreach($lanzamientos as $novedad)
                <a href="{{ url('/lanzamientos/' . $novedad->id) }}" class="flex flex-col gap-2 max-w-[392px] h-[530px]">
                    <div class="max-w-[391px] min-h-[321px]">
                        <img src="{{ $novedad->image }}" alt="{{ $novedad->title }}" class="h-full outline w-full object-cover">
                    </div>
                    <div class="flex h-full flex-col justify-between">
                        <div class="flex flex-col gap-2">
                            <p class="text-primary-orange text-sm font-bold uppercase">{{ $novedad->type }}</p>
                            <div>
                                <p class="text-xl font-bold sm:text-2xl">{{ $novedad->title }}</p>
                                <div class="line-clamp-3 overflow-hidden break-words">
                                    {!! $novedad->text !!}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-row items-center justify-between">
                            <p class="font-bold">Leer más</p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M1 8H15M15 8L8 1M15 8L8 15" stroke="#0072C6" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

@endsection