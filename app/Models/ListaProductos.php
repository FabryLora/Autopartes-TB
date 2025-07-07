<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaProductos extends Model
{
    protected $guarded = [];

    /**
     * Relación con ListaDePrecios
     */
    public function listaDePrecios()
    {
        return $this->belongsTo(ListaDePrecios::class);
    }

    /**
     * Relación con Producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
