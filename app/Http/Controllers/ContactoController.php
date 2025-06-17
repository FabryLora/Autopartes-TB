<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacto = Contacto::first();

        return inertia('admin/contactoAdmin', [
            'contacto' => $contacto,

        ]);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $contacto = Contacto::first();


        $data = $request->validate([

            'phone' => 'sometimes|string|max:255',
            'mail' => 'sometimes|email|max:255',
            'location' => 'sometimes|string|max:255',
            'fb' => 'sometimes|string|max:255',
            'ig' => 'sometimes|string|max:255',
            'wp' => 'sometimes|string|max:255',
        ]);

        if (!$contacto) {
            $contacto = Contacto::create($data);
            return redirect()->back()->with('success', 'Contacto created successfully.');
        } else {
            $contacto->update($data);
        }



        return redirect()->back()->with('success', 'Contacto updated successfully.');
    }
}
