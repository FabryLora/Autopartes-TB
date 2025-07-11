<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 10);

        $query = Categoria::query()->orderBy('order', direction: 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $categorias = $query->paginate($perPage);



        return Inertia::render('admin/categoriasAdmin', [
            'categorias' => $categorias,
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order' => 'nullable|sometimes|string',
            'name' => 'required|string|max:255',
        ]);


        // Create the category
        Categoria::create($data);

        return redirect()->back()->with('success', 'Category created successfully.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $categoria = Categoria::findOrFail($request->id);

        // Check if the category entry exists
        if (!$categoria) {
            return redirect()->back()->with('error', 'Category not found.');
        }

        $data = $request->validate([
            'order' => 'sometimes|string',
            'name' => 'sometimes|string|max:255',
        ]);



        // Update the category
        $categoria->update($data);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $categoria = Categoria::findOrFail($request->id);

        // Check if the category entry exists
        if (!$categoria) {
            return redirect()->back()->with('error', 'Category not found.');
        }



        // Delete the category
        $categoria->delete();

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
