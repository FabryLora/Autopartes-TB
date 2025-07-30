<?php

namespace App\Jobs;

use App\Models\Categoria;
use App\Models\ListaProductos;
use App\Models\Oferta;
use App\Models\Producto;
use App\Models\ProductoMarca;
use App\Models\ProductoModelo;
use App\Models\SubCategoria;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportarMarcasYModelosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $archivoPath;


    public function __construct($archivoPath)
    {
        $this->archivoPath = $archivoPath;
    }

    public function handle()
    {
        $filePath = Storage::path($this->archivoPath);
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        Log::info('=== INICIO DEBUG EXCEL ===');
        Log::info('Total de filas: ' . count($rows));

        foreach ($rows as $index => $row) {

            if ($index === 0 || trim($row['E']) == 'CODIGO TB') {
                Log::info('Saltando encabezado');
                continue;
            }

            $modelo = trim($row['B']);
            $marca = trim($row['D']);
            $codigo = trim($row['E']);

            if (!empty($codigo)) {
                $prod = Producto::where('code', $codigo)->first();
            }


            if (empty($marca) || empty($modelo)) {
                Log::warning("Marca o modelo vacío en la fila {$index}");
                continue;
            }

            $categoria = Categoria::firstOrCreate(
                ['name' => $marca]
            );

            $subcategoria = SubCategoria::firstOrCreate(
                ['name' => $modelo],
                ['categoria_id' => $categoria->id]
            );



            if ($prod) {
                ProductoMarca::firstOrCreate(
                    [
                        'producto_id' => $prod->id,
                        'categoria_id' => $categoria->id
                    ]
                );

                ProductoModelo::firstOrCreate(
                    [
                        'producto_id' => $prod->id,
                        'sub_categoria_id' => $subcategoria->id
                    ]
                );
            }
        }

        Log::info('=== FIN DEBUG EXCEL ===');
    }
}
