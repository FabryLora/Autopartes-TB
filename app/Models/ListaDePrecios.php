<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ListaDePrecios extends Model
{
    protected $guarded = [];

    // Si el archivo se guarda como una ruta
    public function getArchivoAttribute($value)
    {
        return asset("storage/" . $value);
    }

    public function getFormatoArchivo()
    {
        // Usar getOriginal() para obtener el valor sin procesar por el accessor
        $archivoOriginal = $this->getOriginal('archivo');

        if (empty($archivoOriginal)) {
            return null;
        }

        // Obtener la extensión del archivo
        $extension = pathinfo($archivoOriginal, PATHINFO_EXTENSION);
        return $extension;
    }

    public function getPesoArchivo()
    {
        // Usar getOriginal() para obtener el valor sin procesar por el accessor
        $archivoOriginal = $this->getOriginal('archivo');

        if (empty($archivoOriginal)) {
            return null;
        }

        // Verificar si el archivo existe en el almacenamiento
        if (Storage::exists($archivoOriginal)) {
            // Obtener el tamaño en bytes
            $bytes = Storage::size($archivoOriginal);

            // Convertir a KB, MB, etc.
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            $bytes /= (1 << (10 * $pow));

            return round($bytes, 2) . ' ' . $units[$pow];
        }

        return null;
    }

    // Método adicional para obtener solo la ruta del archivo sin la URL completa
    public function getRutaArchivoAttribute()
    {
        return $this->getOriginal('archivo');
    }

    public function productos()
    {
        return $this->hasMany(ListaProductos::class, 'lista_de_precios_id');
    }

    public function clientes()
    {
        return $this->hasMany(User::class, 'lista_de_precios_id');
    }
}
