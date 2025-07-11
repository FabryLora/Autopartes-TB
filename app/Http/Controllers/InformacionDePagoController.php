<?php

namespace App\Http\Controllers;

use App\Models\InformacionDePago;
use Illuminate\Http\Request;

class InformacionDePagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $informacionDePago = InformacionDePago::first();


        return inertia('admin/informacionDePago', [
            'informacion' => $informacionDePago,
        ]);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'informacion' => 'required|string',
        ]);

        $informacionDePago = InformacionDePago::first();
        if ($informacionDePago) {
            $informacionDePago->update($request->only('informacion'));
        } else {
            InformacionDePago::create($request->only('informacion'));
        }
    }
}
