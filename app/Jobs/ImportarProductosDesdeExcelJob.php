<?php

namespace App\Jobs;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\SubCategoria;
use App\Models\SubProducto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class ImportarProductosDesdeExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function handle()
    {


        $spreadsheet = IOFactory::load(storage_path("app/" . $this->path));
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Saltar encabezado

            $modelo = trim($row[1]);
            $marca = trim($row[3]);


            $marcas = Categoria::firstOrCreate(
                ['name' => $marca],
                ['name' => $marca]
            );

            SubCategoria::firstOrCreate(
                ['name' => $modelo],
                ['name' => $modelo, 'categoria_id' => $marcas->id]
            );
        }

        Log::info("Importación de productos y subproductos desde CSV completada.");
    }
}
