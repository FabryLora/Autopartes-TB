<?php

namespace App\Jobs;

use App\Models\ListaDePrecios;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\SucursalCliente;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportarVendedoresJob implements ShouldQueue
{
    use Queueable;
    protected $archivoPath;
    /**
     * Create a new job instance.
     */
    public function __construct($archivoPath)
    {
        $this->archivoPath = $archivoPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filePath = Storage::path($this->archivoPath);
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        Log::info('=== INICIO DEBUG EXCEL ===');
        Log::info('Total de filas: ' . count($rows));


        foreach ($rows as $index => $row) {

            if ($index === 0 || trim($row['B']) == 'NOMBRE') {
                Log::info('Saltando encabezado');
                continue;
            }

            $name = trim($row['B']);
            $cuit = trim($row['C']);
            $email = trim($row['D']);
            $direccion = trim($row['E']);
            $telefono = trim($row['F']);
            $provincia = trim($row['G']);
            $localidad = trim($row['H']);



            User::updateOrCreate([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($cuit),
                'cuit' => $cuit,
                'direccion' => $direccion,
                'provincia' => $provincia,
                'localidad' => $localidad,
                'telefono' => $telefono,
                'rol' => 'vendedor',
                'autorizado' => true
            ]);
        }
    }
}
