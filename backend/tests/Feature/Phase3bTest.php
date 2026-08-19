<?php

namespace Tests\Feature;

use App\Models\Auditoria;
use App\Models\Producto;
use App\Models\Traspaso;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase3bTest extends TestCase
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

    public function test_get_tiendas_vinculadas_lists_real_branches(): void
    {
        $user = $this->admin();
        Auditoria::create(['nombre' => 'Sucursal Norte', 'status' => 1]);

        $resp = $this->actingAs($user)->post('/getTiendasVinculadas');

        $resp->assertStatus(200);
        $this->assertTrue(collect($resp->json('sucursales'))->contains('nombre', 'Sucursal Norte'));
    }

    public function test_full_traspaso_lifecycle_solicitado_to_recibido_moves_stock(): void
    {
        $user = $this->admin();
        $sucursal = Auditoria::create(['nombre' => 'Sucursal Sur', 'status' => 1]);
        $producto = Producto::create(['clave' => 'TR1', 'descripcion' => 'TR1', 'precio_1' => 100, 'precio_compra' => 40, 'stock' => 50, 'status' => 1]);

        // 1. Solicitar
        $crear = $this->actingAs($user)->post('/crearSolicitud', [
            'sucursal_id' => $sucursal->id,
            'productos' => json_encode([['id' => $producto->id, 'cantidad' => 10]]),
        ]);
        $crear->assertStatus(200)->assertJson(['status' => 1]);
        $traspasoId = $crear->json('requisicion.id');
        $this->assertDatabaseHas('traspasos', ['id' => $traspasoId, 'status' => 0]);
        $this->assertDatabaseHas('traspaso_detalles', ['traspaso_id' => $traspasoId, 'producto_id' => $producto->id, 'cantidad_solicitada' => 10]);

        // appears in "activas" not "surtidas"
        $activas = $this->actingAs($user)->post('/getRequisicionesActivas')->json('requisiciones');
        $this->assertTrue(collect($activas)->contains('id', $traspasoId));
        $surtidas = $this->actingAs($user)->post('/getRequisicionesSurtidas')->json('requisiciones');
        $this->assertFalse(collect($surtidas)->contains('id', $traspasoId));

        // 2. Autorizar
        $this->actingAs($user)->post('/autorizarTraspaso', ['id' => $traspasoId])->assertStatus(200);
        $this->assertDatabaseHas('traspasos', ['id' => $traspasoId, 'status' => 1]);

        // Sending before authorized would have been rejected — verify enforcement on a fresh unauthorized one
        $otro = Traspaso::create(['sucursal_origen_id' => $sucursal->id, 'status' => 0, 'no_requisicion' => 999]);
        $rejected = $this->actingAs($user)->post('/enviarMovimientoMercancia', ['id' => $otro->id]);
        $rejected->assertJson(['status' => false]);

        // 3. Enviar (decrements stock)
        $enviar = $this->actingAs($user)->post('/enviarMovimientoMercancia', ['id' => $traspasoId]);
        $enviar->assertStatus(200)->assertJson(['status' => true]);
        $this->assertEquals(40, $producto->fresh()->stock);
        $this->assertDatabaseHas('traspasos', ['id' => $traspasoId, 'status' => 2]);

        // 4. Ingresar. Origin and destino are both locales_id=1 here (default for a
        // user/producto that don't set one), so crediting destino's matching-clave row
        // lands back on this same row — see test_traspaso_moves_stock_between_different_locales
        // below for the real cross-local case.
        $ingresar = $this->actingAs($user)->post('/ingresarMovimientoMercanciaDetalles', ['id' => $traspasoId]);
        $ingresar->assertStatus(200)->assertJson(['status' => true]);
        $this->assertEquals(50, $producto->fresh()->stock);
        $this->assertDatabaseHas('traspasos', ['id' => $traspasoId, 'status' => 3]);

        // now appears in "surtidas"
        $surtidas2 = $this->actingAs($user)->post('/getRequisicionesSurtidas')->json('requisiciones');
        $this->assertTrue(collect($surtidas2)->contains('id', $traspasoId));
    }

    public function test_traspaso_moves_stock_between_different_locales(): void
    {
        // Real system: each local (Bodega Principal + one per vendedor) has its own
        // independent producto row/stock per clave — confirmed live. A traspaso must
        // decrement the origin's row and credit the destino's own row for that same
        // clave, creating it if the destino never had that product before.
        $vendedor = User::create(['name' => 'Vendedor Test', 'email' => uniqid().'@t.mx', 'password' => 'x', 'type' => 'Vendedor', 'status' => 1, 'locales_id' => 2]);
        $bodega = Auditoria::create(['id' => 1, 'nombre' => 'Bodega Principal', 'status' => 1]);
        Auditoria::create(['id' => 2, 'nombre' => 'Local Vendedor', 'status' => 1]);
        $productoOrigen = Producto::create(['locales_id' => 1, 'clave' => 'XCL1', 'descripcion' => 'Producto cruzado', 'precio_1' => 100, 'stock' => 30, 'status' => 1]);

        $this->assertDatabaseMissing('productos', ['locales_id' => 2, 'clave' => 'XCL1']);

        $crear = $this->actingAs($vendedor)->post('/crearSolicitud', [
            'sucursal_id' => $bodega->id,
            'productos' => json_encode([['id' => $productoOrigen->id, 'cantidad' => 5]]),
        ]);
        $traspasoId = $crear->json('requisicion.id');
        $this->assertDatabaseHas('traspasos', ['id' => $traspasoId, 'sucursal_origen_id' => 1, 'sucursal_destino_id' => 2]);

        $this->actingAs($vendedor)->post('/autorizarTraspaso', ['id' => $traspasoId]);
        $this->actingAs($vendedor)->post('/enviarMovimientoMercancia', ['id' => $traspasoId]);
        $this->assertEquals(25, $productoOrigen->fresh()->stock);

        $this->actingAs($vendedor)->post('/ingresarMovimientoMercanciaDetalles', ['id' => $traspasoId]);

        // Origin stays decremented — the vendedor's own new row got credited instead.
        $this->assertEquals(25, $productoOrigen->fresh()->stock);
        $this->assertDatabaseHas('productos', ['locales_id' => 2, 'clave' => 'XCL1', 'stock' => 5]);
    }

    public function test_rechazar_and_eliminar(): void
    {
        $user = $this->admin();
        $sucursal = Auditoria::create(['nombre' => 'Sucursal Este', 'status' => 1]);

        $t1 = Traspaso::create(['sucursal_origen_id' => $sucursal->id, 'status' => 2, 'no_requisicion' => 1]);
        $this->actingAs($user)->post('/rechazarMovimientoMercanciaDetalles', ['id' => $t1->id])->assertStatus(200);
        $this->assertDatabaseHas('traspasos', ['id' => $t1->id, 'status' => 4]);

        $t2 = Traspaso::create(['sucursal_origen_id' => $sucursal->id, 'status' => 0, 'no_requisicion' => 2]);
        $del = $this->actingAs($user)->post('/eliminarMovimientoMercancia', ['id' => $t2->id]);
        $del->assertStatus(200)->assertJson(['status' => true]);
        $this->assertDatabaseMissing('traspasos', ['id' => $t2->id]);

        $t3 = Traspaso::create(['sucursal_origen_id' => $sucursal->id, 'status' => 2, 'no_requisicion' => 3]);
        $delBlocked = $this->actingAs($user)->post('/eliminarMovimientoMercancia', ['id' => $t3->id]);
        $delBlocked->assertJson(['status' => false]);
        $this->assertDatabaseHas('traspasos', ['id' => $t3->id]);
    }

    public function test_update_cantidad_solicitada_and_ver_requisicion(): void
    {
        $user = $this->admin();
        $sucursal = Auditoria::create(['nombre' => 'Sucursal Oeste', 'status' => 1]);
        $producto = Producto::create(['clave' => 'TR2', 'descripcion' => 'TR2', 'precio_1' => 50, 'precio_compra' => 20, 'stock' => 5, 'status' => 1]);
        $crear = $this->actingAs($user)->post('/crearSolicitud', [
            'sucursal_id' => $sucursal->id,
            'productos' => json_encode([['id' => $producto->id, 'cantidad' => 3]]),
        ]);
        $traspasoId = $crear->json('requisicion.id');
        $detalleId = \App\Models\TraspasoDetalle::where('traspaso_id', $traspasoId)->first()->id;

        $this->actingAs($user)->post('/updateCantidadSolicitada', ['detalle_id' => $detalleId, 'cantidad' => 7])->assertStatus(200);
        $this->assertDatabaseHas('traspaso_detalles', ['id' => $detalleId, 'cantidad_solicitada' => 7]);

        // Real evidence (js/app-inventario.js, both traspasos' own verRequisicion() and
        // compras' arribo_productos() call routeVerRequisicion and .forEach the response
        // directly): the real shape is a flat array of line items, not {id,detalles:[...]}.
        $ver = $this->actingAs($user)->post('/verRequisicion', ['id' => $traspasoId]);
        $ver->assertStatus(200);
        $this->assertEquals(7, (float) $ver->json('requisicion.0.cantidad_solicitada'));
    }
}
