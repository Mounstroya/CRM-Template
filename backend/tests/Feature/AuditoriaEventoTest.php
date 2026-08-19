<?php

namespace Tests\Feature;

use App\Models\Auditoria;
use App\Models\AuditoriaEvento;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers the AuditoriaController rewrite: real historical audit EVENTS (many per
 * local over time) replaced the earlier "own design" single ultima_auditoria_*
 * columns directly on Auditoria — no test coverage existed for that flow before
 * this rewrite, so this is new coverage, not a migration of an old test.
 */
class AuditoriaEventoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    private function admin(int $localesId = 1): User
    {
        return User::create(['name' => 'Test Admin', 'email' => uniqid().'@t.mx', 'password' => 'x', 'type' => 'Propietario', 'status' => 1, 'locales_id' => $localesId]);
    }

    public function test_nueva_auditoria_creates_evento_and_locales_auditoria_lists_it(): void
    {
        $user = $this->admin();
        $sucursal = Auditoria::create(['id' => 1, 'nombre' => 'Bodega Test', 'status' => 1]);

        $resp = $this->actingAs($user)->post('/autidoria/nueva-auditoria', ['id' => $sucursal->id]);
        $resp->assertStatus(200)->assertJson(['ok' => true]);
        $eventoId = $resp->json('evento.id');
        $this->assertDatabaseHas('auditoria_eventos', ['id' => $eventoId, 'local_id' => $sucursal->id, 'fecha_fin' => null]);

        $lista = $this->actingAs($user)->post('/autidoria/get-locales-auditoria')->json('sucursales');
        $entry = collect($lista)->firstWhere('id', $sucursal->id);
        $this->assertNotNull($entry['ultima_auditoria']);
        $this->assertEquals($eventoId, $entry['ultima_auditoria']['id']);
    }

    public function test_full_conteo_lifecycle_and_reports(): void
    {
        $user = $this->admin(localesId: 1);
        $sucursal = Auditoria::create(['id' => 1, 'nombre' => 'Bodega Test', 'status' => 1]);
        $producto = Producto::create(['locales_id' => 1, 'clave' => 'AUD1', 'descripcion' => 'Producto Auditado', 'precio_1' => 100, 'stock' => 20, 'status' => 1]);

        $evento = AuditoriaEvento::create(['local_id' => 1, 'no_auditoria' => '1', 'fecha_inicio' => now()]);

        $show = $this->actingAs($user)->get("/auditoria/{$evento->id}/show");
        $show->assertStatus(200)->assertSee('Producto Auditado');

        $guardar = $this->actingAs($user)->post("/auditoria/{$evento->id}/conteo", [
            'producto_id' => $producto->id,
            'stock_contado' => 18,
        ]);
        $guardar->assertStatus(200)->assertJson(['ok' => true]);
        $this->assertDatabaseHas('auditoria_conteos', ['auditoria_id' => $evento->id, 'producto_id' => $producto->id, 'stock_sistema' => 20, 'stock_contado' => 18, 'diferencia' => -2]);

        // Reports download real data for this specific evento, not another.
        $excel = $this->actingAs($user)->post('/auditoria/reporte-auditoria', ['id' => $evento->id]);
        $excel->assertStatus(200);
        $this->assertStringContainsString('AUD1', $excel->streamedContent());

        $pdf = $this->actingAs($user)->post('/auditoria/reporte/pdf', ['id' => $evento->id]);
        $pdf->assertStatus(200);

        $finalizar = $this->actingAs($user)->post("/auditoria/{$evento->id}/finalizar");
        $finalizar->assertStatus(200)->assertJson(['ok' => true, 'diferencias' => 1]);
        $this->assertDatabaseHas('auditoria_eventos', ['id' => $evento->id]);
        $this->assertNotNull($evento->fresh()->fecha_fin);
    }

    public function test_fechas_auditoria_local_returns_full_real_history(): void
    {
        $user = $this->admin();
        Auditoria::create(['id' => 1, 'nombre' => 'Bodega Test', 'status' => 1]);
        AuditoriaEvento::create(['local_id' => 1, 'no_auditoria' => '1', 'fecha_inicio' => now()->subDays(10), 'fecha_fin' => now()->subDays(9)]);
        AuditoriaEvento::create(['local_id' => 1, 'no_auditoria' => '2', 'fecha_inicio' => now()]);

        $resp = $this->actingAs($user)->post('/auditoria/fechas-auditoria-local', ['id' => 1]);
        $resp->assertStatus(200);
        $this->assertCount(2, $resp->json('auditorias'));
    }
}
