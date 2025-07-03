<?php

namespace App\Http\Controllers;

use App\Models\ImagenProducto;
use Illuminate\Http\Request;

class ImagenProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /* public function index()
    {
        
    } */



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'order' => 'nullable|string|max:255',
            'images' => 'required|array|min:1', // Cambié a array
            'images.*' => 'required|file|image', // Validación para cada imagen
        ]);

        $createdImages = [];

        // Procesar cada imagen
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Subir cada imagen
                $imagePath = $image->store('images', 'public');

                // Crear registro para cada imagen
                $imageRecord = ImagenProducto::create([
                    'producto_id' => $data['producto_id'],
                    'order' => $data['order'],
                    'image' => $imagePath,
                ]);

                $createdImages[] = $imageRecord;
            }
        }

        // Opcional: retornar las imágenes creadas
        return response()->json([
            'message' => 'Imágenes subidas correctamente',
            'images' => $createdImages,
            'count' => count($createdImages)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $imagenProducto = ImagenProducto::findOrFail($request->id);
        if (!$imagenProducto) {
            return redirect()->back()->with('error', 'No se encontró la imagen del producto.');
        }

        $data = $request->validate([
            'order' => 'nullable|string|max:255',
            'producto_id' => 'sometimes|exists:productos,id',
            'image' => 'sometimes|file',
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($imagenProducto->image) {
                $absolutePath = public_path('storage/' . $imagenProducto->image);
                if (file_exists($absolutePath)) {
                    unlink($absolutePath);
                }
            }
            // Store the new image
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        $imagenProducto->update($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $imagenProducto = ImagenProducto::findOrFail($request->id);
        if (!$imagenProducto) {
            return redirect()->back()->with('error', 'No se encontró la imagen del producto.');
        }

        // Delete the old image if it exists
        if ($imagenProducto->image) {
            $absolutePath = public_path('storage/' . $imagenProducto->image);
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            }
        }

        $imagenProducto->delete();
    }
}
