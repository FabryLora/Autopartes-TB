<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoMarca extends Model
{
    protected $guarded = [];

    public function producto()
    {
        return $this->belongsTo(Producto::class)->orderBy('order', 'asc');
    }

    public function marca()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id')->orderBy('order', 'asc');
    }
}
