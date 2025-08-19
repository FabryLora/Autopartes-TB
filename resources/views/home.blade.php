@extends('layouts.default')

@section('title', 'Autopartes TB')

@section('description', $metadatos->description ?? "")
@section('keywords', $metadatos->keywords ?? "")

@section('content')
    <x-slider :sliders="$sliders" />
    <x-search-bar :categorias="$categorias" :subcategorias="$subcategorias" />
    <x-productos-destacados :productos="$productos" />
    <x-banner-portada :bannerPortada="$bannerPortada" />
    <x-novedades-inicio :novedades="$novedades" />
@endsection

{{-- Toast de error (auto-dismiss) --}}
@if ($errors->any())
    <div id="toast-error"
        class="fixed right-4 top-4 z-1000 w-[92vw] max-w-sm rounded-xl border border-red-300 bg-white/90 shadow-lg backdrop-blur px-4 py-3 flex items-start gap-3"
        role="alert" aria-live="assertive">
        {{-- Icono (opcional) --}}
        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd"
                d="M18 10A8 8 0 11.001 10 8 8 0 0118 10zm-8-5a.75.75 0 01.75.75v5.5a.75.75 0 11-1.5 0v-5.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                clip-rule="evenodd" />
        </svg>

        <div class="text-sm text-red-800">
            {{-- Mostramos el primer error (o podrías listar todos) --}}
            <p class="font-medium">Ocurrió un error</p>
            <p class="mt-0.5">
                {{ $errors->first() }}
            </p>
        </div>

        {{-- Botón cerrar --}}
        <button type="button"
            class="ml-auto inline-flex h-6 w-6 items-center justify-center rounded-md text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-300"
            aria-label="Cerrar"
            onclick="(function(){ const t=document.getElementById('toast-error'); if(t){ t.classList.add('opacity-0','translate-y-2'); setTimeout(()=>t.remove(),250); } })()">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <script>
        // Auto-dismiss luego de 4s con una pequeña animación
        (function () {
            const toast = document.getElementById('toast-error');
            if (!toast) return;
            const hide = () => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 250);
            };
            // Animación inicial (fade-in)
            toast.classList.add('transition', 'duration-200', 'ease-out', 'opacity-0', 'translate-y-2');
            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-2');
            });
            // Auto cerrar
            setTimeout(hide, 4000);
        })();
    </script>
@endif