<?php

use App\Http\Controllers\DescargarArchivo;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SendContactInfoController;
use App\Models\ArchivoCalidad;
use App\Models\BannerNovedades;
use App\Models\BannerPortada;
use App\Models\Calidad;
use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\Instagram;
use App\Models\Logos;
use App\Models\Marca;
use App\Models\Metadatos;
use App\Models\Nosotros;
use App\Models\Novedades;
use App\Models\Producto;
use App\Models\Provincia;
use App\Models\Slider;
use App\Models\Valores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create();

    // Página de inicio
    $sitemap->add(Url::create('/'));
    $sitemap->add(Url::create("/nosotros"));
    $sitemap->add(Url::create("/productos"));
    $sitemap->add(Url::create("/calidad"));
    $sitemap->add(Url::create("/novedades"));
    $sitemap->add(Url::create("/contacto"));


    return $sitemap->toResponse(request());
});


Route::get('/', function () {
    $sliders = Slider::orderBy('order', 'asc')->get();
    $bannerPortada = BannerPortada::first();
    $novedades = Novedades::where('featured', true)->orderBy('order', 'asc')->get();
    return view('home', [
        'sliders' => $sliders,
        'bannerPortada' => $bannerPortada,
        'novedades' => $novedades,
    ]);
})->name('home');

Route::get('/nosotros', function () {
    $bannerPortada = BannerPortada::first();
    $nosotros = Nosotros::first();
    $valores = Valores::first();
    $metadatos = Metadatos::where('title', 'Nosotros')->first();

    return Inertia::render('nosotros', [
        'bannerPortada' => $bannerPortada,
        'nosotros' => $nosotros,
        'valores' => $valores,
        'metadatos' => $metadatos,
    ]);
})->name('nosotros');

Route::get('/calidad', function () {
    $calidad = Calidad::first();
    $archivos = ArchivoCalidad::orderBy('order', 'asc')->get();
    $metadatos = Metadatos::where('title', 'Calidad')->first();


    return Inertia::render('calidad', [
        'calidad' => $calidad,
        'archivos' => $archivos,
        'metadatos' => $metadatos,

    ]);
})->name('calidad');

Route::get('/novedades', function () {

    $novedades = Novedades::orderBy('order', 'asc')->get();
    $metadatos = Metadatos::where('title', 'Novedades')->first();

    return Inertia::render('novedades', [
        'novedades' => $novedades,

        'metadatos' => $metadatos,

    ]);
})->name('novedades.index');

Route::get('/novedades/{id}', function ($id) {
    $novedad = Novedades::where('id', $id)->first();

    return Inertia::render('novedadesShow', [
        'novedad' => $novedad,
    ]);
})->name('novedades');



Route::get('/contacto', function (Request $request) {
    $producto = $request->producto;
    return Inertia::render('contacto', [
        'producto' => $producto,
    ]);
})->name('contacto');
Route::get('/productos', [ProductoController::class, 'indexVistaPrevia'])->name('/productos');
Route::get('/productos/{id}', [ProductoController::class, 'indexInicio'])->name('/productos');
Route::get('/productos/{categoria_id}/{producto_id}', [ProductoController::class, 'show'])->name('/productoss');
Route::get('/busqueda', [ProductoController::class, 'SearchProducts'])->name('searchproducts');


Route::get('/fix-images', [ProductoController::class, 'fixImagePath'])->name('fix.images');

Route::get('/imagenes-prod', [ProductoController::class, 'imagenesProducto']);
Route::get('/agregar-marca', [ProductoController::class, 'agregarMarca']);

Route::post('/sendcontact', [SendContactInfoController::class, 'sendReactEmail'])->name('send.contact');

// routes/web.php
Route::get('/descargar/archivo/{id}', [DescargarArchivo::class, 'descargarArchivo'])
    ->name('descargar.archivo');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render(component: 'dashboard');
    })->name('dashboard');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/admin_auth.php';
