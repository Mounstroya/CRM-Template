<?php

namespace App\Console\Commands;

use App\Models\Categoria;
use App\Models\Departamento;
use App\Models\Producto;
use Illuminate\Console\Command;

class BackfillProductoCategorias extends Command
{
    protected $signature = 'app:backfill-producto-categorias';

    protected $description = 'Backfill productos.categoria_id from database/seed-data/productos_categorias.csv '
        .'(real clave->categoria pulled from the still-live original site, since the Fase 1 import never '
        .'captured this relation). Real categorias are grouped under a single "General" departamento because '
        .'the real departamento-per-categoria grouping was not captured from the original system.';

    public function handle(): int
    {
        $departamento = Departamento::firstOrCreate(['departamento' => 'General']);

        $path = database_path('seed-data/productos_categorias.csv');
        $fh = fopen($path, 'r');
        $header = fgetcsv($fh);
        $rows = [];
        while (($row = fgetcsv($fh)) !== false) {
            $c = min(count($header), count($row));
            $rows[] = array_combine(array_slice($header, 0, $c), array_slice($row, 0, $c));
        }
        fclose($fh);

        $categoriaIds = [];
        $matched = 0;
        $unmatched = [];

        foreach ($rows as $r) {
            $nombre = trim($r['categoria'] ?? '');
            if ($nombre === '') {
                continue;
            }
            if (! isset($categoriaIds[$nombre])) {
                $categoria = Categoria::firstOrCreate([
                    'departamentos_id' => $departamento->id,
                    'categoria' => $nombre,
                ]);
                $categoriaIds[$nombre] = $categoria->id;
            }

            // Some claves are legitimately duplicated in the real data (e.g. "DIADEMA"
            // appears 3x on the live site, all under the same categoria) — assign every
            // matching row, not just the first, so re-running this command stays idempotent.
            $productos = Producto::where('clave', $r['clave'])->get();
            if ($productos->isEmpty()) {
                $unmatched[] = $r['clave'];
                continue;
            }
            foreach ($productos as $producto) {
                $producto->categoria_id = $categoriaIds[$nombre];
                $producto->save();
                $matched++;
            }
        }

        $this->info("categorias creadas/existentes: ".count($categoriaIds));
        $this->info("productos actualizados: $matched");
        if ($unmatched) {
            $this->warn('claves sin match en productos: '.implode(', ', $unmatched));
        }

        return self::SUCCESS;
    }
}
