<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => "required|string|max:255",
            'email' => "required|string|email|max:255|unique:users,email",
            "password" => "required|confirmed|string|min:8",
            'cuit' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'localidad' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'descuento_uno' => 'nullable|sometimes|integer|min:0|max:100',
            'descuento_dos' => 'nullable|sometimes|integer|min:0|max:100',
            'descuento_tres' => 'nullable|sometimes|integer|min:0|max:100',
            'rol' => 'nullable|string|max:255', // Optional role, default is 'cliente'
            'lista_de_precios_id' => 'nullable|sometimes|exists:lista_de_precios,id',
            'vendedor_id' => 'nullable|sometimes|exists:users,id',
            'autorizado' => 'nullable|boolean'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cuit' => $request->cuit,
            'direccion' => $request->direccion,
            'provincia' => $request->provincia,
            'localidad' => $request->localidad,
            'telefono' => $request->telefono,
            'descuento_uno' => $request->descuento_uno || 0,
            'descuento_dos' => $request->descuento_dos || 0,
            'descuento_tres' => $request->descuento_tres || 0,
            'rol' => $request->rol,
            'vendedor_id' => $request->vendedor_id,
            'autorizado' => $request->autorizado || false,
            'lista_de_precios_id' => $request->lista_de_precios_id,
            'password' => Hash::make($request->password),
        ]);
    }
}
