<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Garantia;
use App\Models\Producto;

class PageController extends Controller
{
    public function miLocal()
    {
        return view('mi_local');
    }

    public function creditos()
    {
        return view('creditos');
    }

    public function carteraDeClientes()
    {
        return view('cartera_de_clientes');
    }

    public function carteraDeUsuarios()
    {
        return view('cartera_de_usuarios');
    }

    public function configuracion()
    {
        return view('configuracion');
    }

    public function depositos()
    {
        return view('depositos');
    }

    public function depositosConsulta()
    {
        return view('depositos_consulta');
    }

    public function garantia()
    {
        return view('garantia', [
            'garantias' => Garantia::orderByDesc('registro')->get(),
            'productos' => Producto::where('status', 1)->orderBy('descripcion')->get(),
        ]);
    }

    public function garantiaAtender()
    {
        return view('garantia_atender', [
            'garantias' => Garantia::orderByDesc('registro')->get(),
            'productos' => Producto::where('status', 1)->orderBy('descripcion')->get(),
        ]);
    }

    public function historico()
    {
        return view('historico');
    }

    public function puntoDeVenta()
    {
        $productos = Producto::where('status', 1)->get()->map(fn ($p) => [
            'id' => $p->id,
            'clave' => $p->clave,
            'descripcion' => $p->descripcion,
            'precio_1' => $p->precio_1,
            'precio_2' => $p->precio_2,
            'precio_3' => $p->precio_3,
            'precio_4' => $p->precio_4,
            'precio_compra' => $p->precio_compra,
            'stock' => $p->stock,
            'servicio' => $p->servicio ? 1 : 0,
            'unidad_venta' => $p->unidad_venta,
            'categoria' => '',
        ]);

        return view('punto_de_venta', ['productosJson' => $productos->values()->toJson()]);
    }

    public function auditoria()
    {
        return view('auditoria', ['auditorias' => Auditoria::all()]);
    }

    public function cajaRegistradora()
    {
        return view('caja_registradora');
    }

    public function miLocalProductos()
    {
        return view('mi_local_productos', ['productos' => Producto::orderBy('descripcion')->get()]);
    }
}
