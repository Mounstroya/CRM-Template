<?php

namespace App\Console\Commands;

use App\Models\Producto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Imports the ~76 real product photos from the extracted IMAGENES FD3.zip into the
 * shared-media volume (mounted into both the CRM's public/storage/productos and the
 * bot's uploads/productos). Filenames = producto.clave exactly, per the owner
 * (e.g. AUT123.png) — matched case-insensitively, with a no-space fallback since a
 * few filenames in the zip have stray spaces. Reports what didn't match either way.
 */
class ImportProductPhotos extends Command
{
    protected $signature = 'app:import-product-photos {source : Directory with the extracted photos}';

    protected $description = 'Copy product photos into shared-media/productos, matched by clave';

    public function handle(): int
    {
        $source = rtrim($this->argument('source'), '/');
        if (! File::isDirectory($source)) {
            $this->error("No existe el directorio: $source");

            return self::FAILURE;
        }

        // public/storage/productos is the shared-media/productos volume, mounted into
        // both this container and the bot's.
        $dest = public_path('storage/productos');
        File::ensureDirectoryExists($dest);

        $productos = Producto::pluck('clave')->filter()->values();
        $byNormalizedClave = [];
        foreach ($productos as $clave) {
            $byNormalizedClave[strtoupper(trim($clave))] = $clave;
        }

        $files = File::files($source);
        $matched = 0;
        $unmatchedFiles = [];
        $matchedClaves = [];

        foreach ($files as $file) {
            $filename = $file->getFilenameWithoutExtension();
            $ext = $file->getExtension();
            $normalized = strtoupper(trim($filename));
            $normalizedNoSpace = str_replace(' ', '', $normalized);

            $clave = $byNormalizedClave[$normalized] ?? $byNormalizedClave[$normalizedNoSpace] ?? null;
            if (! $clave) {
                $unmatchedFiles[] = $file->getFilename();

                continue;
            }

            File::copy($file->getPathname(), "$dest/$clave.$ext");
            $matchedClaves[] = $clave;
            $matched++;
        }

        $sinFoto = $productos->diff($matchedClaves)->values();

        $this->info("Fotos copiadas: $matched de ".count($files));
        $this->info('Archivos del zip que no matchearon ningún producto real: '.count($unmatchedFiles));
        foreach ($unmatchedFiles as $f) {
            $this->line("  - $f");
        }
        $this->info('Productos reales sin foto: '.$sinFoto->count().' de '.$productos->count());
        foreach ($sinFoto as $c) {
            $this->line("  - $c");
        }

        return self::SUCCESS;
    }
}
