<?php

namespace App\Jobs;


use App\Models\ListaProductos;
use App\Models\Oferta;
use App\Models\Producto;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportarOfertasJob implements ShouldQueue
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

            if ($index === 0) {
                Log::info('Saltando encabezado');
                continue;
            }

            $cliente = trim($row['A']);
            $producto = trim($row['B']);
            $fecha_fin = trim($row['C']);

            $user = User::where('name', $cliente)->first();
            $prod = Producto::where('code', $producto)->first();

            if (!$user) {
                Log::warning("Usuario no encontrado: {$cliente}");
                continue;
            }

            if (!$prod) {
                Log::warning("Producto no encontrado: {$producto}");
                continue;
            }

            Oferta::updateOrCreate([
                'user_id' => $user->id,
                'producto_id' => $prod->id,
            ], [
                'fecha_fin' => $fecha_fin,
            ]);
        }

        Log::info('=== FIN DEBUG EXCEL ===');
    }
}
