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

class ImportarClientesJob implements ShouldQueue
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

            if ($index === 0 || trim($row['S']) == 'LISTA') {
                Log::info('Saltando encabezado');
                continue;
            }

            $razon = trim($row['C']);
            $email_dos = trim($row['D']);
            $email_tres = trim($row['E']);
            $email_cuatro = trim($row['F']);
            $email = trim($row['G']);
            $name = trim($row['H']);
            $direccion = trim($row['I']);
            $cuit = trim($row['J']);
            $telefono = trim($row['K']);
            $provincia = trim($row['L']);
            $localidad = trim($row['M']);
            $descuento_uno = trim($row['N']);
            $descuento_dos = trim($row['O']);
            $descuento_tres = trim($row['P']);
            $sucursales = trim($row['Q']);
            $vendedor = trim($row['R']);
            $lista = trim($row['S']);
            $activo = trim($row['T']);

            $lista_id = ListaDePrecios::where('name', $lista)->value('id');

            if (!empty($vendedor)) {
                $vendedor_record = User::where('name', $vendedor)->first();
                $vendedor_id = $vendedor_record ? $vendedor_record->id : null;
            }

            $user = User::updateOrCreate([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt(trim($cuit)),
                'email_dos' => $email_dos,
                'email_tres' => $email_tres,
                'email_cuatro' => $email_cuatro,
                'razon_social' => $razon,
                'cuit' => $cuit,
                'direccion' => $direccion,
                'provincia' => $provincia,
                'localidad' => $localidad,
                'telefono' => $telefono,
                'descuento_uno' => $descuento_uno,
                'descuento_dos' => $descuento_dos,
                'descuento_tres' => $descuento_tres,
                'lista_de_precios_id' => $lista ? $lista_id : null,
                'vendedor_id' => $vendedor ? $vendedor_id : null,
                'rol' => 'cliente',
                'autorizado' => true,

            ]);

            #sucursales es un string donde estan los nombres de las sucursales separados por una coma
            if (!empty($sucursales)) {
                $sucursales_array = array_filter(array_map('trim', explode(',', $sucursales)));
                foreach ($sucursales_array as $sucursal) {
                    if (empty($sucursal)) continue;

                    $sucursal_selected = Sucursal::where('name', $sucursal)->first();
                    if ($sucursal_selected) {
                        SucursalCliente::firstOrCreate([
                            'user_id' => $user->id,
                            'sucursal_id' => $sucursal_selected->id
                        ]);
                    }
                }
            };
        }
    }
}
