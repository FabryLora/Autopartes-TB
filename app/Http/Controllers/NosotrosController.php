<?php

namespace App\Http\Controllers;

use App\Models\Nosotros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class NosotrosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nosotros = Nosotros::first();



        return Inertia::render('admin/nosotrosAdmin', ['nosotros' => $nosotros]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $nosotros = Nosotros::first();



        // Check if the Nosotros entry exists

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'text' => 'sometimes',
            'image' => 'sometimes|file',
        ]);

        if ($request->hasFile('image') && $nosotros) {
            // Guardar la ruta del archivo antiguo para eliminarlo después
            $oldImagePath = $nosotros->getRawOriginal('image');

            // Guardar el nuevo archivo
            $data['image'] = $request->file('image')->store('slider', 'public');

            // Eliminar el archivo antiguo si existe
            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
        } else if ($request->hasFile('image') && !$nosotros) {
            $data['image'] = $request->file('image')->store('slider', 'public');
        }

        if (!$nosotros) {
            // Si no existe, crear una nueva entrada
            $nosotros = Nosotros::create($data);
            return redirect()->back()->with('success', 'Nosotros created successfully.');
        }

        $nosotros->update($data);

        return redirect()->back()->with('success', 'Nosotros updated successfully.');
    }
}
