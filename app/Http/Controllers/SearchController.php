<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Ajusta según tu modelo
use App\Models\Producto;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {

        $request->validate([
            'query' => 'required|string|min:1|max:100'
        ]);

        $query = $request->input('query');
        $limit = $request->input('limit', 10); // Limitar resultados por defecto

        try {
            // Buscar productos por nombre, descripción o SKU
            $products = Producto::where('name', 'LIKE', "%{$query}%")
                ->orWhere('desc_visible', 'LIKE', "%{$query}%")
                ->orWhere('desc_invisible', 'LIKE', "%{$query}%")
                ->orWhere('code', 'LIKE', "%{$query}%")
                ->orWhere('code_oem', 'LIKE', "%{$query}%")
                ->orWhere('code_competitor', 'LIKE', "%{$query}%")

                ->with('imagenes')
                ->limit($limit)
                ->get();

            // Formatear los resultados


            return response()->json([
                'success' => true,
                'products' => $products,
                'total' => $products->count(),
                'query' => $query
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la búsqueda',
                'products' => [],
                'total' => 0
            ], 500);
        }
    }

    public function searchPage(Request $request)
    {

        $query = $request->input('q', '');
        $perPage = 20;

        $products = collect();
        $total = 0;

        if (!empty($query)) {
            $products = Producto::where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('sku', 'LIKE', "%{$query}%");
            })
                ->orderByRaw("
                    CASE 
                        WHEN name LIKE '{$query}%' THEN 1
                        WHEN name LIKE '%{$query}%' THEN 2
                        WHEN description LIKE '%{$query}%' THEN 3
                        ELSE 4
                    END
                ")
                ->paginate($perPage);

            $total = $products->total();
        }

        return view('search.results', compact('products', 'query', 'total'));
    }
}
