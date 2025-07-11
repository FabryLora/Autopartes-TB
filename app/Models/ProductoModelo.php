<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoModelo extends Model
{
    protected $guarded = [];

    public function producto()
    {
        return $this->belongsTo(Producto::class)->orderBy('order', 'asc');
    }

    public function subCategoria()
    {
        return $this->belongsTo(SubCategoria::class)->orderBy('order', 'asc');
    }

    public function modelo()
    {
        return $this->belongsTo(SubCategoria::class, 'sub_categoria_id')->orderBy('order', 'asc');
    }
}
