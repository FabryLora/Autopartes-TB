<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\InformacionImportante;
use App\Models\Producto;
use App\Models\SubCategoria;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class PrivadaController extends Controller
{
    public function carrito()
    {
        $contacto = Contacto::first();
        $informacion = InformacionImportante::first();
        $carrito = Cart::content();

        // Extraer los IDs de los productos del carrito
        $productosIds = $carrito->pluck('id')->toArray();

        // Traer todos los productos con esos IDs
        $productos = Producto::whereIn('id', $productosIds)->with(['imagenes', 'marcas', 'modelos', 'precio'])->get();

        $productosConRowId = $productos->map(function ($producto) use ($carrito) {
            // Buscar el item del carrito que corresponde a este producto
            $itemCarrito = $carrito->where('id', $producto->id)->first();

            // Agregar el rowId al producto
            $producto->rowId = $itemCarrito ? $itemCarrito->rowId : null;
            $producto->qty = $itemCarrito ? $itemCarrito->qty : null;
            $producto->subtotal = $itemCarrito->price * ($itemCarrito->qty ?? 1);

            return $producto;
        });

        // Calcular el subtotal total del carrito
        $subtotalTotal = $productosConRowId->sum('subtotal');

        // Obtener los descuentos del usuario logueado
        $descuento_uno = auth()->user()->descuento_uno ?? 0;
        $descuento_dos = auth()->user()->descuento_dos ?? 0;
        $descuento_tres = auth()->user()->descuento_tres ?? 0;

        // Calcular subtotal con descuentos aplicados en orden
        $subtotal_descuento = $subtotalTotal;

        // Aplicar descuento_uno si es mayor a 0
        if ($descuento_uno > 0) {
            $subtotal_descuento = $subtotal_descuento * (1 - ($descuento_uno / 100));
        }

        // Aplicar descuento_dos si es mayor a 0
        if ($descuento_dos > 0) {
            $subtotal_descuento = $subtotal_descuento * (1 - ($descuento_dos / 100));
        }

        // Aplicar descuento_tres si es mayor a 0
        if ($descuento_tres > 0) {
            $subtotal_descuento = $subtotal_descuento * (1 - ($descuento_tres / 100));
        }

        // Calcular el IVA (21% del subtotal con descuentos)
        $iva = $subtotal_descuento * 0.21;

        $total = $subtotal_descuento + $iva;

        $categorias = Categoria::orderBy('order', 'asc')->get();
        $subcategorias = SubCategoria::orderBy('order', 'asc')->get();

        return inertia('privada/carrito', [
            'informacion' => $informacion,
            'contacto' => $contacto,
            'carrito' => $carrito,
            'productos' => $productosConRowId,
            'subtotal' => $subtotalTotal,
            'descuento_uno' => $descuento_uno,
            'descuento_dos' => $descuento_dos,
            'descuento_tres' => $descuento_tres,
            'subtotal_descuento' => $subtotal_descuento,
            'iva' => $iva,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
            'total' => $total,
        ]);
    }


    public function carritoAdmin()
    {

        $informacion = InformacionImportante::first();

        return inertia('admin/carritoAdmin', [
            'informacion' => $informacion,
        ]);
    }
}
