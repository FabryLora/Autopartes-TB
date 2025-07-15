<?php

namespace App\Http\Controllers;

use App\Models\ArchivoCalidad;
use App\Models\BannerPortada;
use App\Models\Calidad;
use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\Metadatos;
use App\Models\Nosotros;
use App\Models\Novedades;
use App\Models\Producto;
use App\Models\Slider;
use App\Models\SubCategoria;
use App\Models\Valores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePages extends Controller
{
    public function home()
    {
        if (Auth::check()) {
            return redirect('/privada/productos');
        }

        $metadatos = Metadatos::where('name', 'home')->first();

        $categorias = Categoria::orderBy('order', 'asc')->get();
        $subcategorias = SubCategoria::orderBy('order', 'asc')->get();
        $sliders = Slider::orderBy('order', 'asc')->get();
        $bannerPortada = BannerPortada::first();
        $novedades = Novedades::where('featured', true)->orderBy('order', 'asc')->get();
        $productos = Producto::where('destacado', true)
            ->orderBy('order', 'asc')
            ->with(['imagenes', 'marcas', 'modelos', 'precio'])
            ->get();
        return view('home', [
            'sliders' => $sliders,
            'bannerPortada' => $bannerPortada,
            'novedades' => $novedades,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
            'productos' => $productos,
            'metadatos' => $metadatos,
        ]);
    }

    public function empresa()
    {
        $nosotros = Nosotros::first();
        $valores = Valores::first();
        return view('empresa', [
            'nosotros' => $nosotros,
            'valores' => $valores,
        ]);
    }

    public function calidad()
    {
        $calidad = Calidad::first();
        $archivos = ArchivoCalidad::orderBy('order', 'asc')->get();

        return view('calidad', [
            'calidad' => $calidad,
            'archivos' => $archivos,
        ]);
    }

    public function lanzamientos()
    {
        $lanzamientos = Novedades::orderBy('order', 'asc')
            ->get();
        return view('lanzamientos', [
            'lanzamientos' => $lanzamientos,
        ]);
    }

    public function contacto(Request $request)
    {
        $contacto = Contacto::first();
        return view('contacto', [
            'contacto' => $contacto,
            'mensaje' => $request->mensaje ?? null,
        ]);
    }
}
