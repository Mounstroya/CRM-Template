<?php

namespace App\Console\Commands;

use App\Models\Categoria;
use App\Models\Departamento;
use Illuminate\Console\Command;

class BackfillDepartamentosReales extends Command
{
    protected $signature = 'app:backfill-departamentos-reales';

    protected $description = 'Split the single "General" departamento into the real ones '
        .'(ACCESORIOS, EQUIPOS) confirmed live from the original site, and move each existing '
        .'categoria under its real departamento (CELULAR/TABLETA -> EQUIPOS, everything else -> ACCESORIOS).';

    private const CATEGORIAS_EQUIPOS = ['CELULAR', 'TABLETA'];

    public function handle(): int
    {
        $accesorios = Departamento::firstOrCreate(['departamento' => 'ACCESORIOS'], ['status' => 1]);
        $equipos = Departamento::firstOrCreate(['departamento' => 'EQUIPOS'], ['status' => 1]);

        $moved = ['ACCESORIOS' => 0, 'EQUIPOS' => 0];
        foreach (Categoria::all() as $categoria) {
            $target = in_array(strtoupper(trim($categoria->categoria)), self::CATEGORIAS_EQUIPOS, true) ? $equipos : $accesorios;
            $categoria->departamentos_id = $target->id;
            $categoria->save();
            $moved[$target->departamento]++;
        }

        $this->info("categorias movidas a ACCESORIOS: {$moved['ACCESORIOS']}");
        $this->info("categorias movidas a EQUIPOS: {$moved['EQUIPOS']}");

        return self::SUCCESS;
    }
}
