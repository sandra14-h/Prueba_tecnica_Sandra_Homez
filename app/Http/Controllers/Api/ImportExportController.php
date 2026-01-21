<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ImportExportController extends Controller
{
    public function export()
    {
        $fileName = 'productos.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Encabezados
            fputcsv($file, ['ID', 'Nombre', 'Descripción', 'Precio', 'Stock', 'Categoría']);

            Product::chunk(1000, function ($products) use ($file) {
                foreach ($products as $p) {
                    fputcsv($file, [
                        $p->id,
                        $p->nombre,
                        $p->descripcion,
                        $p->precio,
                        $p->stock,
                        $p->categoria
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        fgetcsv($file); // Saltar encabezado

        $batch = [];
        $batchSize = 500;
        $insertados = 0;
        $errores = [];

        while (($row = fgetcsv($file)) !== false) {

            if (count($row) < 5) {
                $errores[] = $row;
                continue;
            }

            [$nombre, $descripcion, $precio, $stock, $categoria] = $row;

            if (empty($nombre) || !is_numeric($precio) || !is_numeric($stock)) {
                $errores[] = $row;
                continue;
            }

            $batch[] = [
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'stock' => $stock,
                'categoria' => $categoria,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                Product::insert($batch);
                $insertados += count($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            Product::insert($batch);
            $insertados += count($batch);
        }

        fclose($file);

        if (!empty($errores)) {
            Log::warning('Filas fallidas en importación', [
                'total_fallidos' => count($errores),
                'detalle' => $errores // Esto guardará el contenido de las filas que no pasaron la validación
            ]);
        }
        
        Log::info('Importación productos finalizada', [
            'insertados' => $insertados,
            'errores' => count($errores)
        ]);

        return response()->json([
            'insertados' => $insertados,
            'errores' => count($errores)
        ]);
    }
}