<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\PedidoWhatsapp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

/**
 * Single management console for the WhatsApp bot (Fase 4 — full fusion). Talks to the
 * Node/Baileys service's internal API (shared-secret header, never exposed publicly)
 * for anything that's bot-state (conversations, prospectos, quejas, QR/connection
 * status, sending messages); pedidos_whatsapp and garantías are queried directly from
 * this same MySQL database since the bot writes/reads them there too.
 */
class WhatsappController extends Controller
{
    private function bot()
    {
        return Http::withHeaders(['X-Internal-Secret' => config('services.whatsapp_bot.secret')])
            ->timeout(10)
            ->baseUrl(config('services.whatsapp_bot.url'));
    }

    public function index()
    {
        $pedidos = PedidoWhatsapp::with('cliente')->orderByDesc('id')->limit(50)->get();

        return view('whatsapp.index', ['pedidos' => $pedidos]);
    }

    public function status()
    {
        $resp = $this->bot()->get('/internal/status');

        return response()->json($resp->json(), $resp->status());
    }

    public function prospectos()
    {
        $resp = $this->bot()->get('/internal/prospectos');

        return response()->json($resp->json(), $resp->status());
    }

    public function quejas()
    {
        $resp = $this->bot()->get('/internal/quejas');

        return response()->json($resp->json(), $resp->status());
    }

    public function sync()
    {
        $resp = $this->bot()->post('/internal/sync-socios');

        return response()->json($resp->json(), $resp->status());
    }

    public function enviarCatalogo(Request $request)
    {
        $cliente = Cliente::findOrFail($request->input('cliente_id'));
        $resp = $this->bot()->post('/internal/enviar-catalogo', ['cliente_fd3_id' => $cliente->id]);

        return response()->json($resp->json(), $resp->status());
    }

    public function pedidos()
    {
        $pedidos = PedidoWhatsapp::with('cliente')->orderByDesc('id')->limit(50)->get();

        return response()->json(['pedidos' => $pedidos]);
    }

    /**
     * Confirms a pendiente pedido_whatsapp into a real sale, reusing
     * VentaController::crearVenta (same stock/price/credit logic as the POS) —
     * explicit owner requirement, not duplicated logic.
     */
    public function confirmarPedido(PedidoWhatsapp $pedido, VentaController $ventaController)
    {
        if ($pedido->estado !== 'pendiente') {
            return response()->json(['ok' => false, 'message' => 'Solo se puede confirmar un pedido pendiente.'], 422);
        }

        $cliente = Cliente::findOrFail($pedido->cliente_id);
        $venta = $ventaController->crearVenta($pedido->items, 0, $cliente, Auth::user()->name);

        $pedido->estado = 'confirmado';
        $pedido->venta_id = $venta->id;
        $pedido->save();

        $this->notificarCliente($cliente, "Tu pedido WA-{$pedido->id} fue confirmado. ¡Gracias por tu compra!");

        return response()->json(['ok' => true, 'pedido' => $pedido->fresh(), 'venta' => $venta]);
    }

    private const TRANSICIONES = [
        'confirmado' => 'enviado',
        'enviado' => 'completado',
    ];

    public function avanzarPedido(Request $request, PedidoWhatsapp $pedido)
    {
        $accion = $request->input('accion');

        if ($accion === 'cancelar') {
            if (in_array($pedido->estado, ['completado', 'cancelado'], true)) {
                return response()->json(['ok' => false, 'message' => 'Ese pedido ya no se puede cancelar.'], 422);
            }
            $pedido->estado = 'cancelado';
            $pedido->save();
            $this->notificarCliente($pedido->cliente, "Tu pedido WA-{$pedido->id} fue cancelado.");

            return response()->json(['ok' => true, 'pedido' => $pedido->fresh()]);
        }

        $siguiente = self::TRANSICIONES[$pedido->estado] ?? null;
        if (! $siguiente) {
            return response()->json(['ok' => false, 'message' => "No se puede avanzar un pedido en estado '{$pedido->estado}'."], 422);
        }
        $pedido->estado = $siguiente;
        $pedido->save();
        $this->notificarCliente($pedido->cliente, "Tu pedido WA-{$pedido->id} ahora está: {$siguiente}.");

        return response()->json(['ok' => true, 'pedido' => $pedido->fresh()]);
    }

    private function notificarCliente(?Cliente $cliente, string $texto): void
    {
        if (! $cliente || ! $cliente->telefono) {
            return;
        }
        // Best-effort: WhatsApp delivery can't be confirmed until the owner links a
        // real number (see /whatsapp QR screen) — don't fail the staff action if this
        // errors, just leave it unnotified.
        try {
            $this->bot()->post('/internal/enviar-mensaje', ['telefono' => $cliente->telefono, 'texto' => $texto]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
