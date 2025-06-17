<?php

namespace App\Http\Controllers;

use App\Models\Calidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CalidadController extends Controller
{

    public function index()
    {
        $calidad = Calidad::first();


        return inertia('admin/calidadAdmin', ['calidad' => $calidad]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $calidad = Calidad::first();



        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'text' => 'sometimes',
            'image' => 'sometimes|file',
        ]);

        if ($request->hasFile('image') && $calidad) {
            // Guardar la ruta del archivo antiguo para eliminarlo después
            $oldImagePath = $calidad->getRawOriginal('image');

            // Guardar el nuevo archivo
            $data['image'] = $request->file('image')->store('slider', 'public');

            // Eliminar el archivo antiguo si existe
            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
        } else if ($request->hasFile('image') && !$calidad) {
            $data['image'] = $request->file('image')->store('slider', 'public');
        }

        if (!$calidad) {
            // Si no existe, crear una nueva entrada
            $calidad = Calidad::create($data);
            return redirect()->back()->with('success', 'Calidad created successfully.');
            # code...
        }

        $calidad->update($data);

        return redirect()->back()->with('success', 'Calidad updated successfully.');
    }
}
