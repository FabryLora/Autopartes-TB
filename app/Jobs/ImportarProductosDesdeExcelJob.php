<?php

namespace App\Jobs;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoMarca;
use App\Models\ProductoModelo;
use App\Models\SubCategoria;
use App\Models\SubProducto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportarProductosDesdeExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $archivoPath;

    public function __construct($archivoPath)
    {
        $this->archivoPath = $archivoPath;
    }

    public function handle()
    {
        try {
            $filePath = Storage::path($this->archivoPath);

            // Verificar que el archivo existe
            if (!file_exists($filePath)) {
                Log::error("Archivo no encontrado: " . $filePath);
                return;
            }

            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Obtener información del sheet
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            Log::info("Procesando Excel - Filas: {$highestRow}, Columnas: {$highestColumn}");

            // Verificar que hay datos
            if ($highestRow < 2) {
                Log::warning("El archivo no tiene datos para procesar (solo tiene {$highestRow} fila(s))");
                return;
            }

            $rows = $sheet->toArray(null, true, true, true);

            // Debug: mostrar estructura del array
            Log::info("Estructura del array rows:", [
                'total_rows' => count($rows),
                'keys' => array_keys($rows),
                'first_few_keys' => array_slice(array_keys($rows), 0, 3)
            ]);

            // Verificar que tenemos filas
            if (empty($rows)) {
                Log::error("El array de filas está vacío");
                return;
            }

            // SOLUCIÓN MEJORADA: Usar array_values para reindexar y luego slice
            $rowsReindexed = array_values($rows);
            $dataRows = array_slice($rowsReindexed, 1);

            Log::info("Procesando " . count($dataRows) . " filas de datos");

            foreach ($dataRows as $index => $row) {
                try {
                    $this->procesarFila($row, $index + 2); // +2 porque empezamos desde fila 2 del Excel
                } catch (\Exception $e) {
                    Log::error("Error procesando fila " . ($index + 2) . ": " . $e->getMessage());
                    continue; // Continuar con la siguiente fila
                }
            }
        } catch (\Exception $e) {
            Log::error("Error general en ImportarProductosDesdeExcelJob: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }

        // SOLUCIÓN 2 ALTERNATIVA: Usar contador manual
        /*
        $contador = 0;
        foreach ($rows as $row) {
            $contador++;
            if ($contador === 1) continue; // Saltar encabezado
            $this->procesarFila($row);
        }
        */

        // SOLUCIÓN 3 ALTERNATIVA: Usar array_keys para obtener las claves reales
        /*
        $rowKeys = array_keys($rows);
        foreach ($rowKeys as $key) {
            if ($key === $rowKeys[0]) continue; // Saltar primera fila
            $this->procesarFila($rows[$key]);
        }
        */

        // SOLUCIÓN 4 ALTERNATIVA: Usar getHighestRow() para iterar manualmente
        /*
        $highestRow = $sheet->getHighestRow();
        for ($rowIndex = 2; $rowIndex <= $highestRow; $rowIndex++) {
            $row = [];
            foreach (range('A', 'N') as $column) {
                $row[$column] = $sheet->getCell($column . $rowIndex)->getValue();
            }
            $this->procesarFila($row);
        }
        */
    }

    private function procesarFila($row, $numeroFila = null)
    {
        // Validar que la fila tiene la estructura esperada
        if (!is_array($row)) {
            Log::warning("Fila {$numeroFila}: No es un array válido");
            return;
        }

        // Verificar que las columnas necesarias existen
        $columnasRequeridas = ['B', 'C'];
        foreach ($columnasRequeridas as $columna) {
            if (!isset($row[$columna])) {
                Log::warning("Fila {$numeroFila}: Columna {$columna} no encontrada");
                return;
            }
        }

        // Extraer datos con valores por defecto
        $codigo = isset($row['B']) ? trim($row['B']) : '';
        $nombre = isset($row['C']) ? trim($row['C']) : '';
        $descripcion_visible = isset($row['D']) ? trim($row['D']) : '';
        $desc_invisible = isset($row['E']) ? trim($row['E']) : '';
        $unidad_pack = isset($row['F']) ? trim($row['F']) : '';
        $codigo_oem = isset($row['G']) ? trim($row['G']) : '';
        $codigo_competidor = isset($row['H']) ? trim($row['H']) : '';
        $codigo_competidor_dos = isset($row['I']) ? trim($row['I']) : '';
        $codigo_competidor_tres = isset($row['J']) ? trim($row['J']) : '';
        $codigo_competidor_cuatro = isset($row['K']) ? trim($row['K']) : '';
        $codigo_competidor_cinco = isset($row['L']) ? trim($row['L']) : '';
        $codigo_competidor_seis = isset($row['M']) ? trim($row['M']) : '';
        $codigo_competidor_siete = isset($row['N']) ? trim($row['N']) : '';
        $stock = isset($row['O']) ? trim($row['O']) : 0;
        $descuento_oferta = isset($row['V']) ? trim($row['V']) : 0;

        // Validar que el código no esté vacío
        if (empty($codigo)) {
            Log::warning("Fila {$numeroFila}: Código vacío, saltando");
            return;
        }


        try {
            $producto = Producto::updateOrCreate(
                ['code' => $codigo],
                [
                    'name' => $nombre,
                    'desc_visible' => $descripcion_visible,
                    'desc_invisible' => $desc_invisible,
                    'unidad_pack' => $unidad_pack ?? null,
                    'code_oem' => $codigo_oem,
                    'code_competitor' => $codigo_competidor,
                    'code_competitor_dos' => $codigo_competidor_dos,
                    'code_competitor_tres' => $codigo_competidor_tres,
                    'code_competitor_cuatro' => $codigo_competidor_cuatro,
                    'code_competitor_cinco' => $codigo_competidor_cinco,
                    'code_competitor_seis' => $codigo_competidor_seis,
                    'code_competitor_siete' => $codigo_competidor_siete,
                    'stock' => is_numeric($stock) ? $stock : 0,
                    'descuento_oferta' => is_numeric($descuento_oferta) ? $descuento_oferta : 0
                ]
            );




            Log::info("Producto procesado exitosamente: {$codigo} (Fila {$numeroFila})");
        } catch (\Exception $e) {
            Log::error("Error procesando producto {$codigo} (Fila {$numeroFila}): " . $e->getMessage());
            throw $e;
        }
    }
}
