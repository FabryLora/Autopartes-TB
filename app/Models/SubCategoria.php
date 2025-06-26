<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategoria extends Model
{
    protected $guarded = [];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
