<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 10);

        $query = Sucursal::query()->orderBy('name',  'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $sucursales = $query->paginate($perPage);

        return inertia('admin/sucursalesAdmin', [
            'sucursales' => $sucursales,
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Sucursal::create($request->all());
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $sucursal = Sucursal::findOrFail($request->id);
        $sucursal->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $sucursal = Sucursal::findOrFail($request->id);

        // Verificar si la sucursal tiene clientes asociados
        if ($sucursal->clientes()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la sucursal porque tiene clientes asociados.'
            ], 400);
        }

        $sucursal->delete();
    }
}
