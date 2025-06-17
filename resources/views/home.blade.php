@extends('layouts.default')

@section('title', 'Autopartes TB')

@section('content')
    <x-slider :sliders="$sliders" />
    <x-search-bar />
    <x-productos-destacados />
    <x-banner-portada :bannerPortada="$bannerPortada" />
    <x-novedades-inicio :novedades="$novedades" />
    <div class="min-h-screen">asd</div>
@endsection