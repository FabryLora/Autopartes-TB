<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{

    public function __construct()
    {
        // Restaurar carrito al inicializar si hay usuario logueado
        if (Auth::check()) {
            Cart::restore(Auth::id());
        }
    }

    public function index()
    {
        $cartItems = Cart::content();
        $cartTotal = Cart::total();
        $cartCount = Cart::count();

        return view('cart.index', compact('cartItems', 'cartTotal', 'cartCount'));
    }
    public function addtocart(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        Cart::add(
            $request->id,
            $request->name,
            $request->qty,
            $request->price,
            0
        );

        // Guardar en base de datos si hay usuario logueado
        if (Auth::check()) {
            Cart::store(Auth::id());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);
        if ($request->rowId) {
            Cart::update($request->rowId, $request->qty);
        }



        // Guardar cambios en base de datos
        if (Auth::check()) {
            Cart::store(Auth::id());
        }

        return redirect()->back()->with('success', 'Carrito actualizado correctamente');
    }

    public function remove(Request $request)
    {
        Cart::remove($request->rowId);

        // Guardar cambios en base de datos
        if (Auth::check()) {
            Cart::store(Auth::id());
        }

        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }

    public function destroy()
    {
        Cart::destroy();

        // Eliminar de base de datos
        if (Auth::check()) {
            Cart::erase(Auth::id());
        }

        return redirect()->back()->with('success', 'Carrito vaciado completamente');
    }

    public function saveCart()
    {
        if (Auth::check()) {
            Cart::store(Auth::id());
            return response()->json(['success' => true, 'message' => 'Carrito guardado']);
        }

        return response()->json(['success' => false, 'message' => 'Usuario no autenticado']);
    }

    public function compraRapida(Request $request)
    {


        $producto = Producto::where('code', $request->code)->with('precio')->first();



        Cart::add(
            $producto->id,
            $producto->name,
            $request->qty,
            $producto->precio->precio, // Asegurarse de que el precio sea correcto
            0
        );

        // Guardar en base de datos si hay usuario logueado
        if (Auth::check()) {
            Cart::store(Auth::id());
        }
    }
}
