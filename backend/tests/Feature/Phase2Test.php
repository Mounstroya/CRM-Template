<?php

namespace Tests\Feature;

use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Credito;
use App\Models\Garantia;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    private function admin(): User
    {
        return User::create(['name' => 'Test Admin', 'email' => uniqid().'@t.mx', 'password' => 'x', 'type' => 'Propietario', 'status' => 1]);
    }

    private function vendedor(): User
    {
        return User::create(['name' => 'Test Vendedor', 'email' => uniqid().'@t.mx', 'password' => 'x', 'type' => 'Vendedor', 'status' => 1]);
    }

    public function test_vendedor_blocked_from_admin_pages_admin_allowed(): void
    {
        $this->actingAs($this->vendedor())->get('/configuracion')->assertStatus(403);
        $this->actingAs($this->vendedor())->get('/auditoria')->assertStatus(403);
        $this->actingAs($this->vendedor())->get('/cartera-de-usuarios')->assertStatus(403);

        $this->actingAs($this->admin())->get('/configuracion')->assertStatus(200);
        $this->actingAs($this->admin())->get('/auditoria')->assertStatus(200);
        $this->actingAs($this->admin())->get('/cartera-de-usuarios')->assertStatus(200);
    }

    public function test_vendedor_can_access_operative_screens(): void
    {
        $vendedor = $this->vendedor();
        $this->actingAs($vendedor)->get('/punto-de-venta')->assertStatus(200);
        $this->actingAs($vendedor)->get('/caja-registradora')->assertStatus(200);
        $this->actingAs($vendedor)->get('/depositos')->assertStatus(200);
        $this->actingAs($vendedor)->get('/garantia-atender')->assertStatus(200);
    }

    public function test_pos_sale_creates_venta_and_decrements_stock(): void
    {
        $user = $this->admin();
        $producto = Producto::create(['clave' => 'T1', 'descripcion' => 'Test', 'precio_1' => 100, 'precio_compra' => 50, 'stock' => 10, 'status' => 1]);
        $cliente = Cliente::create(['nombre' => 'Cliente Test', 'status' => 'Activo']);

        $resp = $this->actingAs($user)->post('/registroVenta', [
            'cliente_id' => $cliente->id,
            'ventaTipo' => 0,
            'productos' => json_encode([
                ['id' => $producto->id, 'cantidad' => 3, 'precio_venta' => 100, 'servicio' => 0],
            ]),
        ]);

        $resp->assertStatus(200)->assertJson(['ok' => true]);
        $this->assertDatabaseHas('ventas', ['total' => 300, 'utilidad' => 150, 'tipo_venta' => 0]);
        $this->assertEquals(7, $producto->fresh()->stock);
    }

    public function test_pos_sale_on_credit_creates_credito(): void
    {
        $user = $this->admin();
        $producto = Producto::create(['clave' => 'T2', 'descripcion' => 'Test2', 'precio_1' => 200, 'precio_compra' => 100, 'stock' => 5, 'status' => 1]);
        $cliente = Cliente::create(['nombre' => 'Cliente Credito', 'status' => 'Activo', 'dias_credito' => 15, 'limite_credito' => 5000]);

        $resp = $this->actingAs($user)->post('/registroVenta', [
            'cliente_id' => $cliente->id,
            'ventaTipo' => 1,
            'productos' => json_encode([
                ['id' => $producto->id, 'cantidad' => 1, 'precio_venta' => 200, 'servicio' => 0],
            ]),
        ]);

        $resp->assertStatus(200);
        $this->assertDatabaseHas('creditos', ['monto_total' => 200, 'monto_pagado' => 0, 'estado_pago' => 0, 'cliente_nombre' => 'Cliente Credito']);
    }

    public function test_pos_sale_blocked_when_insufficient_stock(): void
    {
        $user = $this->admin();
        $producto = Producto::create(['clave' => 'T3', 'descripcion' => 'Test3', 'precio_1' => 50, 'precio_compra' => 20, 'stock' => 1, 'status' => 1]);

        $resp = $this->actingAs($user)->post('/registroVenta', [
            'cliente_id' => 0,
            'ventaTipo' => 0,
            'productos' => json_encode([
                ['id' => $producto->id, 'cantidad' => 5, 'precio_venta' => 50, 'servicio' => 0],
            ]),
        ]);

        $resp->assertStatus(422);
        $this->assertEquals(1, $producto->fresh()->stock);
    }

    public function test_credito_abono_updates_monto_pagado_and_marks_paid_when_full(): void
    {
        $user = $this->admin();
        $credito = Credito::create(['monto_total' => 500, 'monto_pagado' => 0, 'estado_pago' => 0]);

        $resp = $this->actingAs($user)->post('/credito.abonar', ['credito_id' => $credito->id, 'efectivo' => 500]);

        $resp->assertStatus(200)->assertJson(['ok' => true, 'monto_pagado' => 500, 'estado_pago' => 1]);
        $this->assertDatabaseHas('creditos', ['id' => $credito->id, 'monto_pagado' => 500, 'estado_pago' => 1]);
    }

    public function test_credito_abono_parcial_keeps_estado_pendiente(): void
    {
        $user = $this->admin();
        $credito = Credito::create(['monto_total' => 500, 'monto_pagado' => 0, 'estado_pago' => 0]);

        $resp = $this->actingAs($user)->post('/credito.abonar', ['credito_id' => $credito->id, 'efectivo' => 200]);

        $resp->assertStatus(200)->assertJson(['ok' => true, 'monto_pagado' => 200, 'estado_pago' => 0]);
    }

    public function test_garantia_status_change(): void
    {
        $user = $this->admin();
        $g = Garantia::create(['producto' => 'X', 'cliente' => 'Y', 'motivo' => 'Z', 'status' => 'Solicitud de garantía']);

        $resp = $this->actingAs($user)->put('/garantia-update-status', ['garantia_id' => $g->id, 'status' => 3]);

        $resp->assertStatus(200);
        $this->assertDatabaseHas('garantias', ['id' => $g->id, 'status' => 'Aprobada']);
    }

    /**
     * Real bug fixed: matched producto_vendido items by numeric id, but that id is
     * whatever the ORIGINAL site's own scrape captured — unrelated to this clone's
     * own auto-increment producto ids (confirmed live: a real venta's item id
     * happened to collide with a completely different producto here). Also
     * `optional($venta->fecha_compra)->format(...)` always silently returned null
     * since fecha_compra isn't cast to a datetime on the Venta model.
     */
    public function test_garantia_last_purchase_matches_by_descripcion_not_id(): void
    {
        $user = $this->admin();
        $cliente = Cliente::create(['nombre' => 'RAMIRO PEREZ', 'status' => 'Activo']);
        // id deliberately does NOT match any producto_vendido item id below.
        $producto = Producto::create(['clave' => 'GAR148', 'descripcion' => 'GAR148 POWERBANK CON CABLES', 'precio_1' => 176, 'stock' => 10, 'status' => 1]);
        Venta::create([
            'cliente_id' => $cliente->id,
            'fecha_compra' => '2026-08-15 11:38:02',
            'total' => 352,
            'producto_vendido' => [['id' => 999999, 'nombre' => 'GAR148 POWERBANK CON CABLES', 'cantidad' => '2.00']],
        ]);

        $resp = $this->actingAs($user)->post('/garantia/lastPurchase', [
            'cliente_id' => $cliente->id,
            'producto_id' => $producto->id,
        ]);

        $resp->assertStatus(200)->assertJson([
            'cliente' => 'RAMIRO PEREZ',
            'producto' => 'GAR148 POWERBANK CON CABLES',
            'fecha' => '2026-08-15 11:38:02',
            'cantidad' => '2.00',
        ]);
    }

    public function test_caja_apertura_y_cierre(): void
    {
        $user = $this->admin();

        $resp = $this->actingAs($user)->post('/caja_status', ['status' => 1, 'efectivo' => 1000]);
        $resp->assertStatus(200);
        $this->assertDatabaseHas('cajas', ['users_id' => $user->id, 'status' => 1, 'cuenta_inicial' => 1000]);

        $resp2 = $this->actingAs($user)->post('/caja_status', ['contadoEfectivo' => 1500, 'contadoTransferencia' => 0, 'contadoTarjeta' => 0]);
        $resp2->assertStatus(200);
        $this->assertDatabaseHas('cajas', ['users_id' => $user->id, 'status' => 0, 'cuenta_final' => 1500]);
    }

    public function test_caja_cannot_open_two_at_once(): void
    {
        $user = $this->admin();
        $this->actingAs($user)->post('/caja_status', ['status' => 1, 'efectivo' => 100])->assertStatus(200);
        $this->actingAs($user)->post('/caja_status', ['status' => 1, 'efectivo' => 100])->assertStatus(422);
    }
}
