<?php

namespace App\Jobs;


use App\Models\ListaProductos;
use App\Models\Producto;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ActualizarPreciosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $archivoPath;
    protected $lista_id;

    public function __construct($archivoPath, $lista_id)
    {
        $this->archivoPath = $archivoPath;
        $this->lista_id = $lista_id;
    }

    public function handle()
    {
        $filePath = Storage::disk('public')->path($this->archivoPath);
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        Log::info('=== INICIO DEBUG EXCEL ===');
        Log::info('Total de filas: ' . count($rows));
        Log::info(message: 'Lista ID: ' . $this->lista_id);

        foreach ($rows as $index => $row) {
            Log::info("Fila {$index}: " . json_encode($row));

            if ($index === 0) {
                Log::info('Saltando encabezado');
                continue;
            }

            if (empty($row) || !isset($row['A']) || !isset($row['B'])) {
                Log::info("Fila {$index} vacía o incompleta");
                continue;
            }

            $codigo = trim($row['A']);
            $precio = trim($row['B']);

            Log::info("Procesando - Código: '{$codigo}', Precio: '{$precio}'");

            $producto = Producto::where('code', $codigo)->first();

            if (!$producto) {
                Log::warning("Producto no encontrado con código: {$codigo}");
                continue;
            }

            Log::info("Producto encontrado - ID: {$producto->id}, Código: {$producto->codigo}");

            $result = ListaProductos::updateOrCreate(
                [
                    'lista_de_precios_id' => $this->lista_id,
                    'producto_id' => $producto->id
                ],
                [
                    'precio' => $precio
                ]
            );

            Log::info("Registro creado/actualizado - ID: {$result->id}");
        }

        Log::info('=== FIN DEBUG EXCEL ===');
    }
}
