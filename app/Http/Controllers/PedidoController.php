<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Producto;
use App\Models\SubProducto;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class PedidoController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable | exists:users,id',
            'tipo_entrega' => 'nullable | string',
            'mensaje' => 'sometimes | string',
            'archivo' => 'sometimes | file',
            'subtotal' => 'nullable | numeric',
            'iva' => 'nullable | numeric',
            'iibb' => 'nullable | numeric',
            'total' => 'nullable | numeric',
            'entregado' => 'nullable | boolean',
        ]);

        // Guardar la nueva imagen
        if ($request->hasFile('archivo')) {
            $archivoPath = $request->file('archivo')->store('files', 'public');
            $data["archivo"] = $archivoPath;
        }

        $pedido = Pedido::create($data);

        return redirect()->back()->with([
            'pedido_id' => $pedido->id,
            'message' => 'Pedido creado exitosamente'
        ]);
    }

    public function misPedidos()
    {
        $pedidos = Pedido::where('user_id', auth()->user()->id)
            ->with(['productos.producto']) // Ajusta los campos según necesites
            ->orderBy('created_at', 'desc')
            ->get();



        return inertia('privada/mispedidos', [
            'pedidos' => $pedidos,
        ]);
    }

    public function cambiarEstado(Request $request)
    {
        $pedido = Pedido::find($request->id);


        // Cambiar el estado del pedido
        $pedido->estado = $request->estado;
        $pedido->save();
    }

    public function recomprar(Request $request)
    {
        $productos_pedidos = PedidoProducto::where('pedido_id', $request->pedido_id)
            ->get();

        foreach ($productos_pedidos as $producto_pedido) {
            $producto = Producto::find($producto_pedido->producto_id);
            Cart::add(
                $producto->id,
                $producto->name,
                $producto_pedido->cantidad,
                $producto->precio->precio,
                0,
            );
        }
    }

    public function misPedidosAdmin(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Pedido::query()->with(['productos.producto', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('id',  $searchTerm);
        }

        $subProductos = SubProducto::with(['producto' => function ($query) {
            $query->select('id', 'name', 'marca_id')->with('marca');
        }])
            ->get();;

        $pedidos = $query->paginate($perPage);
        return inertia('admin/pedidosAdmin', [
            'pedidos' => $pedidos,
            'subProductos' => $subProductos,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $pedido = Pedido::find($request->id);

        if (!$pedido) {
            return redirect()->back()->with('error', 'Pedido not found.');
        }

        // Toggle the current value of entregado
        $pedido->entregado = !$pedido->entregado;
        $pedido->save();

        return redirect()->back()->with('success', 'Pedido updated successfully.');
    }
}
