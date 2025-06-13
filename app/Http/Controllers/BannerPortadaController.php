<?php

namespace App\Http\Controllers;

use App\Models\BannerPortada;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class BannerPortadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banner = BannerPortada::first();

        return Inertia::render('admin/bannerPortadaAdmin', ['banner' => $banner]);
    }





    public function update(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'sometimes|file',
        ]);

        $bannerPortada = BannerPortada::first();

        // Si no existe, lo creamos
        if (!$bannerPortada) {
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('banner_portada', 'public');
            }
            BannerPortada::create($data);

            return redirect()->back()->with('success', 'Banner creado correctamente');
        }

        // Si existe y se sube nueva imagen, eliminamos la anterior
        if ($request->hasFile('image')) {
            if ($bannerPortada->getRawOriginal('image')) {
                Storage::disk('public')->delete($bannerPortada->getRawOriginal('image'));
            }
            $data['image'] = $request->file('image')->store('banner_portada', 'public');
        }

        $bannerPortada->update($data);

        return redirect()->back()->with('success', 'Banner actualizado correctamente');
    }
}
