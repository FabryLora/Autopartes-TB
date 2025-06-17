<?php

namespace App\Http\Controllers;

use App\Models\ArchivoCalidad;
use App\Models\BannerPortada;
use App\Models\Calidad;
use App\Models\Nosotros;
use App\Models\Novedades;
use App\Models\Slider;
use App\Models\Valores;
use Illuminate\Http\Request;

class HomePages extends Controller
{
    public function home()
    {
        $sliders = Slider::orderBy('order', 'asc')->get();
        $bannerPortada = BannerPortada::first();
        $novedades = Novedades::where('featured', true)->orderBy('order', 'asc')->get();
        return view('home', [
            'sliders' => $sliders,
            'bannerPortada' => $bannerPortada,
            'novedades' => $novedades,
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
}
