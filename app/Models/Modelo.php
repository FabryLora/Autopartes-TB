<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $guarded = [];

    public function producto()
    {
        return $this->belongsTo(Producto::class)->orderBy('order', 'asc');
    }

    public function marca()
    {
        return $this->belongsTo(MarcaProducto::class)->orderBy('order', 'asc');
    }
}
