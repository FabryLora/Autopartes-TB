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
use Gloudemans\Shoppingcart\Facades\Cart;
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

        $query = Producto::query()->orderBy('order', 'asc')->with(['marcas', 'modelos', 'imagenes']);

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

    public function indexVistaPrevia(Request $request)
    {
        // Construir query base para productos
        $query = Producto::query();

        // Filtro por categoría (a través de marcas)
        if ($request->filled('id')) {
            $query->whereHas('marcas', function ($q) use ($request) {
                $q->where('categoria_id', $request->id);
            });
        }

        // Filtro por modelo/subcategoría
        if ($request->filled('modelo_id')) {
            $query->whereHas('modelos', function ($q) use ($request) {
                $q->where('sub_categoria_id', $request->modelo_id);
            });
        }

        // Filtro por código
        if ($request->filled('code')) {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        // Filtro por código OEM
        if ($request->filled('code_oem')) {
            $query->where('code_oem', 'LIKE', '%' . $request->code_oem . '%');
        }

        // Filtro por descripción visible
        if ($request->filled('desc_visible')) {
            $query->where('desc_visible', 'LIKE', '%' . $request->desc . '%')->orWhere('desc_invisible', 'LIKE', '%' . $request->desc . '%');
        }

        // Aplicar ordenamiento por defecto
        $query->orderBy('order', 'asc');

        // Ejecutar query
        $productos = $query->get();

        if ($productos->count() === 1) {
            return redirect('/p/' . $productos->first()->code);
        }

        // Cargar datos adicionales para la vista
        $categorias = Categoria::with('subCategorias')->orderBy('order', 'asc')->get();
        $subcategorias = SubCategoria::orderBy('order', 'asc')->get();
        $categoria = $request->filled('id') ? Categoria::findOrFail($request->id) : null;

        return view('productos', [
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
            'productos' => $productos,
            'categoria' => $categoria,
            'id' => $request->id,
            'modelo_id' => $request->modelo_id,
            'code' => $request->code,
            'code_oem' => $request->code_oem,
            'desc_visible' => $request->desc_visible,

        ]);
    }

    public function show($codigo, Request $request)
    {
        $producto = Producto::with(['categoria:id,name', 'imagenes', 'marcas', 'modelos'])->where('code', $codigo)->first();

        $subcategorias = SubCategoria::orderBy('order', 'asc')->get();

        $categorias = Categoria::select('id', 'name', 'order')->orderBy('order', 'asc')->get();

        // Obtener productos relacionados que compartan marcas y modelos
        $productosRelacionados = collect();

        if ($producto) {
            // Obtener IDs de marcas y modelos del producto actual
            $marcaIds = $producto->marcas->pluck('id')->toArray();
            $modeloIds = $producto->modelos->pluck('id')->toArray();

            if (!empty($marcaIds) || !empty($modeloIds)) {
                $query = Producto::with(['imagenes'])
                    ->where('id', '!=', $producto->id); // Cambiar a comparar por ID

                // Filtrar por marcas y modelos usando whereHas
                if (!empty($marcaIds) && !empty($modeloIds)) {
                    // Productos que compartan marcas O modelos
                    $query->where(function ($q) use ($marcaIds, $modeloIds) {
                        $q->whereHas('marcas', function ($subQuery) use ($marcaIds) {
                            $subQuery->whereIn('id', $marcaIds);
                        })->orWhereHas('modelos', function ($subQuery) use ($modeloIds) {
                            $subQuery->whereIn('id', $modeloIds);
                        });
                    });
                } elseif (!empty($marcaIds)) {
                    // Solo filtrar por marcas
                    $query->whereHas('marcas', function ($subQuery) use ($marcaIds) {
                        $subQuery->whereIn('id', $marcaIds);
                    });
                } elseif (!empty($modeloIds)) {
                    // Solo filtrar por modelos
                    $query->whereHas('modelos', function ($subQuery) use ($modeloIds) {
                        $subQuery->whereIn('id', $modeloIds);
                    });
                }

                $productosRelacionados = $query->inRandomOrder()->limit(3)->get();
            }

            // Si no se encontraron suficientes productos relacionados, completar con productos aleatorios
            if ($productosRelacionados->count() < 3) {
                $idsExcluir = $productosRelacionados->pluck('id')->toArray();
                $idsExcluir[] = $producto->id; // Agregar el ID del producto actual

                $productosAleatorios = Producto::with(['imagenes'])
                    ->whereNotIn('id', $idsExcluir)
                    ->inRandomOrder()
                    ->limit(3 - $productosRelacionados->count())
                    ->get();

                $productosRelacionados = $productosRelacionados->merge($productosAleatorios);
            }
        }



        return view('producto', [
            'producto' => $producto,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
            'categoria' => $producto->categoria,
            'productosRelacionados' => $productosRelacionados,
            'modelo_id' => $request->modelo_id ?? null
        ]);
    }

    public function indexPrivada(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $qty = $request->input('qty', 1); // Valor por defecto para qty
        $carrito = Cart::content();

        $query = Producto::with(['imagenes', 'marcas', 'modelos', 'precio'])->orderBy('order', 'asc');

        // Filtrar por código del subproducto

        if ($request->filled('code')) {
            $query->where('code', 'like', "%{$request->code}%");
        }
        // Filtrar por categoría del producto
        if ($request->filled('categoria')) {
            $query->whereHas('producto', function ($q) use ($request) {
                $q->where('categoria_id', $request->categoria);
            });
        }

        #subcateogria
        if ($request->filled('subcategoria')) {
            $query->whereHas('subproductos', function ($q) use ($request) {
                $q->where('sub_categoria_id', $request->subcategoria);
            });
        }

        // Filtrar por código OEM del producto
        if ($request->filled('codigo_oem')) {
            $query->where('codigo_oem', 'like', "%{$request->codigo_oem}%");
        }

        // Filtrar por descripción visible del producto
        if ($request->filled('descripcion')) {
            $query->where('descripcion', 'like', "%{$request->descripcion}%")->orWhere('descripcion_invisible', 'like', "%{$request->descripcion}%");
        }



        $productos = $query->paginate(perPage: $perPage);

        // Modificar los productos para agregar rowId y qty del carrito
        $productos->getCollection()->transform(function ($producto) use ($carrito, $qty) {
            // Buscar el item del carrito que corresponde a este producto
            $itemCarrito = $carrito->where('id', $producto->id)->first();

            if ($itemCarrito) {
                $producto->rowId = $itemCarrito ? $itemCarrito->rowId : null;
                $producto->qty = $itemCarrito ? $itemCarrito->qty : null;
                $producto->subtotal =  $itemCarrito ? $itemCarrito->price * ($itemCarrito->qty ?? 1) : $producto->precio->precio;
            } else {
                $producto->rowId = null;
                $producto->qty = $qty; // Asignar qty por defecto si no está en el carrito
                $producto->subtotal = $producto->precio ? $producto->precio->precio * ($producto->qty ?? 1) : 0; // Asignar precio base si no está en el carrito
            }

            // Aquí puedes agregar más lógica si es necesario, como calcular el subtotal
            // Agregar el rowId y qty al producto
            // Calcular subtotal

            return $producto;
        });
        # si el usuario es vendedor

        $categorias = Categoria::orderBy('order', 'asc')->get();
        $subcategorias = SubCategoria::orderBy('order', 'asc')->get();

        return inertia('privada/productosPrivada', [
            'productos' => $productos,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
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
            'destacado' => 'nullable|sometimes|boolean',
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
                    'destacado' => $data['destacado'] ?? false,
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



    public function update(Request $request)
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
            'modelos.*' => 'integer|exists:sub_categorias,id',
            'marcas' => 'nullable|array',
            'marcas.*' => 'integer|exists:categorias,id',
            'destacado' => 'nullable|sometimes|boolean',
            // Validaciones de las imágenes (opcionales)
            'images' => 'nullable|array|min:1',
            'images.*' => 'required|file|image',
            // Para eliminar imágenes existentes
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:imagen_productos,id',
        ]);

        try {
            return DB::transaction(function () use ($request, $data) {
                // Buscar el producto
                $producto = Producto::findOrFail($request->id);

                // Actualizar los datos del producto
                $producto->update([
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'code_oem' => $data['code_oem'],
                    'code_competitor' => $data['code_competitor'],
                    'desc_visible' => $data['desc_visible'],
                    'desc_invisible' => $data['desc_invisible'],
                    'destacado' => $data['destacado'] ?? false,
                    'unidad_pack' => $data['unidad_pack'],
                    'familia' => $data['familia'],
                    'stock' => $data['stock'],
                ]);

                if ($request->has('images_to_delete')) {
                    foreach ($request->images_to_delete as $imageId) {
                        $image = ImagenProducto::find($imageId);
                        if ($image) {
                            // Eliminar archivo del storage
                            Storage::delete($image->image);
                            // Eliminar registro de la base de datos
                            $image->delete();
                        }
                    }
                }

                // Agregar nuevas imágenes
                if ($request->hasFile('new_images')) {
                    foreach ($request->file('new_images') as $image) {
                        $path = $image->store('images', 'public');

                        ImagenProducto::create([
                            'producto_id' => $producto->id,
                            'image' => $path,

                        ]);
                    }
                }

                // Actualizar otros campos del producto


                // Eliminar imágenes seleccionadas si se especificaron
                if ($request->has('delete_images')) {
                    $imagesToDelete = ImagenProducto::where('producto_id', $producto->id)
                        ->whereIn('id', $data['delete_images'])
                        ->get();

                    foreach ($imagesToDelete as $imageRecord) {
                        // Eliminar archivo físico
                        if (Storage::disk('public')->exists($imageRecord->image)) {
                            Storage::disk('public')->delete($imageRecord->image);
                        }
                        // Eliminar registro de la base de datos
                        $imageRecord->delete();
                    }
                }

                // Procesar nuevas imágenes si existen
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        // Subir cada imagen
                        $imagePath = $image->store('images', 'public');

                        // Crear registro para cada imagen
                        ImagenProducto::create([
                            'producto_id' => $producto->id,
                            'order' => $data['order'] ?? null,
                            'image' => $imagePath,
                        ]);
                    }
                }

                // Actualizar relaciones con modelos
                if ($request->has('modelos')) {
                    // Eliminar relaciones existentes
                    ProductoModelo::where('producto_id', $producto->id)->delete();

                    // Crear nuevas relaciones
                    foreach ($data['modelos'] as $modeloId) {
                        ProductoModelo::create([
                            'producto_id' => $producto->id,
                            'sub_categoria_id' => $modeloId,
                        ]);
                    }
                }

                // Actualizar relaciones con marcas
                if ($request->has('marcas')) {
                    // Eliminar relaciones existentes
                    ProductoMarca::where('producto_id', $producto->id)->delete();

                    // Crear nuevas relaciones
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
                'message' => 'Error al actualizar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $id = $request->id;
        try {
            return DB::transaction(function () use ($id) {
                // Buscar el producto
                $producto = Producto::findOrFail($id);

                // Eliminar todas las imágenes asociadas
                $imagenes = ImagenProducto::where('producto_id', $producto->id)->get();
                foreach ($imagenes as $imagen) {
                    // Eliminar archivo físico del storage
                    if (Storage::disk('public')->exists($imagen->image)) {
                        Storage::disk('public')->delete($imagen->image);
                    }
                    // Eliminar registro de la base de datos
                    $imagen->delete();
                }

                // Eliminar relaciones con modelos
                ProductoModelo::where('producto_id', $producto->id)->delete();

                // Eliminar relaciones con marcas
                ProductoMarca::where('producto_id', $producto->id)->delete();

                // Eliminar el producto
                $producto->delete();
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function cambiarDestacado(Request $request)
    {
        $producto = Producto::findOrFail($request->id);
        $producto->destacado = !$producto->destacado; // Cambiar el estado de destacado
        $producto->save();
    }
}
