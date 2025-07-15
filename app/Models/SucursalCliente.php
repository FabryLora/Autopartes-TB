<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SucursalCliente extends Model
{
    protected $guarded = [];

    /**
     * Get the sucursal that owns the SucursalCliente.
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
    /**
     * Get the user that owns the SucursalCliente.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
