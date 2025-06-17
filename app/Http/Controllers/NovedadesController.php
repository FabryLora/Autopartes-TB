<?php

namespace App\Http\Controllers;

use App\Models\Novedades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NovedadesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 10);

        $query = Novedades::query()->orderBy('order', 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $novedades = $query->paginate($perPage);


        return inertia('admin/novedadesAdmin', [
            'novedades' => $novedades,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order' => 'sometimes|nullable|string|max:255',
            'image' => 'required|file',
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'text' => 'required|string',
            'featured' => 'sometimes|boolean',
        ]);

        // Handle file upload if image exists
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }


        Novedades::create($data);

        return redirect()->back()->with('success', 'Novedad created successfully.');
    }

    public function changeFeatured(Request $request)
    {
        $novedad = Novedades::findOrFail($request->id);

        // Check if the Novedad entry exists
        if (!$novedad) {
            return redirect()->back()->with('error', 'Novedad not found.');
        }

        // Toggle the featured status
        $novedad->featured = !$novedad->featured;
        $novedad->save();

        return redirect()->back()->with('success', 'Novedad featured status updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $novedad = Novedades::findOrFail($request->id);

        // Check if the Novedad entry exists
        if (!$novedad) {
            return redirect()->back()->with('error', 'Novedad not found.');
        }

        $data = $request->validate([
            'order' => 'sometimes|nullable|string|max:255',
            'image' => 'sometimes|file',
            'title' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'text' => 'sometimes|string',
            'featured' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Guardar la ruta del archivo antiguo para eliminarlo después
            $oldImagePath = $novedad->getRawOriginal('image');

            // Guardar el nuevo archivo
            $data['image'] = $request->file('image')->store('images', 'public');

            // Eliminar el archivo antiguo si existe
            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
        }


        $novedad->update($data);

        return redirect()->back()->with('success', 'Novedad updated successfully.');
    }

    public function novedadesShow($id)
    {
        $novedad = Novedades::findOrFail($id);

        // Check if the Novedad entry exists
        if (!$novedad) {
            return redirect()->back()->with('error', 'Novedad not found.');
        }

        return view('novedad', [
            'novedad' => $novedad,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $novedad = Novedades::findOrFail($request->id);

        // Check if the Novedad entry exists
        if (!$novedad) {
            return redirect()->back()->with('error', 'Novedad not found.');
        }

        // Check if the image file exists and delete it
        $imagePath = $novedad->getRawOriginal('image');

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        $novedad->delete();

        return redirect()->back()->with('success', 'Novedad deleted successfully.');
    }
}
