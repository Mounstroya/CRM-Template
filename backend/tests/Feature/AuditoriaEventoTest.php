<?php

namespace Tests\Feature;

use App\Models\Auditoria;
use App\Models\AuditoriaEvento;
use App\Models\Producto;
use App\Models\Traspaso;
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

        // Real page: products load via AJAX (#tblProductos), not server-rendered.
        $show = $this->actingAs($user)->get("/auditoria/{$evento->id}/show");
        $show->assertStatus(200)->assertSee('tblProductos', false);

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

    public function test_productos_by_local_and_auditar_flow_matches_by_clave_across_locales(): void
    {
        $user = $this->admin(localesId: 1);
        $bodega = Auditoria::create(['id' => 1, 'nombre' => 'Bodega Test', 'status' => 1]);
        $vendedorLocal = Auditoria::create(['id' => 2, 'nombre' => 'Vendedor Test', 'status' => 1]);
        $productoBodega = Producto::create(['locales_id' => 1, 'clave' => 'AUD2', 'descripcion' => 'Producto Movido', 'precio_1' => 50, 'stock' => 100, 'status' => 1]);
        $productoVendedor = Producto::create(['locales_id' => 2, 'clave' => 'AUD2', 'descripcion' => 'Producto Movido', 'precio_1' => 50, 'stock' => 0, 'status' => 1]);

        // Real traspaso Bodega -> Vendedor, received, so totalRecibidos should reflect
        // it for the vendedor's local even though the detalle.producto_id points at
        // the BODEGA's own row (created from that catalog), not the vendedor's.
        $traspaso = Traspaso::create(['sucursal_origen_id' => 1, 'sucursal_destino_id' => 2, 'status' => 3, 'no_requisicion' => 500]);
        \App\Models\TraspasoDetalle::create(['traspaso_id' => $traspaso->id, 'producto_id' => $productoBodega->id, 'cantidad_solicitada' => 10, 'cantidad_enviada' => 10, 'cantidad_recibida' => 10]);

        $evento = AuditoriaEvento::create(['local_id' => 2, 'no_auditoria' => '1', 'fecha_inicio' => now()]);

        $lista = $this->actingAs($user)->post('/productos/getProductosByLocalId', ['id' => 2, 'auditado' => false, 'auditoria_id' => $evento->id]);
        $lista->assertStatus(200)->assertJson(['status' => true]);
        $this->assertTrue(collect($lista->json('productos'))->contains('id', $productoVendedor->id));

        $auditar = $this->actingAs($user)->post('/auditoria/producto-auditar', [
            'id' => $productoVendedor->id, 'localId' => 2, 'auditado' => false, 'auditoriaId' => $evento->id,
        ]);
        $auditar->assertStatus(200)->assertJson(['totalRecibidos' => 10, 'totalEnviados' => 0]);

        $auditado = $this->actingAs($user)->post('/auditoria/producto-auditado', [
            'auditoria_id' => $evento->id, 'producto_id' => $productoVendedor->id,
            'stock' => 0, 'entradas' => 10, 'salidas' => 0, 'calculado' => 10, 'stock_final' => 9, 'diferencia' => -1, 'nota' => 'faltó uno',
        ]);
        $auditado->assertStatus(200)->assertJson(['status' => true]);
        $this->assertDatabaseHas('auditoria_conteos', ['auditoria_id' => $evento->id, 'producto_id' => $productoVendedor->id, 'stock_contado' => 9, 'diferencia' => -1, 'comentario' => 'faltó uno']);

        // Re-fetching with auditado=true should now surface this saved count.
        $reconsulta = $this->actingAs($user)->post('/auditoria/producto-auditar', [
            'id' => $productoVendedor->id, 'localId' => 2, 'auditado' => true, 'auditoriaId' => $evento->id,
        ]);
        $reconsulta->assertStatus(200)->assertJsonPath('ultimaAuditoriaProducto.stock_final', 9);
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
