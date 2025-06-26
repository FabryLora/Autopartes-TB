<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\SubCategoria;
use Illuminate\Http\Request;

class SubCategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 10);

        $query = SubCategoria::query()->with('categoria')->orderBy('order', 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $subCategorias = $query->paginate($perPage);

        $categorias = Categoria::orderBy('order')->get();

        return inertia('admin/subcategoriasAdmin', [
            'subCategorias' => $subCategorias,
            'categorias' => $categorias,
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|string|max:255',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        SubCategoria::create($request->all());

        return redirect()->back()->with('success', 'Subcategoría creada exitosamente.');
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|sometimes|string|max:255',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        $subCategoria = SubCategoria::findOrFail($request->id);
        $subCategoria->update($request->all());

        return redirect()->back()->with('success', 'Subcategoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $subCategoria = SubCategoria::findOrFail($request->id);
        $subCategoria->delete();

        return redirect()->back()->with('success', 'Subcategoría eliminada exitosamente.');
    }
}
