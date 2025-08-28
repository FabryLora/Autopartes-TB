<?php

namespace App\Http\Controllers;

use App\Models\ListaDePrecios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListaDePreciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Esto devuelve una colección de modelos, no un solo modelo
        $listaDePrecios = ListaDePrecios::where('id', auth()->user()->lista_de_precios_id)->get();

        // Mapea la colección para añadir los atributos formato y peso a cada elemento
        $listaDePrecios = $listaDePrecios->map(function ($item) {
            // Asumiendo que has implementado estos métodos en tu modelo
            $item->formato = $item->getFormatoArchivo();
            $item->peso = $item->getPesoArchivo();
            return $item;
        });

        return inertia('privada/listadeprecios', [
            'listaDePrecios' => $listaDePrecios
        ]);
    }

    public function indexAdmin()
    {
        $listaDePrecios = ListaDePrecios::all();
        return inertia('admin/listadepreciosAdmin', [
            'listaDePrecios' => $listaDePrecios
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',

            'archivo' => 'required|file'
        ]);

        if ($request->hasFile('archivo')) {
            $imagePath = $request->file('archivo')->store('images', 'public');
            $data['archivo'] = $imagePath;
        }

        ListaDePrecios::create($data);

        return redirect()->back()->with('success', 'Lista de precios creada correctamente.');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $listaDePrecios = ListaDePrecios::findOrFail($request->id);
        if (!$listaDePrecios) {
            return redirect()->back()->with('error', 'No se encontró la lista de precios.');
        }

        $data = $request->validate([
            'name' => 'required',

            'archivo' => 'sometimes|file'
        ]);

        if ($request->hasFile('archivo')) {
            if ($listaDePrecios->getRawOriginal('archivo')) {
                Storage::disk('public')->delete($listaDePrecios->getRawOriginal('archivo'));
            }
            $data['archivo'] = $request->file('archivo')->store('images', 'public');
        }

        $listaDePrecios->update($data);

        return redirect()->route('admin.listadeprecios');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $listaDePrecios = ListaDePrecios::findOrFail($request->id);
        if (!$listaDePrecios) {
            return redirect()->back()->with('error', 'No se encontró la lista de precios.');
        }

        // Delete the file if it exists
        if ($listaDePrecios->archivo) {
            Storage::disk('public')->delete($listaDePrecios->archivo);
        }

        $listaDePrecios->delete();

        return redirect()->route('admin.listadeprecios');
    }
}
