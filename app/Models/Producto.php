<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $guarded = [];
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function subcategoria()
    {
        return $this->belongsTo(SubCategoria::class, 'sub_categoria_id');
    }

    public function modelos()
    {
        return $this->hasMany(ProductoModelo::class);
    }
    public function marcas()
    {
        return $this->hasMany(ProductoMarca::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenProducto::class, 'producto_id');
    }

    public function subproductos()
    {
        return $this->hasMany(SubProducto::class);
    }

    public function getImageAttribute($value)
    {
        return url("storage/" . $value);
    }

    public function precio()
    {
        return $this->hasOne(ListaProductos::class, 'producto_id')
            ->where('lista_de_precios_id', auth()->user()->listaDePrecio->id ?? null);
    }
}
