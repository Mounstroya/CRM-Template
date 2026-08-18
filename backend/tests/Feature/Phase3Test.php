<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase3Test extends TestCase
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

    public function test_credit_sale_uses_clients_real_dias_credito_not_a_flat_default(): void
    {
        $user = $this->admin();
        $producto = Producto::create(['clave' => 'P3A', 'descripcion' => 'P3A', 'precio_1' => 100, 'precio_compra' => 40, 'stock' => 10, 'status' => 1]);
        $cliente = Cliente::create(['nombre' => 'Cliente Plazo30', 'status' => 'Activo', 'dias_credito' => 30, 'limite_credito' => 5000]);

        $resp = $this->actingAs($user)->post('/registroVenta', [
            'cliente_id' => $cliente->id,
            'ventaTipo' => 1,
            'productos' => json_encode([['id' => $producto->id, 'cantidad' => 1, 'precio_venta' => 100, 'servicio' => 0]]),
        ]);

        $resp->assertStatus(200);
        $this->assertDatabaseHas('creditos', ['plazo_pago' => 30]);
        $fecha = \App\Models\Credito::first()->fecha_vencimiento;
        $this->assertEquals(now()->addDays(30)->toDateString(), \Illuminate\Support\Carbon::parse($fecha)->toDateString());
    }

    public function test_credit_sale_rejected_for_client_without_credit_line(): void
    {
        $user = $this->admin();
        $producto = Producto::create(['clave' => 'P3B', 'descripcion' => 'P3B', 'precio_1' => 100, 'precio_compra' => 40, 'stock' => 10, 'status' => 1]);
        $cliente = Cliente::create(['nombre' => 'Cliente Sin Credito', 'status' => 'Activo', 'dias_credito' => 0, 'limite_credito' => 0]);

        $resp = $this->actingAs($user)->post('/registroVenta', [
            'cliente_id' => $cliente->id,
            'ventaTipo' => 1,
            'productos' => json_encode([['id' => $producto->id, 'cantidad' => 1, 'precio_venta' => 100, 'servicio' => 0]]),
        ]);

        $resp->assertStatus(422);
        $this->assertDatabaseMissing('ventas', ['total' => 100]);
    }

    public function test_pos_price_tier_selects_correct_price_column_by_nivel_numero(): void
    {
        $user = $this->admin();
        Producto::create(['clave' => 'P3C', 'descripcion' => 'P3C', 'precio_1' => 150, 'precio_2' => 115, 'precio_3' => 97, 'precio_compra' => 60, 'stock' => 10, 'status' => 1]);
        Cliente::create(['nombre' => 'Cliente Nivel Preferente', 'status' => 'Activo', 'nivel' => 'PREFERENTE', 'nivel_numero' => 2]);

        $resp = $this->actingAs($user)->post('/local.getClientes');
        $resp->assertStatus(200);
        $cliente = collect($resp->json('clientes'))->first(fn ($c) => trim($c['nombre'].' '.$c['apellido_p']) === 'Cliente Nivel Preferente');
        $this->assertEquals(2, $cliente['nivel']);

        $producto = Producto::where('clave', 'P3C')->first();
        $priceResp = $this->actingAs($user)->post('/productos-get-price', ['id' => $producto->id, 'nivel' => $cliente['nivel']]);
        $priceResp->assertStatus(200)->assertJson(['precio' => 115]);
    }

    public function test_proveedor_crud(): void
    {
        $user = $this->admin();

        $resp = $this->actingAs($user)->post('/addProveedor', [
            'nombre' => 'Proveedor Test SA', 'representante' => 'Juan Perez', 'celular' => '777', 'telefono' => '555', 'emails' => 'a@a.com',
        ]);
        $resp->assertStatus(200)->assertJsonPath('proveedor.nombre', 'Proveedor Test SA');
        $id = $resp->json('proveedor.id');

        $this->actingAs($user)->post('/getProveedores')->assertStatus(200)->assertJsonPath('proveedores.0.nombre', 'Proveedor Test SA');

        $this->actingAs($user)->post('/statusProveedor', ['id' => $id, 'status' => 0])->assertStatus(200);
        $this->assertDatabaseHas('proveedores', ['id' => $id, 'status' => 0]);
    }

    public function test_departamento_y_categoria_crud(): void
    {
        $user = $this->admin();

        $resp = $this->actingAs($user)->post('/store', ['departamento' => 'Accesorios Test']);
        $resp->assertStatus(200)->assertJsonPath('departamento.departamento', 'Accesorios Test');
        $deptoId = $resp->json('departamento.id');

        $catResp = $this->actingAs($user)->post('/store_categoria', ['departamentos_id' => $deptoId, 'categoria' => 'Cables Test']);
        $catResp->assertStatus(200)->assertJsonPath('categoria.categoria', 'Cables Test');
        $catId = $catResp->json('categoria.id');

        $this->actingAs($user)->post('/delete_cat', ['id' => $catId])->assertStatus(200);
        $this->assertDatabaseMissing('categorias', ['id' => $catId]);

        $this->actingAs($user)->post('/get_datos_generales')->assertStatus(200)->assertJsonPath('departamentos.0.departamento', 'Accesorios Test');
    }

    public function test_registrar_compra_increments_stock_and_updates_cost(): void
    {
        $user = $this->admin();
        $producto = Producto::create(['clave' => 'P3D', 'descripcion' => 'P3D', 'precio_1' => 100, 'precio_compra' => 40, 'stock' => 5, 'status' => 1]);
        $proveedor = Proveedor::create(['nombre' => 'Prov Compra', 'status' => 1]);

        $resp = $this->actingAs($user)->post('/registrar-compra', [
            'producto_id' => $producto->id, 'proveedor_id' => $proveedor->id, 'cantidad' => 20, 'costo_unitario' => 45,
        ]);

        $resp->assertStatus(200)->assertJson(['ok' => true]);
        $this->assertEquals(25, $producto->fresh()->stock);
        $this->assertEquals(45, $producto->fresh()->precio_compra);
        $this->assertDatabaseHas('compras', ['producto_id' => $producto->id, 'cantidad' => 20]);
    }
}
