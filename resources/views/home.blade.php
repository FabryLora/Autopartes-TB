@extends('layouts.default')

@section('title', 'Autopartes TB')

@section('content')
    <x-slider :sliders="$sliders" />
    <x-search-bar :categorias="$categorias" :subcategorias="$subcategorias" />
    <x-productos-destacados :productos="$productos" />
    <x-banner-portada :bannerPortada="$bannerPortada" />
    <x-novedades-inicio :novedades="$novedades" />

@endsection