<?php

use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\CreditoController;
use App\Http\Controllers\DepositoController;
use App\Http\Controllers\GarantiaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TraspasoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('root');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Operative screens: open to all logged-in roles (Vendedor included).
    Route::get('/mi-local', [PageController::class, 'miLocal']);
    Route::get('/mi-local/productos', [PageController::class, 'miLocalProductos']);
    Route::get('/creditos', [PageController::class, 'creditos']);
    Route::get('/cartera-de-clientes', [PageController::class, 'carteraDeClientes']);
    Route::get('/depositos', [PageController::class, 'depositos']);
    Route::get('/depositos-consulta', [PageController::class, 'depositosConsulta']);
    Route::get('/garantia', [PageController::class, 'garantia']);
    Route::get('/garantia-atender', [PageController::class, 'garantiaAtender']);
    Route::get('/historico', [PageController::class, 'historico']);
    Route::get('/punto-de-venta', [PageController::class, 'puntoDeVenta']);
    Route::get('/caja-registradora', [PageController::class, 'cajaRegistradora']);

    Route::get('/clientes.tabla', [ClienteController::class, 'tabla']);
    Route::post('/getCliente', [ClienteController::class, 'getCliente']);
    Route::post('/local.getClientes', [ClienteController::class, 'localGetClientes']);

    Route::post('/credito.getCreditos', [CreditoController::class, 'getCreditos']);
    Route::post('/credito.tablaAbonos', [CreditoController::class, 'tablaAbonos']);
    Route::post('/credito.abonar', [CreditoController::class, 'abonar']);

    Route::post('/getVentas', [VentaController::class, 'getVentas']);
    Route::post('/registroVenta', [VentaController::class, 'registroVenta']);
    Route::post('/verNota', [VentaController::class, 'verNota']);
    Route::post('/descargar-nota-compra', [VentaController::class, 'descargarNotaPdf']);
    Route::post('/reporteExcel', [VentaController::class, 'reporteExcel']);
    Route::post('/ventas.cancelar', [VentaController::class, 'cancelar']);
    Route::post('/ventas.cancelarProducto', [VentaController::class, 'cancelarProducto']);
    Route::post('/ventas.verCancelacion', [VentaController::class, 'verCancelacion']);

    Route::post('/servicios/getServicios', [ServicioController::class, 'getServicios']);
    Route::post('/servicios/cobroServicio', [ServicioController::class, 'cobroServicio']);
    Route::post('/servicios/recaudacion', [ServicioController::class, 'recaudacion']);
    Route::post('/servicios/finalizarRecaudacion', [ServicioController::class, 'finalizarRecaudacion']);

    Route::post('/getDatosCredito', [ClienteController::class, 'getDatosCredito']);

    Route::post('/productos-get-price', [ProductoController::class, 'getPrice']);
    Route::post('/productos-validate-existence', [ProductoController::class, 'validateExistence']);
    Route::get('/get_productos_all', [ProductoController::class, 'all']);
    Route::post('/editProducto', [ProductoController::class, 'edit']);
    Route::post('/form_edit_productos', [ProductoController::class, 'update']);
    Route::post('/form_add_productos', [ProductoController::class, 'store']);
    Route::post('/stock', [ProductoController::class, 'stock']);
    Route::post('/form_add_unidad_compra', [ProductoController::class, 'addUnidadCompra']);
    Route::post('/form_add_unidad_venta', [ProductoController::class, 'addUnidadVenta']);
    Route::post('/getProductos', [ProductoController::class, 'listAll']);
    Route::post('/getProductosMatriz', [ProductoController::class, 'listAll']);
    Route::post('/mercanciaSinStock', [ProductoController::class, 'sinStock']);
    Route::post('/sincronizar', [ProductoController::class, 'sincronizar']);

    Route::post('/getCajas', [CajaController::class, 'getCajas']);
    Route::post('/caja_status', [CajaController::class, 'status']);
    Route::post('/deposito', [CajaController::class, 'deposito']);
    Route::post('/retiro', [CajaController::class, 'retiro']);
    Route::post('/getDatosCierreCaja', [CajaController::class, 'getDatosCierreCaja']);

    Route::post('/depositos/consultaPorFecha', [DepositoController::class, 'consultaPorFecha']);

    Route::put('/garantia-update-status', [GarantiaController::class, 'updateStatus']);
    Route::post('/garantia/cambiar-producto/{id}', [GarantiaController::class, 'cambiarProducto']);
    Route::post('/garantia/lastPurchase', [GarantiaController::class, 'lastPurchase']);
    Route::post('/garantia/usar-nota-credito/{id}', [GarantiaController::class, 'usarNotaCredito']);

    // Purchasing / inventory module
    Route::post('/addProveedor', [ComprasController::class, 'addProveedor']);
    Route::post('/getProveedores', [ComprasController::class, 'getProveedores']);
    Route::post('/statusProveedor', [ComprasController::class, 'statusProveedor']);
    Route::post('/store', [ComprasController::class, 'storeDepartamento']);
    Route::post('/status_depto', [ComprasController::class, 'statusDepto']);
    Route::post('/store_categoria', [ComprasController::class, 'storeCategoria']);
    Route::post('/delete_cat', [ComprasController::class, 'deleteCategoria']);
    Route::post('/get_datos_generales', [ComprasController::class, 'getDatosGenerales']);
    Route::post('/registrar-compra', [ComprasController::class, 'registrarCompra']);
    Route::post('/setProductosRequisicion', [ComprasController::class, 'setProductosRequisicion']);
    Route::post('/productos.comprar', [ComprasController::class, 'comprar']);
    Route::post('/autorizarCompra', [ComprasController::class, 'autorizarCompra']);
    Route::post('/finalizarCompra', [ComprasController::class, 'finalizarCompra']);
    Route::post('/getReporteCompras', [ComprasController::class, 'reporteCompras']);

    // Requisiciones / traspasos entre sucursales
    Route::post('/getTiendasVinculadas', [TraspasoController::class, 'getTiendasVinculadas']);
    Route::post('/crearSolicitud', [TraspasoController::class, 'crearSolicitud']);
    Route::post('/getRequisicionesActivas', [TraspasoController::class, 'getRequisicionesActivas']);
    Route::post('/getRequisicionesSurtidas', [TraspasoController::class, 'getRequisicionesSurtidas']);
    Route::post('/verRequisicion', [TraspasoController::class, 'verRequisicion']);
    Route::post('/updateCantidadSolicitada', [TraspasoController::class, 'updateCantidadSolicitada']);
    Route::post('/autorizarTraspaso', [TraspasoController::class, 'autorizar']);
    Route::post('/getMovimientoMercancia', [TraspasoController::class, 'getMovimientoMercancia']);
    Route::post('/getMovimientoMercanciaDetalles', [TraspasoController::class, 'getMovimientoMercanciaDetalles']);
    Route::post('/enviarMovimientoMercancia', [TraspasoController::class, 'enviarMovimientoMercancia']);
    Route::post('/ingresarMovimientoMercanciaDetalles', [TraspasoController::class, 'ingresarMovimientoMercanciaDetalles']);
    Route::post('/rechazarMovimientoMercanciaDetalles', [TraspasoController::class, 'rechazarMovimientoMercanciaDetalles']);
    Route::post('/eliminarMovimientoMercancia', [TraspasoController::class, 'eliminarMovimientoMercancia']);
    Route::post('/descargarTraspasoPdf', [TraspasoController::class, 'descargarPdf']);
    Route::post('/getReporteTraspasos', [TraspasoController::class, 'reporteTraspasos']);

    // Admin-only screens (Propietario/Encargado): user management, settings, audits,
    // and client create/edit. Assumption documented in RequireFullAccess middleware.
    Route::middleware('full_access')->group(function () {
        Route::get('/cartera-de-usuarios', [PageController::class, 'carteraDeUsuarios']);
        Route::get('/configuracion', [PageController::class, 'configuracion']);
        Route::get('/auditoria', [PageController::class, 'auditoria']);
        Route::post('/autidoria/get-locales-auditoria', [AuditoriaController::class, 'getLocalesAuditoria']);
        Route::post('/autidoria/nueva-auditoria', [AuditoriaController::class, 'nuevaAuditoria']);
        Route::post('/auditoria/fechas-auditoria-local', [AuditoriaController::class, 'fechasAuditoriaLocal']);
        Route::get('/usuarios.tabla', [UsuarioController::class, 'tabla']);
        Route::post('/usuarios-store', [UsuarioController::class, 'store']);
        Route::post('/getUsuario', [UsuarioController::class, 'getUsuario']);
        Route::put('/usuarios.update', [UsuarioController::class, 'update']);
        Route::post('/eliminar', [UsuarioController::class, 'eliminar']);
        Route::post('/activar', [UsuarioController::class, 'activar']);
        Route::post('/locales-getSucursales', [UsuarioController::class, 'getSucursales']);
        Route::post('/clientes-store', [ClienteController::class, 'store']);
        Route::post('/clientes.update', [ClienteController::class, 'update']);
        Route::delete('/clientes-delete/{id}', [ClienteController::class, 'deactivate']);
        Route::put('/clientes-active/{id}', [ClienteController::class, 'activate']);
        Route::get('/clientes/exportExcel', [ClienteController::class, 'exportExcel']);
        Route::post('/clientes/cargaMasiva', [ClienteController::class, 'cargaMasiva']);

        Route::get('/auditoria/{id}/show', [AuditoriaController::class, 'show']);
        Route::post('/auditoria/{id}/conteo', [AuditoriaController::class, 'guardarConteo']);
        Route::post('/auditoria/{id}/finalizar', [AuditoriaController::class, 'finalizar']);
        Route::post('/auditoria/reporte-local-excel', [AuditoriaController::class, 'reporteLocalExcel']);
        Route::post('/auditoria/reporte-local-pdf', [AuditoriaController::class, 'reporteLocalPdf']);

        // WhatsApp section (Fase 4 fusion) — management console reads/writes real
        // sale-affecting data (confirms pedidos into ventas), kept admin-only like the
        // other management screens above. Assumption, not spelled out by the owner.
        Route::get('/whatsapp', [WhatsappController::class, 'index']);
        Route::get('/whatsapp/status', [WhatsappController::class, 'status']);
        Route::get('/whatsapp/prospectos', [WhatsappController::class, 'prospectos']);
        Route::get('/whatsapp/quejas', [WhatsappController::class, 'quejas']);
        Route::get('/whatsapp/pedidos', [WhatsappController::class, 'pedidos']);
        Route::post('/whatsapp/sync', [WhatsappController::class, 'sync']);
        Route::post('/whatsapp/enviar-catalogo', [WhatsappController::class, 'enviarCatalogo']);
        Route::post('/whatsapp/pedidos/{pedido}/confirmar', [WhatsappController::class, 'confirmarPedido']);
        Route::post('/whatsapp/pedidos/{pedido}/avanzar', [WhatsappController::class, 'avanzarPedido']);
    });
});
