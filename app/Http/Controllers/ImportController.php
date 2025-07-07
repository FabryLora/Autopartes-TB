<?php

namespace App\Http\Controllers;

use App\Jobs\ActualizarPreciosJob;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function importar(Request $request)
    {

        // Guardar archivo en almacenamiento temporal
        $archivoPath = str_replace('/storage/', '', parse_url($request->path, PHP_URL_PATH));

        $lista_id = $request->lista_id;


        // Encolar el Job
        ActualizarPreciosJob::dispatch($archivoPath, $lista_id);
    }
}
