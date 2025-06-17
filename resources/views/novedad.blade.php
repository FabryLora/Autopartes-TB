@extends('layouts.default')

@section('title', $novedad->title . ' - Autopartes TB')

@section('content')
    <div class="relative flex h-[400px] w-full items-center justify-center">
        <div class="absolute inset-0 w-full h-full bg-black/50 z-10">

        </div>
        <img class="absolute h-full w-full object-cover object-center" src="{{ $novedad->image }}" alt="">


        <div class="absolute flex flex-col z-20">
            <h2 class="text-4xl font-bold text-white">{{ $novedad->title }}</h2>
            <p class="text-primary-orange text-center text-lg font-semibold">{{ $novedad->type }}</p>
        </div>
    </div>

    <div class="mx-auto w-[1200px] py-20">
        <div class="break-words">
            {!! $novedad->text !!}
        </div>
    </div>
@endsection