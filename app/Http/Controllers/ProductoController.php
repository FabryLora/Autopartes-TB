<?php

namespace App\Http\Controllers;

use App\Models\Categoria;

use App\Models\ImagenProducto;

use App\Models\Metadatos;
use App\Models\Producto;
use App\Models\ProductoMarca;
use App\Models\ProductoModelo;
use App\Models\SubCategoria;
use App\Models\SubProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $categorias = Categoria::select('id', 'name')->get();


        $perPage = $request->input('per_page', default: 10);

        $query = Producto::query()->orderBy('order', 'asc')->with(['categoria:id,name', 'imagenes']);

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%')->orWhere('code', 'LIKE', '%' . $searchTerm . '%');
        }

        $productos = $query->paginate($perPage);

        $subcategorias = SubCategoria::orderBy('order', 'asc')->get();



        return Inertia::render('admin/productosAdmin', [
            'productos' => $productos,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias

        ]);
    }

    public function indexVistaPrevia($id)
    {
        $productos = Producto::where('categoria_id', $id)

            ->orderBy('order', 'asc')
            ->get();
        $categorias = Categoria::with('subCategorias')->orderBy('order', 'asc')->get();



        return view('productos', [
            'categorias' => $categorias,
            'productos' => $productos,
            'categoria' => Categoria::findOrFail($id),
        ]);
    }

    public function show($categoria_id, $producto_id)
    {
        $categoria = Categoria::findOrFail($categoria_id);
        $producto = Producto::with(['categoria:id,name',  'imagenes'])->findOrFail($producto_id);
        $categorias = Categoria::select('id', 'name', 'order')->orderBy('order', 'asc')->get();
        $productosRelacionados = Producto::with(['imagenes'])

            ->where('id', '!=', $producto_id)
            ->inRandomOrder()
            ->limit(3)
            ->get();


        return view('producto', [
            'producto' => $producto,
            'categorias' => $categorias,
            'categoria' => $categoria,
            'productosRelacionados' => $productosRelacionados,
        ]);
    }

    /* public function indexInicio(Request $request, $id)
    {
        $marcas = Marca::select('id', 'name', 'order')->orderBy('order', 'asc')->get();

        $categorias = Categoria::select('id', 'name', 'order')
            ->orderBy('order', 'asc')
            ->get();
        $metadatos = Metadatos::where('title', 'Productos')->first();
        if ($request->has('marca') && !empty($request->marca)) {
            $productos = Producto::where('categoria_id', $id)->whereHas('subproductos')->whereHas('imagenes')->where('marca_id', $request->marca)->with('marca', 'imagenes')->orderBy('order', 'asc')->get();
        } else {
            $productos = Producto::where('categoria_id', $id)->whereHas('subproductos')->whereHas('imagenes')->with('marca', 'imagenes')->orderBy('order', 'asc')->get();
        }
        $subproductos = SubProducto::orderBy('order', 'asc')->get();

        return Inertia::render('productos', [
            'productos' => $productos,
            'categorias' => $categorias,
            'marcas' => $marcas,
            'metadatos' => $metadatos,
            'id' => $id,
            'marca_id' => $request->marca,
            'subproductos' => $subproductos,

        ]);
    } */

    public function indexInicio(Request $request, $id)
    {


        $categorias = Categoria::select('id', 'name', 'order')
            ->orderBy('order', 'asc')
            ->get();

        $metadatos = Metadatos::where('title', 'Productos')->first();

        $query = Producto::where('categoria_id', $id)

            ->orderBy('order', 'asc');



        $productos = $query->paginate(12)->withQueryString(); // 12 por página, mantiene filtros

        // Opcional: solo subproductos de productos actuales (más eficiente)
        $productoIds = $productos->pluck('id');
        $subproductos = SubProducto::whereIn('producto_id', $productoIds)
            ->orderBy('order', 'asc')
            ->get();

        return Inertia::render('productos', [
            'productos' => $productos,
            'categorias' => $categorias,

            'metadatos' => $metadatos,
            'id' => $id,

            'subproductos' => $subproductos,
        ]);
    }

    public function imagenesProducto()
    {
        $fotos = Storage::disk('public')->files('repuestos');

        foreach ($fotos as $foto) {
            $path = pathinfo(basename($foto), PATHINFO_FILENAME);

            $producto = Producto::where('code', $path)->first();
            if (!$producto) {
                continue; // Skip if the product is not found
            }
            $url = Storage::url($foto);
            ImagenProducto::create([
                'producto_id' => $producto->id,
                'image' => $url,
            ]);
        }
    }







    public function SearchProducts(Request $request)
    {
        $query = Producto::query();

        // Aplicar filtros solo si existen
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }



        if ($request->filled('codigo')) {
            $query->where('code', 'LIKE', '%' . $request->codigo . '%');
        }

        $productos = $query->with(['categoria:id,name', 'imagenes'])
            ->get();

        $categorias = Categoria::select('id', 'name', 'order')->orderBy('order', 'asc')->get();


        return Inertia::render('productos/productoSearch', [
            'productos' => $productos, // Cambié 'producto' a 'productos' (plural)
            'categorias' => $categorias,

        ]);
    }

    public function fixImagePath()
    {
        # Quitar /storage/ de las rutas de las imágenes
        $imagenes = ImagenProducto::all();
        foreach ($imagenes as $imagen) {
            if (strpos($imagen->image, '/storage/') === 0) {
                $imagen->image = str_replace('/storage/', '', $imagen->image);
                $imagen->save();
            }
        }

        return response()->json(['message' => 'Rutas de imágenes actualizadas correctamente.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            // Validaciones del producto
            'order' => 'nullable|sometimes|max:255',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'code_oem' => 'required|string|max:255',
            'code_competitor' => 'required|string|max:255',
            'medida' => 'nullable|string|max:255',
            'desc_visible' => 'nullable|string',
            'desc_invisible' => 'nullable|string',
            'unidad_pack' => 'nullable|integer',
            'familia' => 'nullable|string|max:255',
            'stock' => 'nullable|integer',
            'descuento_oferta' => 'nullable|integer',
            'modelos' => 'nullable|array',
            'modelos.*' => 'integer|exists:sub_categorias,id', // Cada elemento debe ser un ID válido
            'marcas' => 'nullable|array',
            'marcas.*' => 'integer|exists:categorias,id',
            // Validaciones de las imágenes (opcionales)
            'images' => 'nullable|array|min:1',
            'images.*' => 'required|file|image', // máximo 2MB por imagen
        ]);

        try {
            return DB::transaction(function () use ($request, $data) {
                // Crear el producto primero
                $producto = Producto::create([
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'code_oem' => $data['code_oem'],
                    'code_competitor' => $data['code_competitor'],
                    'desc_visible' => $data['desc_visible'],
                    'desc_invisible' => $data['desc_invisible'],
                    'unidad_pack' => $data['unidad_pack'],
                    'familia' => $data['familia'],
                    'stock' => $data['stock'],
                ]);

                $createdImages = [];

                // Procesar imágenes si existen
                if ($request->hasFile(key: 'images')) {
                    foreach ($request->file('images') as $image) {
                        // Subir cada imagen
                        $imagePath = $image->store('images', 'public');

                        // Crear registro para cada imagen usando el ID del producto recién creado
                        $imageRecord = ImagenProducto::create([
                            'producto_id' => $producto->id,
                            'order' => $data['order'] ?? null,
                            'image' => $imagePath,
                        ]);

                        $createdImages[] = $imageRecord;
                    }
                }

                if ($request->has('modelos')) {
                    foreach ($data['modelos'] as $modeloId) {
                        ProductoModelo::create([
                            'producto_id' => $producto->id,
                            'sub_categoria_id' => $modeloId,
                        ]);
                    }
                }

                if ($request->has('marcas')) {
                    foreach ($data['marcas'] as $marcaId) {
                        ProductoMarca::create([
                            'producto_id' => $producto->id,
                            'categoria_id' => $marcaId,
                        ]);
                    }
                }
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $producto = Producto::findOrFail($request->id);

        // Check if the product entry exists
        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado.');
        }

        $data = $request->validate([
            'order' => 'sometimes|string|max:255',
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:255',
            'code_oem' => 'nullable|string|max:255',
            'code_competitor' => 'nullable|string|max:255',
            'categoria_id' => 'sometimes|exists:categorias,id',
            'sub_categoria_id' => 'nullable|exists:sub_categorias,id',
            'medida' => 'nullable|string|max:255',
            'desc_visible' => 'nullable|string',
            'desc_invisible' => 'nullable|string',
            'unidad_pack' => 'nullable|integer',
            'familia' => 'nullable|string|max:255',
            'stock' => 'nullable|integer',
            'descuento_oferta' => 'nullable|integer',
        ]);

        // Handle file upload

        $producto->update($data);

        return redirect()->back()->with('success', 'Producto actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $producto = Producto::findOrFail($request->id);

        // Check if the product entry exists
        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado.');
        }


        $producto->delete();

        return redirect()->back()->with('success', 'Producto eliminado correctamente.');
    }
}
