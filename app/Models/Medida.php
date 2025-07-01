<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medida extends Model
{
    protected $guarded = [];

    public function productos()
    {
        return $this->hasMany(Producto::class)->orderBy('order');
    }
}
