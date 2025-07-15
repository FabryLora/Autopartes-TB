<?php

namespace App\Http\Controllers;

use App\Models\ListaDePrecios;
use App\Models\Provincia;
use App\Models\Sucursal;
use App\Models\SucursalCliente;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 10);

        $query = User::query()->where('rol', 'cliente')->with(['vendedor', 'sucursales'])->orderBy('name', 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $sucursales = Sucursal::orderBy('name', 'asc')->get();

        $users = $query->paginate($perPage);
        $listas = ListaDePrecios::select('id', 'name')->orderBy('name', 'asc')->get();
        $provincias = Provincia::orderBy('name', 'asc')->with('localidades')->get();

        $vendedores = User::where('rol', 'vendedor')->orderBy('name', 'asc')->get();


        return inertia('admin/clientes', [
            'clientes' => $users,
            'provincias' => $provincias,
            'vendedores' => $vendedores,
            'listas' => $listas,
            'sucursales' => $sucursales,
        ]);
    }

    public function changeStatus(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update(['autorizado' => !$user->autorizado]);
    }

    public function vendedores(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = User::query()
            ->where('rol', 'vendedor')
            ->orderBy('name', 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $vendedores = $query->paginate($perPage);
        $provincias = Provincia::orderBy('name', 'asc')->with('localidades')->get();

        return inertia('admin/vendedores', [
            'vendedores' => $vendedores,
            'provincias' => $provincias,
        ]);
    }

    public function update(Request $request)
    {


        $user = User::findOrFail($request->id);

        $datos = $request->validate(
            [
                'sucursales' => 'nullable|array',
                'sucursales.*' => 'exists:sucursals,id',
            ]
        );

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'email_dos' => 'nullable|sometimes|string|email|max:255',
            'email_tres' => 'nullable|sometimes|string|email|max:255',
            'email_cuatro' => 'nullable|sometimes|string|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'razon_social' => 'nullable|sometimes|string|max:255',
            'cuit' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'localidad' => 'nullable|string|max:255',
            'descuento_uno' => 'nullable|integer|min:0|max:100',
            'descuento_dos' => 'nullable|integer|min:0|max:100',
            'descuento_tres' => 'nullable|integer|min:0|max:100',
            'rol' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'lista_de_precios_id' => 'sometimes|exists:lista_de_precios,id',
            'autorizado' => 'nullable|boolean',
            'vendedor_id' => 'nullable|sometimes|exists:users,id',

        ]);


        if ($request->has('sucursales')) {
            foreach ($datos['sucursales'] as $sucursal) {
                SucursalCliente::create([
                    'user_id' => $user->id,
                    'sucursal_id' => $sucursal,
                ]);
            }
        }

        $user->update($data);
    }

    public function destroy(Request $request)
    {

        $user = User::findOrFail($request->id);
        $user->delete();
    }
}
