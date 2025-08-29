<?php

namespace App\Http\Controllers;

use App\Models\ImagenProducto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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



    public function cargarImagenes()
    {
        // Trae todos los productos (si tienes relación, puedes eager loadear: ->with('imagenes'))
        $productos = Producto::all();

        // Lista todos los archivos en /public/images (subcarpetas incluidas si usas allFiles())
        $allFiles = Storage::disk('public')->files('images');

        // (Opcional) filtrar solo extensiones de imagen conocidas
        $validExts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp'];
        $imageFiles = array_values(array_filter($allFiles, function ($path) use ($validExts) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            return in_array($ext, $validExts, true);
        }));

        foreach ($productos as $producto) {
            $code = (string) $producto->code;
            if ($code === '') {
                continue;
            }

            // Encuentra coincidencias: el nombre del archivo (sin extensión) contiene el code
            $matches = [];
            foreach ($imageFiles as $path) {
                $filenameNoExt = strtolower(pathinfo($path, PATHINFO_FILENAME));
                if (str_contains($filenameNoExt, strtolower($code))) {
                    $matches[] = $path; // ya viene como 'images/archivo.png'
                }
            }

            if (empty($matches)) {
                // Si no se encuentra nada, puedes omitir o crear un placeholder. Aquí omitimos.
                continue;
            }

            // Ordena de forma "natural" para que 30110.png, 30110 (2).png, 30110-3.jpg queden en orden lógico
            natsort($matches);
            $matches = array_values($matches);

            // Crea o actualiza registros para cada imagen encontrada
            foreach ($matches as $index => $imagePath) {
                ImagenProducto::updateOrCreate(
                    [
                        'producto_id' => $producto->id,
                        'image' => $imagePath, // unique por producto + path
                    ],
                    [
                        'order' => 'zzz',
                    ]
                );
            }
        }
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
