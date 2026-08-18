<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Favicons -->
    <link rel="shortcut icon" type="image/x-icon" href="/favico/favicon.ico">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap.css">
	<link rel="stylesheet" href="/toast/resources/css/jquery.toastmessage.css"  rel="stylesheet">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" integrity="sha384-OHBBOqpYHNsIqQy8hL1U+8OXf9hH6QRxi0+EODezv82DfnZoV7qoHAZDwMwEJvSw" crossorigin="anonymous">
	<title>Ventas</title>

	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="/dist/css/adminlte.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="/css/app-private.css%3Fid=3a462871c0ee7353baff263b11e1f5fc.css">
	<link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<style>
	.img-button {
		padding-left: 8px;
		cursor: pointer;
	}

	.modal {
		overflow-y: auto
	}
</style>
	</head>

	<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
		<div class="modal fade" id="modal_edit_stock" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">AJUSTE DE INVENTARIO</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_stock">
				<div class="modal-body">
					<input type="hidden" id="producto_id" name="producto_id">
					<input type="hidden" id="producto_factor" name="producto_factor">
					
					<div class="form-group">
						<label for="tipo_unidad_stock">AGREGAR POR:</label>
						<select class="form-control" id="tipo_unidad_stock" name="tipo_unidad_stock" onchange="cambiarEtiquetaStock()">
							<option value="compra" id="option_compra">Unidad de Compra</option>
							<option value="venta" id="option_venta">Unidad de Venta</option>
						</select>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="stock_actual_display">STOCK ACTUAL:</label>
								<div class="input-group">
									<input type="text" class="form-control" id="stock_actual_display" readonly style="background-color: #f8f9fa; font-weight: bold;">
									<div class="input-group-append">
										<span class="input-group-text" id="unidad_stock_actual">Unidad</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="nuevo_stock_display">NUEVO STOCK:</label>
								<div class="input-group">
									<input type="text" class="form-control" id="nuevo_stock_display" readonly style="background-color: #e9ecef; font-weight: bold; color: #28a745;">
									<div class="input-group-append">
										<span class="input-group-text" id="unidad_nuevo_stock">Unidad</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="stock" id="label_stock">CANTIDAD A AGREGAR</label>
						<input type="number" class="form-control" id="stock" min="0.01" step="0.01" name="stock" oninput="calcularNuevoStock()">
						<small class="form-text text-muted" id="conversion_info"></small>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary" id="btn_guardar_stock"><i class="fa fa-save"></i> Guardar cambios</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_departamentos">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Departamentos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<button type="button" class="btn btn-warning" onclick="nuevo_depto()">Nuevo departamento</button>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Departamento</th>
							<th scope="col">Categorías</th>
							<th scope="col"><i class="fas fa-cogs"></i></th>
						</tr>
					</thead>
					<tbody id="tbl_departamentos">
					</tbody>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_proveedores">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Proveedores</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<button type="button" class="btn btn-warning" onclick="nuevo_proveedor()">Nuevo proveedor</button>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Proveedor</th>
							<th scope="col">Representante</th>
							<th scope="col">Status</th>
						</tr>
					</thead>
					<tbody id="tbl_proveedores">
					</tbody>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_nuevo_proveedor">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Registro de proveedor</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="addProveedor">
				<div class="modal-body">
					<div class="form-group">
						<label for="nombreprove">Nombre del proveedor</label>
						<input type="text" class="form-control" name="nombre" id="nombreprove" required="required" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="representante">Representante</label>
						<input type="text" class="form-control" name="representante" id="representante" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="celular">Celular</label>
						<input type="text" class="form-control" name="celular" id="celular" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="telefono">Teléfono</label>
						<input type="text" class="form-control" name="telefono" id="telefono" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="emails">Correo</label>
						<input type="email" class="form-control" name="emails" id="emails" placeholder="ejemplo@mail.com" autocomplete="off">
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" id="btn_form_depto"><i class="fas fa-save"></i> Registrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_nuevo_departamento">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Registro de departamento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_depto">
				<div class="modal-body">
					<div class="form-group">
						<label for="departamento">Nombre del departamento</label>
						<input type="text" class="form-control" name="departamento" id="departamento" aria-describedby="deptoHelp" placeholder="Ingrese el nombre del departamento" required="required">
						<small id="deptoHelp" class="form-text text-muted">Considere un departamento como un área que incluye categorías</small>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" id="btn_form_depto"><i class="fas fa-save"></i> Registrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_add_cat">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Categoría</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_add_cat">
				<div class="modal-body">
					<input type="hidden" name="departamentos_id" id="add_cat_depto_id">
					<div class="form-group">
						<input type="text" class="form-control" name="categoria" id="categoria" placeholder="Ingrese el nombre de la categoría" required="required">
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" id="btn_form_add_cat"><i class="fas fa-save"></i> Registrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_add_producto">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Registro de producto</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_add_productos">
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group">
								<label for="clave">Clave</label>
								<input type="text" class="form-control" id="clave" name="clave" style="text-transform: uppercase;" autocomplete="off">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group">
								<label for="clave_alterna">Clave Alterna</label>
								<input type="text" class="form-control" id="clave_alterna" name="clave_alterna" style="text-transform: uppercase;" autocomplete="off">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2" style="text-align: center;">
							<div class="form-group">
								<label for="servicio">Servicio</label>
								<input type="checkbox" class="form-control" id="servicio" name="servicio">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2" style="text-align: center;">
							<div class="form-group">
								<label for="sim">SIM</label>
								<input type="checkbox" class="form-control" id="sim" name="sim">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label for="descripcion">Descripción</label>
								<input type="text" class="form-control" id="descripcion" name="descripcion" style="text-transform: uppercase;" autocomplete="off">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label for="categorias_id">Categoría</label>
										<select class="form-control select2" id="categorias_id" name="categorias_id" onchange="change_select_categoria()">
											<option value="0" depto="0">SELECCIONAR</option>
										</select>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label for="departamentos_id">Departamento</label>
										<select class="form-control" id="departamentos_id" name="departamentos_id"></select>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
							<div class="form-group">
								<label for="select_unidad_compra_id">Unidad Compra <button class="btn btn-primary btn-xs" onclick="modal_add_unidad_compra()" type="button"><i class="fas fa-plus"></i></button></label>
								<select class="form-control" name="unidad_compra_id" id="select_unidad_compra_id"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
							<div class="form-group">
								<label for="select_unidad_venta_id">Unidad Venta <button class="btn btn-primary btn-xs" onclick="modal_add_unidad_venta()" type="button"><i class="fas fa-plus"></i></button></label>
								<select name="unidad_venta_id" id="select_unidad_venta_id" class="form-control"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
							<div class="form-group">
								<label for="factor">Factor</label>
								<input type="number" class="form-control" id="factor" name="factor" min="1">
							</div>
						</div>
						<div class="col-xs-7 col-lg-7" style="text-align: center;">
							<p style="color: #12a10f;font-weight: 700;">PRECIO DE COMPRA </p>
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
									<div class="form-group">
										<label for="precio_compra">Precio Compra</label>
										<input type="number" class="form-control" id="precio_compra" name="precio_compra" step="0.01">
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-5 col-lg-5" style="text-align: center;">
									<div class="form-group">
										<label for="neto">IVA 16%</label>
										<input type="checkbox" name="neto" class="form-control" id="neto">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-5 col-lg-5">
							<p style="color: #fd7e14;font-weight: 700;" id="title_compra_iva">PRECIO DE COMPRA SIN IMPUESTOS</p>
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
									<div class="form-group">
										<label for="edit_" id="unidad_compra_1_text">X</label>
										<input type="text" class="form-control" id="p1" name="p1" disabled="disabled">
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
									<div class="form-group">
										<label for="p2" id="unidad_compra_2_text">X</label>
										<input type="text" class="form-control" id="p2" name="p2" disabled="disabled">
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="contenedor_precios">
							<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
								<div class="form-group">
									<label for="precio_1">PRECIO NIVEL 1</label>
									<input type="number" step="0.01" class="form-control" id="precio_1" name="precio_1" placeholder="Precio Venta Neto"><br>
									<!--input type="text" class="form-control" placeholder="Unidades por mayoreo" disabled="disabled"-->
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
								<div class="form-group">
									<label for="precio_2">PRECIO NIVEL 2</label>
									<input type="number" step="0.01" class="form-control" id="precio_2" name="precio_2" placeholder="Precio Venta Neto"><br>
									<!--input type="text" class="form-control" id="unidad_mayoreo_2" name="unidad_mayoreo_2" placeholder="Unidades por mayoreo"-->
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
								<div class="form-group">
									<label for="precio_3">PRECIO NIVEL 3</label>
									<input type="number" step="0.01" class="form-control" id="precio_3" name="precio_3" placeholder="Precio Venta Neto"><br>
									<!--input type="text" class="form-control" id="unidad_mayoreo_3" name="unidad_mayoreo_3" placeholder="Unidades por mayoreo"-->
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
								<div class="form-group">
									<label for="precio_4">PRECIO NIVEL 4</label>
									<input type="number" step="0.01" class="form-control" id="precio_4" name="precio_4" placeholder="Precio Venta Neto"><br>
									<!--input type="text" class="form-control" id="unidad_mayoreo_4" name="unidad_mayoreo_4" placeholder="Unidades por mayoreo"-->
								</div>
							</div>
						</div>
						<div class="row" id="contenedor_existencias">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<div class="form-group">
									<label for="existencia_compra" id="label_existencia_compra">Existencia por Unidad Compra</label>
									<input type="number" step="0.01" class="form-control" id="existencia_compra" name="existencia_compra" placeholder="0.00" min="0">
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<div class="form-group">
									<label for="existencia_venta" id="label_existencia_venta">Existencia por Unidad Venta</label>
									<input type="number" step="0.01" class="form-control" id="existencia_venta" name="existencia_venta" placeholder="0.00" min="0">
								</div>
							</div>
						</div>
						<div class="row" id="contenedor_precios_sim" style="display: none;">

						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_add_producto_masivo" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTitle">Cargar Productos Masivamente</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_producto_masivo" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group">
						<label for="archivo_excel">Selecciona un archivo Excel</label>
						<input type="file" class="form-control-file" name="archivo_excel" id="archivo_excel" accept=".xlsx,.xls" required>
					</div>
					<div id="alerta_error" class="alert alert-danger d-none"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary">Subir Archivo</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_edit_producto">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Editar producto</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_edit_productos">
				<div class="modal-body">
					<div class="row">
						<input type="hidden" id="edit_producto_id" name="id">
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group">
								<label for="edit_clave">Clave</label>
								<input type="text" class="form-control" id="edit_clave" name="clave" style="text-transform: uppercase;" autocomplete="off">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group">
								<label for="edit_clave_alterna">Clave Alterna</label>
								<input type="text" class="form-control" id="edit_clave_alterna" name="clave_alterna" style="text-transform: uppercase;" autocomplete="off">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="text-align: center;">
							<div class="form-group">
								<label for="servicio">Servicio</label>
								<input type="checkbox" class="form-control" id="servicio" name="servicio">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label for="edit_descripcion">Descripción</label>
								<input type="text" class="form-control" id="edit_descripcion" name="descripcion" style="text-transform: uppercase;" autocomplete="off">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label for="edit_categorias_id">Categoría</label>
										<select class="form-control select2" id="edit_categorias_id" name="categorias_id" onchange="edit_change_select_categoria()">
											<option value="0" depto="0">SELECCIONAR</option>
										</select>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label for="edit_departamentos_id">Departamento</label>
										<select class="form-control" id="edit_departamentos_id" name="departamentos_id"></select>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
							<div class="form-group">
								<label for="edit_select_unidad_compra_id">Unidad Compra <button class="btn btn-primary btn-xs" onclick="modal_add_unidad_compra()" type="button"><i class="fas fa-plus"></i></button></label>
								<select class="form-control" name="unidad_compra_id" id="edit_select_unidad_compra_id"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
							<div class="form-group">
								<label for="edit_select_unidad_venta_id">Unidad Venta <button class="btn btn-primary btn-xs" onclick="modal_add_unidad_venta()" type="button"><i class="fas fa-plus"></i></button></label>
								<select name="unidad_venta_id" id="edit_select_unidad_venta_id" class="form-control"></select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
							<div class="form-group">
								<label for="edit_factor">Factor</label>
								<input type="number" class="form-control" id="edit_factor" name="factor" min="1">
							</div>
						</div>
						<div class="col-xs-7 col-lg-7" style="text-align: center;">
							<p style="color: #12a10f;font-weight: 700;">PRECIO DE COMPRA </p>
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
									<div class="form-group">
										<label for="edit_precio_compra">Precio Compra</label>
										<input type="number" class="form-control" id="edit_precio_compra" name="precio_compra" step="0.01">
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-5 col-lg-5" style="text-align: center;">
									<div class="form-group">
										<label for="edit_neto">IVA 16%</label>
										<input type="checkbox" name="neto" class="form-control" id="edit_neto">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-5 col-lg-5">
							<p style="color: #fd7e14;font-weight: 700;" id="title_compra_iva">PRECIO DE COMPRA SIN IMPUESTOS</p>
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
									<div class="form-group">
										<label for="edit_p1" id="edit_unidad_compra_1_text">X</label>
										<input type="text" class="form-control" id="edit_p1" name="p1" disabled="disabled">
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
									<div class="form-group">
										<label for="edit_p2" id="edit_unidad_compra_2_text">X</label>
										<input type="text" class="form-control" id="edit_p2" name="p2" disabled="disabled">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<div class="form-group">
								<label for="edit_precio_1">SUBDISTRIBUIDOR</label>
								<input type="number" step="0.01" class="form-control" id="edit_precio_1" name="precio_1" placeholder="Precio Venta Neto"><br>
								<!--input type="text" class="form-control" placeholder="Unidades por mayoreo" disabled="disabled"-->
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<div class="form-group">
								<label for="edit_precio_2">PREFERENTE</label>
								<input type="number" step="0.01" class="form-control" id="edit_precio_2" name="precio_2" placeholder="Precio Venta Neto"><br>
								<!--input type="text" class="form-control" id="edit_unidad_mayoreo_2" name="unidad_mayoreo_2" placeholder="Unidades por mayoreo"-->
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<div class="form-group">
								<label for="edit_precio_3">POR CAJA</label>
								<input type="number" step="0.01" class="form-control" id="edit_precio_3" name="precio_3" placeholder="Precio Venta Neto"><br>
								<!--input type="text" class="form-control" id="edit_unidad_mayoreo_3" name="unidad_mayoreo_3" placeholder="Unidades por mayoreo"-->
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<div class="form-group">
								<label for="edit_precio_4">EXTRA</label>
								<input type="number" step="0.01" class="form-control" id="edit_precio_4" name="precio_4" placeholder="Precio Venta Neto"><br>
								<!--input type="text" class="form-control" id="edit_unidad_mayoreo_4" name="unidad_mayoreo_4" placeholder="Unidades por mayoreo"-->
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_add_unidad_compra">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Unidad de compra</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_add_unidad_compra">
				<div class="modal-body">
					<div class="form-group">
						<input type="text" style="text-transform: uppercase;" class="form-control" name="unidad_compra" id="unidad_compra" placeholder="Ingrese la unidad de compra, ej. CAJA" required="required">
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" id="btn_form_add_unidad_compra"><i class="fas fa-save"></i> Registrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_add_unidad_venta">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Unidad de venta</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_add_unidad_venta">
				<div class="modal-body">
					<div class="form-group">
						<input type="text" style="text-transform: uppercase;" class="form-control" name="unidad_venta" id="unidad_venta" placeholder="Ingrese la unidad de compra, ej. CAJA" required="required">
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" id="btn_form_add_cat"><i class="fas fa-save"></i> Registrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_requisicion">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><img src="data:image/png;base64,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" /> Solicitar mercancía</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label for="selectProducto">Mercancía</label>
						<select name="" class="form-control select2 select2bs4" id="selectProducto">
							<option value="0">Seleccionar</option>
						</select>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
						<label for="select_proveedor_id">Proveedor</label>
						<select name="proveedor_id" class="form-control" id="select_proveedor_id"></select>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
						<br>
						<button type="button" class="btn btn-warning" style="padding:0px;" onclick="mercanciaSinStock()">Mercancía sugerida</button>
					</div>
				</div>
				<br>
				<br>
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">CLAVE</th>
								<th scope="col">DESCRIPCIÓN</th>
								<th scope="col">CANTIDAD</th>
							</tr>
						</thead>
						<tbody id="tbl_pedidos">
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" id="btn_pedido">Registrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_pedidos">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><img src="data:image/png;base64,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" /> Mis pedidos de mercancía</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a onclick="viewTab(1)" class="nav-link active" id="activos-tab" data-toggle="tab" href="/mi-local/productos#activos" role="tab" aria-controls="activos" aria-selected="true">Activos</a>
					</li>
					<li class="nav-item">
						<a onclick="viewTab(2)" class="nav-link" id="surtidos-tab" data-toggle="tab" href="/mi-local/productos#surtidos" role="tab" aria-controls="surtidos" aria-selected="false">Surtidos</a>
					</li>
					<li class="nav-item">
						<a onclick="viewTab(3)" class="nav-link" id="cancelados-tab" data-toggle="tab" href="/mi-local/productos#cancelados" role="tab" aria-controls="cancelados" aria-selected="false">Cancelados</a>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade show active" id="activos" role="tabpanel" aria-labelledby="activos-tab">
						<br>
						<table class="table">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Proveedor</th>
									<th scope="col">No. Pedido</th>
									<th scope="col">Estado</th>
									<th scope="col">Acciones</th>
								</tr>
							</thead>
							<tbody id="tblRequisicionesActivas">
							</tbody>
						</table>
					</div>
					<div class="tab-pane fade" id="surtidos" role="tabpanel" aria-labelledby="surtidos-tab">
						<br>
						<table class="table">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Proveedor</th>
									<th scope="col">No. Pedido</th>
									<th scope="col">Estado</th>
									<th scope="col">Acciones</th>
								</tr>
							</thead>
							<tbody id="tblRequisicionesSurtidas">
							</tbody>
						</table>
					</div>
					<div class="tab-pane fade" id="cancelados" role="tabpanel" aria-labelledby="cancelados-tab">...</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_productos_requisicion">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAtCAYAAAA6GuKaAAAABmJLR0QA/wD/AP+gvaeTAAAGvElEQVRYhe2YW2xcVxWGv733OWfGM7ZnfI/jOFacyiElpCm9KFQFRQgJHhBIfSBqQQSKICKkFbcK1Eo8IFUISitoaSXykEAqVfDSKAhUVXIjqoYqbdMkTRNSEahTJ45ju3HsGXtu57J4OBPPeDzjmchBIOH/6cxea/b+z7/XXnutA6tYxSpW8X8D9eTD++9RgdonqFtB1H+bUC0olAB/V6K+aRHoA9JhBlMDVm3CAbS8k0MJ5Hss/D6buxKG9mX+grLAJMGbDicA0DboZNm8cxBkw2eTBGUvy/u9UxObPrwyd8ACGZq5xWZ+U2TZN80MRVCu4DUrUIoxW/PZgQjU4m2vBXHBy5WNrQfdFD6LB+4IiAEdA7t/2fUBnKixXjl0fkgDKFU/KvyowmvRUPSd+eMMp49nqjsrB1Qc/GulMR0rEQbwp0GKO2A66q5fxlNZDTn70P7aPLiQvcUhs8GGgpDLBdX/YHVCMBOqeR3lxMSDYLbsZWKVK4YvLvmq0+tGSIsCP6oRBwKnzq7UVLmM2HIqKw06ASoJKlp1iYaURsPM9qb6frBylUUAnzBZVN9JHfpJXS7OtE9k3EO7y/iuVOWQNUgagmsgharLWNpmovlfhR4/rsn32oipwqUgdP0lDQLpWyOk7qyhelWVO8v41IvlcrgLT4EvjF2Y5dyJCRxHX7E8Vx6wp4ODHUfm+/yoYm5rlPlNkUXXjDiKifta0TnBTdY4BtdV9kZKYzchY5w9foXXXx4hPZtHGzXm+fIV65HfPHhEkP5f7fndbU7SPG/ezG5xpjyu3RtfRNyLa4gvM/uKM0YFBIYP/YPTxy6zbkP8XDqVvf97v/7GO1A8iAolPMcp4GPP/eT3jzDi/sJN5EhvLZ5eH9r+No/OQ2bIITtQcXPVVPlGYnkx3n7tIu++cZk1/bEf7/zBl35ebluSPfb8dNcTT+090Jd4N/+d7FDE8qIKhSCWCg9slZjH6iSYmcQbTZWNxUBNFFXzwZ8MM4N2wKSBdMm1L4FOlNJbLlPg6Evvu0EgT3/5RzsXEa5KGsBS8rjrsyc2UiC1OYIYxcw9NbazqHL26FnyJ8tIM1XdH4CxRb8i29YS/8KWhd/vnZzC90WimJ9V5Vdt8OFnHpx6cu+Bt+0pbzubw5rESvkoD/xWTVBeKBVjOf6ZNmKfSoRjdl9FjTEahoZuCm1L3nsxjfEPZkE4vvfZXVcbJl3EFV1Mk6ogdB9Oo5akPKsUyxpUVBeLn9bSLN614m2gwe4OK706yGU9UEzWsi9Hek0QCRUVRzH5xZYFpRegY6WMEYAUArAT4BdzrHjgz4QVp44hXgSC0KZsC0z1kiDaZIHQfUOkn35of5cr3OF2lcxea7Vbx1m4/eZf8cmfuARcrPCB1l29qEgHs/uOFK9pMD0tJHZ/oiqp3oFWzp2avPOX3/pt5w/37f6wIdKeqMeUhsyG0Kx8IflGFuUKmUGHXH9xiyUXVlOml6Yd/dgbR4uVmSxkDGWD1ZMAu4OW+29HCn5IurN20t+0rZu//vl9LU7kUeD7lfYl8j3z+MHH/Dl5dPaOJpPrvU5OEfvARRXA7TB4CUPrmQLrBwx9g33gDKEiDqa7E9PViumyMO1ZTLuHabPB7gXtYNpjmK5mTFczOu7UJG07Bsvy9ej51Padn7svdfjVQ8fK7QpAEPXUQwe3RdaoF/Lj/kfmNkVI3d20bMfY94dZ7t3RzN2fbA5jO3IXSAokU9aVBA13JUtQuMTw4cucfitD5xr7zenxwre/++zXTyqUqCf27v+01up5AtZ6cU369iiZwaUqWPPBQu0hRi0lbQ8BxX7PmyxVenZ//Su7EkE2TJPAmRMZXh+eYy7to7W6FKC+ahlbveC2Wz0z26Lku81CO1UOVRB6XkzVrvJMR4nwjdYY1eCX0vOWj8fYfFuMsQt5jg6ne6bGvYOWuPTMb3DI99TOfuIoJj/fEirdWXEMVlhjLEGQhWB+0ZAxsH5jhC3Tvj38p9l1jXUugNtWrehgZZVcJZQN/qW6bg31iASQPJal/dV5ImNlpadybp7KKgq0NeTaWDcuYHIBuGAKZe2WKiO8UpUlF8ZyRWhUQ0OkxcDVHRWXgaOJxlfWlSxCkAWvfmhASFpE6n/D0zlBu4If14iG5AM9bN24NjTe5IxRC8UGXCyB88l/uoPalZqqK1/RfDpb+pa3zqYv2cxbF8NcuvibnAVq9MYIiwv+TF23MycyKZAxy4j+WjDt7mu96n+0ka+mkQkPJjzOUmsr0zXGV4xAKS6g1O7/1AKrWMUqVvE/iH8D0vzOKuRqeSQAAAAASUVORK5CYII=" /> Requisición</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Producto</th>
						<th scope="col">Solicitado</th>
						<th scope="col">Por surtir</th>
						<th scope="col">$ Unidad</th>
						<th scope="col">Total</th>
					</tr>
				</thead>
				<tbody id="tbl_productos_requisicion">
				</tbody>
			</table>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_arribo">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAtCAYAAAA6GuKaAAAABmJLR0QA/wD/AP+gvaeTAAAExUlEQVRYhe1YXWwUVRT+zp2dLrQVGiJ/1m5LIRUiTUOg/iAKhCAillqRB/XBFH8e6G6M8i6tvml30YgaTGAKSiIgRkOFaEyQqCBiFLukf5RttwutpU1x6fZnOztzfJi2Yu5diC/dbtLvaeY79zvnm8m5s2cvAUCg6mAFadZbNtMSTXDIsqh6197KY5iioEDVwQom+7gn3+T8RQnRGdbtcLuLAH5u194dR1JtUAVBwqrxFJi8tWJQlKyIo+zpmFi02GSh4e1Um0sGwUCRx5MQoH9Jj8cUbIlCBlNyaeogSFBbJOKybyUjEd0mwR0E4lQZux1ctoWacLt+pP7rbM4vGKVIOINDV1yCiXan2lwyEAD4vcZhEJ4HOwwxvnxjb+W2FHtLChcAMHAzc3a2ubxijd544lxisC/6jN9rTKnWIBDrGaJr7rycV1yqBfNzc1BUkivxlmXj5wtBZdLlSwsxJ+cuif/rej9aQxGJd2kaVpcuV+ZqaLqCv6Ox/3AMkLAyc3v7op8oTd+9cBZK1xdJvGkm8NPli8pCRSvvwSLPQokPNoXQ0t8h8cItlDUAoD12DTcoKvFzsrLQFbyRK5SqKQpyPsCUVqbHMW16sqDciNGbg/jgwHGJ110afDvUn++Tp39B/fdnJX7pknylJm6ayhoAsGXDwyh/fI3EN/3WiWsNN9SmbWbE46bM2zbcbl1ZyErY/1ujWg8AmqYpNUI4jZGW7TFterKQlqaVGzFrphubVj0g8cyMb3/4VZmoIG8B7lucJ/FDwyNKjRACm9bJNQCgvbMLzW1hORAVyU1nZOgoXlYo8aaZwHdnLigLbduyNuns8eP5Bol3u3VsfGyVMtfRE6fRebVH4vOyFwBI0/aYNj1ZSEvTyo1oJiy0d3ZLvG0zCvLkzQYANwcGlZr4qKnUaC6hXA8AObOyIfLk9zkjPgNAktkjNjiM49+ckXhd1/Day9uVhb6oP4OOiGyieFkhnn1qrcTH48kHpu1l65F/73yJbzjXjlZ0pWd7TJueLKSV6UTCBgi2ciP2d8dALA/htiDUf6qePfq6Y6AhWRMZ7kd9RNbYtg3qUf85OH+yBcGZ8uxxNdSHzKwZjUrTI7F4j6a5WlSxy5e6LWUlQGjkkk5ZB4bjPNDTbasEGrk0Fd8V6reZ5cNPITisZ7nfVJoG06nX33+xMom5lOO2Pe33Gp8HfMajAPCu11jn9xqHx2MBn3HIX1W3AQD2eOse8XuNoxO6KmN/rffgZgCo3WmU+n3GV+Nn3X5f3UeBqgPlzvX+koDXqK+urhYAEPAa7/m9xnYA2LPz0DK/zzi179V9OgDUVhnv1PrqXrijaQDlbGOls5BXAbR1PMCMMiYuBQAmewVAFRMqwpME+0HnmkvAKK+prnFawebNIHrISULFDGyZe31uJgAwsAmE1QBgaYn7wXhiAJg9lnMjmNfc0TSD/wDQ6tyJVjj3YzlwUbBocXzgMsC/3yL9kxjNji9qY+Di7urd1thDNIC52UmJEIBg77zekTFdkJmaAEAwdwBozHDHBwBAEIJETowAoNZrfJyZk/3SxFFvb/SzXR9WpmdPT1VMm54spLdpazQhrrdchTkSn/IPMvGLODo0ooXPXgLS4O3/A/NF2Y2G9JimAAAAAElFTkSuQmCC" /> Arribo de productos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Producto</th>
						<th scope="col">Solicitado</th>
						<th scope="col">Por surtir</th>
						<th scope="col">$ Unidad</th>
						<th scope="col">Total</th>
					</tr>
				</thead>
				<tbody id="tbl_productos_arribo">
				</tbody>
			</table>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="button" id="btn_finaliza_compra" class="btn btn-primary">FINALIZAR PROCESO DE COMPRA</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_change_cantidad_arribo">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><img src="data:image/png;base64,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" /> Editar cantidad</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="productoArriboId">
				<input type="number" id="nuevaCantidad" class="form-control" min="0" value="0">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" id="btnSaveCantidad">Actualizar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_whatsapp">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><img src="data:image/png;base64,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" /> Enviar mensaje</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p id="mensaje_whatsapp"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!--MODAL DE COMPRAS-->
<div class="modal fade" id="modal_compras">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><img src="data:image/png;base64,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" /> Comprar mercancía</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="selectProductoCompras">Mercancía</label>
							<select name="" class="form-control select2 select2bs4" id="selectProductoCompras">
								<option value="0">Seleccionar</option>
							</select>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<label for="selectProveedorCompras">Proveedor</label>
							<select name="proveedor_id" class="form-control" id="selectProveedorCompras"></select>
						</div>
					</div>
					<br>
					<br>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">CLAVE</th>
								<th scope="col">DESCRIPCION</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">COSTO</th>
							</tr>
						</thead>
						<tbody id="tblCompras">
						</tbody>
					</table>
					<div class="row">
						<div class="col align-self-start">
						</div>
						<div class="col align-self-center">
						</div>
						<div class="col align-self-end">
							<form>
								<div class="form-group row">
									<label for="totalPagar" class="col-sm-7 col-form-label">Total a pagar</label>
									<div class="col-sm-5">
										<input type="text" class="form-control form-control-sm" id="totalPagar" readonly>
									</div>
								</div>
								<div class="form-group row">
									<label for="pagoEfectivo" class="col-sm-7 col-form-label">Efectivo (<b style="color: #12a10f; font-size: 13px" id="cajaEfectivo"></b>)</label>
									<div class="col-sm-5">
										<input type="number" step="0.1" class="form-control form-control-sm" id="pagoEfectivo">
									</div>
								</div>
								<div class="form-group row">
									<label for="pagoTransferencia" class="col-sm-7 col-form-label">Transferencia (<b style="color: #12a10f; font-size: 13px" id="cajaTransferencia"></b>)</label>
									<div class="col-sm-5">
										<input type="number" step="0.1" class="form-control form-control-sm" id="pagoTransferencia">
									</div>
								</div>
								<div class="form-group row">
									<label for="pagoTarjeta" class="col-sm-7 col-form-label">Tarjeta (<b style="color: #12a10f; font-size: 13px" id="cajaTarjeta"></b>)</label>
									<div class="col-sm-5">
										<input type="number" step="0.1" class="form-control form-control-sm" id="pagoTarjeta">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" id="btn_comprar">Registrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_historial_compras">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEhUlEQVR4nO2Va0xbZRjH36iJLU6TEaMmxiXuiyaOTS7DoeHejp4DOFkgQCBsU8SQyCfdZqbAPrgPOsUeEOZA2bh4ewcSsgyyDTag0nIplMu4BzpgQGVQWlDunL85Hc4BLRnQ0i88yS8n5z05eX/nfc7zPITsxE7sxMbCQInj/Z+J62OTR15DMnmCbEcM5hB2KIfMDuUQbIR7OUQ7cJm421ywL4vka7MINkUmMWozySGbCnZlENqVQbBZOjOIsTudeNtMsEVOaIucYCsMFzvzM/VhMzPq8KnZhoiJ2cbIsTlNlG6uKXpovjmmf6H1WO9C64nuxbb32xfbYluXOuI06PxIvcxV9MS/YlFQfZ5Q9XmCzTJc7Ap0fLg12mMzLAoqzxGqPEewGQYLXYD2D6wBtSjYeWlvZd9vTtgIWrof4+USoO24tbAsaKyS9aEtBnaGrifYjjvRsCutUZYF/1EyjWiNhH2JsCw4rWRVaAmHnaEWBWdr2dtoCYWdoRYF5+uCStF8FHal6ahlwaWG4CI0HYGdoRYFoQmi0ATDvgStJ8hQaFjYF2a9FMso3yiDLVlUB+BvpRQGhQT6Sn9MVEkwVS3FXF2A6bngsI6glPINUtiChXoJ9BW+aL/mDe4XX8Rm+yMkU4KYH/3xRa4fKou8MFrug2mlX4FlQbU/5dX+sCbdCgkSrxxGb6knErJ94ZjKQMyxZgm4KMG1Qq+ydQR9KK/2gTXJKvYzbb4nTWa6uubF44eGYrSP9GHcoEff6CCKOxSIvPolHLhA7OaYBYfvAgPMC9Z5Ur7eC9ZiodYTcbnS5RMKREhRMgxGIyYnJ81S2lWDly+GQ8wx02K57KAZwbcpX/cOrMV4+Vs4eOnYwxQ+l/YuNPe6zMoZjUaM6HRQaJvgmP4exHKmgyR7P7VSsNaD8rUesAaLKg90lviipEuJmz21aBvuxfD4qEW5FHkalKoa031SVbbpg0SpbOQqQXfK17rDGkxVuKGg7CR6dP0YWRYTRMrKb2FsbGyFnDz1e5Tfqni41n9/GM+mBUMkZwpXCqrcKF/jBmswUeaMbEUaVHdbMWbQmzbW6ydw+kwiQiOicDk3Hz09PWvk/uNQ/sfCv9i/StCZ8ioXWIPxGwfAKdJR0FaBCaNhxeaapmac/OwM9ry6F58nJplN+5E/EiGWs7MrBav35/PKA7AG+hv7kFRyFl+rfsVf+v9T+igpEVJotVqzz/x+/wQijtGtEITCiVmqdprhq52wVYw338CpKzE4UfIVlHdbzEoMnI02uy70x5cuhAkn+OeaVgOF024onFy3yvztfW4JObKEN3PiEH9dviHBvObrD6qYCzxNbB0ijhkRJoTQhB9HcGhch9ezjwsFMvlMhvQFmws+LWekIjnLCxNCaMKPygx+m7BG7jA9tdzYZfFku0LEsZ+K5cy0MCESK38y9bnV/5yQ1gcnxwr9jyPbHQ4pQS4ijr0jCOxKDTL1uZCiJFO1vnghdFmMNWzrya0JGvakML6ECSHm2AExx8wJrUSoVqEgdn0T9Pzqd/4FqAvblnbrRdcAAAAASUVORK5CYII=" alt="history-folder"> Historial de compras</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container">
				<form action="/getReporteCompras" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">                        <div class="form-group">
							<label for="fecha_inicio">Fecha Inicio</label>
								<input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control " 
									value="2026-08-12" required>
															</div>

							<div class="form-group">
								<label for="fecha_fin">Fecha Fin</label>
								<input type="date" name="fecha_fin" id="fecha_fin" class="form-control " 
									value="2026-08-12" required>
															</div>
                        <button type="submit" class="btn btn-primary">Generar Reporte</button>
                    </form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_historial_traspasos">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Historial de traspasos</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container">
					<form action="/getReporteTraspasos" method="POST">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						<div class="form-group">
							<label for="fecha_inicio">Fecha Inicio</label>
							<input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control "
								value="2026-08-12" required>
													</div>

						<div class="form-group">
							<label for="fecha_fin">Fecha Fin</label>
							<input type="date" name="fecha_fin" id="fecha_fin" class="form-control "
								value="2026-08-12" required>
													</div>

						<div class="form-group">
							<label for="local">Local</label>
							<select name="local" id="local" class="form-control " required>
								<option value="">Seleccione un local</option>
								<option value="0">Todos los locales</option>
																	<option value="2">ERIK CORCINO HERNANDEZ</option>
																	<option value="6">DARIO ALBERTO CONTRERAS QUINTERO</option>
																	<option value="7">JOSE ALEJANDRO LIRA MARTINEZ</option>
																	<option value="8">IGNACIO GARCIA MENESES</option>
																	<option value="9">ANDREA DIAZ LUCAS</option>
																	<option value="10">HUGO NAVARRETE VEGA</option>
																	<option value="11">MIGUEL DIONICIO FLORES</option>
																	<option value="12">MARCOS FERMIN PANIAGUA ROCHA</option>
																	<option value="20">JUAN CARLOS AGUILAR CRUZ</option>
																	<option value="21">gerente@fusiond3.mx</option>
																	<option value="22">LUIS RAMIREZ OCAMPO</option>
																	<option value="23">JESUS ALEJANDRO FELIX MORALES</option>
																	<option value="24">Jaime Sanchez Medina</option>
																	<option value="25">JAIME SANCHEZ</option>
																	<option value="26">JAIME SANCHEZ</option>
																	<option value="27">JAIME SANCHEZ</option>
																	<option value="28">JAIME SANCHEZ</option>
																	<option value="29">JAIME SANCHEZ</option>
																	<option value="30">Jorge Antonio Fonticiella González</option>
																	<option value="31">JORGE ANTONIO  FONTICIELLA  GONZALEZ</option>
																	<option value="32">JAIME SANCHEZ MEDINA</option>
																	<option value="33">JORGE ANTONIO  FONTICIELLA  GONZALEZ</option>
																	<option value="34">JOSE ALEJANDRO LIRA MARTINEZ</option>
																	<option value="35">MILTON MORALES PEREZ</option>
																	<option value="36">ARTURO FLORES CARRANZA</option>
																	<option value="37">YAEL PRUEBAS</option>
															</select>
													</div>

						<button type="submit" class="btn btn-primary">Generar Reporte</button>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modal_traspaso">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"> Solicitar mercancía</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label for="select_productos_sucursal">Mercancía</label>
						<select name="" class="form-control select2 select2bs4" id="select_productos_sucursal">
							<option value="0">Seleccionar</option>
						</select>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
						<label for="select_sucursal">Sucursal</label>
						<select name="proveedor_id" class="form-control" id="select_sucursal"></select>
					</div>

					<!--Se agrega un radio button group para selecciona si es una solicitud o si es un traspaso-->
					<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
						<label for="select_sucursal">Tipo</label>
						<div class="form-group">
							<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" id="traspaso" name="tipo_documento" value="traspaso" checked>
								<label class="custom-control-label" for="traspaso">Envío</label>
							</div>
							<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" id="solicitud" name="tipo_documento" value="solicitud">
								<label class="custom-control-label" for="solicitud">Solicitud</label>
							</div>
						</div>
					</div>
				</div>
				<br>
				<br>
				<div class="table-responsive">
					<table id="tbl_solicitud_productos" class="table table-bordered" style="width: 100%;">
						<thead>
							<tr>
								<th scope="col">ID</th>
								<th scope="col">DESCRIPCION</th>
								<th scope="col">CANTIDAD</th>
								<th scope="col">CLAVE</th>
								<th scope="col">ACCIONES</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" id="btn_solicitar">Registrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_movimiento_mercancia">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Movimiento de mercancía</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="overflow-x: auto;">
				<table id="tbl_movimiento_mercancia" class="table table-bordered" style="width: 100%">
					<thead>
						<tr>
							<th scope="col">ID</th>
							<th scope="col">TIPO</th>
							<th scope="col">ORIGEN</th>
							<th scope="col">DESTINO</th>
							<th scope="col">ESTADO</th>
							<th scope="col">FECHAS</th>
							<th scope="col">ACCIONES</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_movimiento_mercancia_detalles">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"> Detalles de solicitud</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table id="tbl_movimiento_mercancia_detalles" class="table table-bordered" style="width: 100%;">
						<thead>
							<tr>
								<th scope="col">TIPO</th>
								<th scope="col">SOLICITADA</th>
								<th scope="col">A ENVIAR</th>
								<!--th scope="col">RECIBIDA</th-->
								<th scope="col">PRODUCTO</th>
								<th scope="col">ACCIONES</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

		<div class="wrapper">
			<!-- Navbar -->
			<nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="/mi-local/productos#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/mi-local" class="nav-link"><i class="fas fa-home"></i> Inicio</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/mi-local/productos" class="nav-link"><i class="fas fa-database"></i> Inventario</a>
      </li>
            <li class="nav-item d-none d-sm-inline-block">
        <a href="/productos/existencia-excel" class="nav-link"><i class="fas fa-file-excel"></i> Lista de precios</a>
      </li>
          </ul>
    <!-- SEARCH FORM -->
    <!--form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Búsqueda" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form-->
    <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="/mi-local/productos#">
                    <i class="fa fa-inbox" style="font-size: 25px;"></i>
                    <span class="badge badge-danger navbar-badge" id="totalNotify">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="/mi-local/productos" class="dropdown-item dropdown-footer">VER PEDIDOS</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="/mi-local/productos#">
                      <i class="far fa-bell animated infinite swing" style="font-size: 25px;"></i>
                    <span class="badge badge-warning navbar-badge" id="hTotalSugerencias">65</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header" style="font-weight: bold;color: red;">65 PRODUCTOS POR SURTIR</span>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia28">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUT206 AUDIFONO BLUETOOTH
              <span class="float-right text-sm text-danger" onclick="no_sugerir(28)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia37">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUT250 DIADEMA BT 1HR
              <span class="float-right text-sm text-danger" onclick="no_sugerir(37)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia190">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> BG-139  DIADEMA DE GATO LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(190)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia199">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> BS-09 BARRA DE SONIDO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(199)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia226">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> MB-152 BOCINA MINI LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(226)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia235">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> P47 DIADEMA COLORES
              <span class="float-right text-sm text-danger" onclick="no_sugerir(235)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia244">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> P47M DIADEMA GATO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(244)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia262">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> PAST-001 PROYECTOR ASTRONAUTA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(262)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia271">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA314T BOCINA LINK BITS 3&quot;  TWS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(271)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia298">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> VA369T BOCINA 3&quot; LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(298)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia316">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> OSO GRADUACION
              <span class="float-right text-sm text-danger" onclick="no_sugerir(316)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia325">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUDIFONOS SONYN202
              <span class="float-right text-sm text-danger" onclick="no_sugerir(325)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia334">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FR-3206 AUDIFONO SAMSUNG AKG S10
              <span class="float-right text-sm text-danger" onclick="no_sugerir(334)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia379">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUDIFONOS ZTE FRESHSUN
              <span class="float-right text-sm text-danger" onclick="no_sugerir(379)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia388">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SMARTWATCH ZTE FRESHFUN
              <span class="float-right text-sm text-danger" onclick="no_sugerir(388)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia397">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> X10 SMARTWATCH EARPHONES X10
              <span class="float-right text-sm text-danger" onclick="no_sugerir(397)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia496">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CUBO IPHONE 20W SIN CAJA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(496)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia514">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CUBO SAMSUNG  45W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(514)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia550">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR IPHONE 16 C A C 35W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(550)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia568">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR SAMSUNG 45W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(568)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia580">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR SAMSUNG V8
              <span class="float-right text-sm text-danger" onclick="no_sugerir(580)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia590">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> MJ-6699 AUDIFONO INALAMBRICO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(590)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia600">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TB-6310 SMARTWATCH T500
              <span class="float-right text-sm text-danger" onclick="no_sugerir(600)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia640">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARRITO 8 WHEEL STUNT
              <span class="float-right text-sm text-danger" onclick="no_sugerir(640)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia670">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR XIAOMI TIPO C 33W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(670)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia740">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SOMBRILLAS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(740)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia770">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> VA370T BOCINA 3
              <span class="float-right text-sm text-danger" onclick="no_sugerir(770)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia871">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> KTS-2048 BOCINA 8&quot; LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(871)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia881">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGNEBROPROMO PROMOCION CARGADOR NEBRO TIPO C
              <span class="float-right text-sm text-danger" onclick="no_sugerir(881)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia891">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> LABUBU MUÑECO TIPO ORIGINAL
              <span class="float-right text-sm text-danger" onclick="no_sugerir(891)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia911">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> MB-168 BOCINA 3&quot; LINK BITS COLORES
              <span class="float-right text-sm text-danger" onclick="no_sugerir(911)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia945">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XB-5516 POWER BANK 2000 MAH
              <span class="float-right text-sm text-danger" onclick="no_sugerir(945)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia957">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FR-3232 AUD EARPODS LIGHTNING CONNECTOR
              <span class="float-right text-sm text-danger" onclick="no_sugerir(957)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia969">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FM-8226 BARRA DE SONIDO A500
              <span class="float-right text-sm text-danger" onclick="no_sugerir(969)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia981">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XM-9010 CUBETA PARA BEBIDAS CON BOCINA Y LUCES
              <span class="float-right text-sm text-danger" onclick="no_sugerir(981)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1008">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA1238TKL BOCINA 12&quot;C/MICROFONO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1008)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1020">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA8061T BOCINA 8&quot;
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1020)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1032">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA438TBOCINA RADIO 4&quot;
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1032)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1056">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIEADEMA DE AIRE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1056)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1068">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIEADEMA DE AIRE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1068)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1092">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FR-2007 DIADEMA BOSE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1092)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1104">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIADEMA DE AIRE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1104)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1116">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUT114 AUDIFONO BLUETOOTH
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1116)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1152">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> ESTRELLAS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1152)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1327">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FM5125 BOCINA SPLASHPROOF
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1327)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1571">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TWS G-TIDE H11
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1571)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1643">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIADEMA SONY WH-1000XM5
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1643)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1667">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> GAR264 BATERIA PORTATIL 10000 MAH 3A
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1667)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1694">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TWS G-TIDE L22
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1694)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1886">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CAB177 CABLE V8 2.1A 1 METRO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1886)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1934">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA KUROMI 7&quot; ANDROID 15 256/8 GB
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1934)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1958">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA STICH 7&quot; ANDROID 15 256/8 GB
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1958)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1982">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA BOB ESPONJA 10&quot; A08   AZUPIK DOBLE SIM ANDROID 15 512/12 GB
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1982)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2006">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA UMIIO S25 ULTRA 10.1&quot; 128/12GB ANDROID 13
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2006)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2099">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> KTS-1841 BOCINA 6.5&quot; LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2099)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2165">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> GAR159 BATERIA PORTATIL 20000 2.1
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2165)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2401">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> P9PROMO DIADEMA P9 PROMOCION
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2401)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2426">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> VA370PROMO PROMOCION BOCINA VA370T LINK BITS 3&quot;
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2426)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2451">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XR3101 EXTRA BASS EARPHONE XINMI
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2451)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2476">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XR3109 STEREO HEADSET  XINMI
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2476)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2501">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TR6061 OWS T2 AUD DE BOLA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2501)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2526">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> JXQ1403 EXTENSION 5 METROS XINMI
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2526)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2551">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FEE-40313 DIADEMA GUERRERAS K-POP
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2551)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2676">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> PLAYERA SELECCION MEXICANA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2676)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2997">
            <a href="/mi-local/productos#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TERMO MUNDIAL LARGO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2997)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <a href="/mi-local/productos" class="dropdown-item dropdown-footer">IR A MI ALMACÉN</a>
        </div>
      </li>
            <li class="nav-item">
        <a class="nav-link" href="/mi-local/productos#" data-toggle="modal" data-target="#updatePasswordModal">
          <i class="fas fa-key"></i> Cambiar Contraseña
        </a>
      </li>
      <!--li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i
          class="fas fa-th-large"></i>
        </a>
      </li-->
    </ul>
  </nav>

  <!-- Modal para actualizar contraseña -->
  <div class="modal fade" id="updatePasswordModal" tabindex="-1" role="dialog" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updatePasswordModalLabel">Actualizar Contraseña</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="updatePasswordForm">
          <div class="modal-body">
            <div class="form-group">
              <label for="current_password">Contraseña Actual</label>
              <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
              <label for="new_password">Nueva Contraseña</label>
              <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
              <label for="new_password_confirmation">Confirmar Nueva Contraseña</label>
              <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Actualizar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('updatePasswordForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(this);
      fetch('/usuarios/update-password', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Contraseña actualizada correctamente');
          $('#updatePasswordModal').modal('hide');
        } else {
          alert('Error al actualizar la contraseña: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar la contraseña. Verifique la consola para más detalles.');
      });
    });
  </script>
			<!-- /.navbar -->
			<!-- Main Sidebar Container -->
			<aside class="main-sidebar elevation-4 sidebar-light-warning">
				<!-- Brand Logo -->
				<a href="/mi-local/productos#" class="brand-link" style="text-align: center ;">
					<!--img-circle  elevation-3-->
										<img alt="panel-logo" class="brand-image" style="opacity: 1; float:none !important;" src="/logo-fd3.jpeg">
					<!--img  alt="tienda-logo" class="brand-image" style="opacity: .8" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAABmJLR0QA/wD/AP+gvaeTAAAFRUlEQVRYhe2XW2wUVRzGv3PmzOyt0227dOlFe4EqVECzsfFBQXkx0RDe5IEoRo1BEngQL/jgy4YnovWS6JskoETj5UVRiYkkVuqDEKFgwSLR3rulpdvttrs7szNnzvGh7XZ3u4stI8YHv2Symf98c87vfDNzzllgBXrnxRP1bx04dnYlXjeiKzE5nAcB3H2bWVYGk6/O/UebbwcIcAswBErv2/s+bPxPwIBAJV6h3gYWkPyT6DPHvLpO9gKyocgWgpR7QPAuJHlZQh4lBEk3HUuJsVQKH0SPP2su1li+oboaRyor+Qst67i3TBuvLfzucwMCAAP9zGRMWQfgYEkYAlFTXSVYOMzd9vW3SkwTlkqSUH6tAMbM0kP9/cru2TFVqHlv07RJiI9J+Bjkajs1OIjBCWq8MnevLYCpLAF35KGyMAqVj/gIwc6gRpW8+iemha0BhjqNFrxjK9G4JdE1y7FD13L3Cgl8FrdFWpCHAXy+WM+NPxqNUkrI4Y4KRVGkBBaOtCNgSKCWkVyt4BASvWlnvocS18MMyAiJjLNUo5CIBChjVB6JRqM5hlwy+lTTLkZIbbPIEDuTNzJOUUMVCCMNUWLkXAI/pzW0SQOsTG7VRMV4ykATW2qhFcA5aKHKGy1PLKZDF1MByOsRzXaUooYmHYJaWgpj5apVBCadQlIK4H7N8RLIw4vpUGAxFdRuUMWa4oamBUVYWfV7W6CwIjEtlsfWpjqaShHWp5p25WAIyMF7VR4vNR0/5LHRzNwl08IEHvQsny4ogIjmMALyUg4GwOYWJtuLzY4EJgTFjFP+IzJk4W8pzTgEE4LCKeGpV4ROQO7JwVCCs5ctJZVvSgiCLw1mX4I2d8pkosdSlk0yQ5yKrwyVV3uV2ZOGJoZ4YbYSQI+l8FOmxn+VWvqkoTqJosd1wWJXBOQZYOFrsqXy/B9c/pgwmL+eSjZLaHKU04qO7U2yY3uznowb+PbjXjFi2OYm2BYTIn2NepxJiYbHn2xXWjaEKgd+j+P7L/rkACXJ9dz0GlIZ+02yaqqr/t1PbfEGQz72S9ew/KZrSDQxkdal0Ecdkk0I4qcM+4G8hfLNVz4K+G1xMVjlCbdG6vSN960letXSEsVtgd5zMfT3TYHbDhpbqhDZdicCFVrOk05ZuNA9gtjgDJiqYF37Gmx5oAEsbzqfmzHR1zOBwYsTc4kZ47qpKZFXO59OF8AAwPuHTnRve2z91vZIXa4WG0oiM2ctf9irlF/X0NAczJ339VxH93d//nTgjT3bFmus5J0LGh2YwdcnLsMb9Jf1ZNNZ2IYF1afBE/CU9ZnJDHbu2Yw7WqvKem4KY6Qs+EM6GrZHynp4JgsjnoQvFATzl4eJdfXASN084ZvCrETM74HuD7ttBsCtbDuLNN03hKuf/oB437BrGNfJ1GxsQnB9IxS1eFVbvVwnw00L2cQseNZ2DVO07QS90D2Cqz0T2NQRhnQkMtNzGDx9vjxM2gQ3LDCfBhYot3UG+Gwagtfi2qVxXDk/iUzKAikKo3Cnp8AXrlNQ36iiOpDBRMyEoAKGEi8/nMr5w4YNG+myNoVqsFMJhBs8uGuDivExByOD8JWFAYDatRpa25bmFcIAGXC3agMAWeAMVDC0tjFwLjAyaBR4XL8z/6QYAHTuP/YoCGm1bRm6PpqF48xfjE9aEDaAWfdfirCB2FgWWXt+7b8xnoVty1DngeN7FSL6D7733GkGAJRghwA2cVv0x8ayydhYNteIV1N9sFT3CWoQo8OmMTqc+wMJbos4gF0C5DKA0677+F//hv4CTIE3Sy62000AAAAASUVORK5CYII="/-->
					<!--span class="brand-text font-weight-light" style="color:#0099ff;font-family: 'Abel', sans-serif;font-weight: bold !important;">Bodega Principal</span-->
									</a>
				<!-- Sidebar -->
				<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="row">
    <span class="brand-text font-weight-light" style="margin-top: 8px; width: 100%; text-align:center; color:#0099ff;font-family: 'Abel', sans-serif;font-weight: bold !important; font-size: 20px">Bodega Principal</span>
  </div>
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
      <img src="/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
    </div>
    <div class="info">
      <a href="/mi-local/productos#" class="d-block">Administrador</a>
      <form action="/logout" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">        <button class="btn btn-danger btn-sm"><i class="fa fa-power-off	"></i> Cerrar sesión</button>
      </form>
    </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

      <!--MENU LATERAL IZQUIERDO-->
                                  <li class="nav-item">
            <a href="/mi-local" id="menu_local" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAACg0lEQVR4nO2W3U9ScRjHTy8XXdV9F63Vv9HWRdS6aCuZ6FzzAuMmt7xJh7zU6ZS9YhfK0sHIYpgQNgUmhyASoRfAYVMCs0gy001yCCVYlPNpz7ExBYTW4OAFZ/tsZ3uefb+fnXF+B4LYcJEkuVMh5Bzoaj12qNRgD/YRuS6FkLOP7q7z+AcvxAOGpkSpwR66q87TQZ7cmyWju13VuPjqMiTfXGcN7NPe4Z7PlpFxRTEvxapMzEsB9mbJ9N48JQ+YL0LwaQtrYB/2Zsn4rS1BWNIA20xYmieBIHakRbQy7rmfc8q1csik5lVr+vZqASOi5/F2vXdIw+UQgb9MPRd/Ismju4knHTzJ6sL9sonAkgZWF3pgoLNGSgwp6iNBWyuUmyFFfYQYUfNn8RUrNyNq/izxUiOYYfNsSW4BemwpE3VfYWBrxsi41A3+XEP34CUGtmboQZjktfpl37VNg+WxNnCa7oLT2M7cl3zmawOjvFZPqEjOfrOy4WPMs/5dinspcPZLIDC/CIG5r+DUiyE+erVkM+yllYIQeqz/j1GZzlhpDbyw3APXsA6moisQ+g4MeO8a7gMXXfwZ9tksvUApjafTnwPJQ88Jz0wivRxiEezF/oIy45EU3Oh/BzJDKC+Nna8L7mAO5v23zOiXFZBqwyCzxPNSQ9kL7mAO5lVkZJUnY6n8ZuKVtwkq58y2PYGfhRNg//xjEwOBGIj7iiODOZiX2YG9WTLW6SSYM9CNR0FUJBnMwbzMDuzdvjKiHvdxw9toiv7w7ddGHo9FfjcpJ0D0aDov1aSt4A7mYF5mB/Zif1qGfODYI+x2H8ykWeE7zL/lOFKIs5S96l/2MC9XD/ajyB+DTlm2C91ZuAAAAABJRU5ErkJggg==">
              </i>
              <p>
                Bodega Principal              </p>
            </a>
          </li>
                    <li class="nav-item">
            <a href="/punto-de-venta" id="menu_punto" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAACmElEQVR4nGNgwANCQ1cxJ1V3y2dW9ClRglOqJymmpc1kZSAXVHbMzeyYte761OV7PkxdvucLZXj3p+7ZG+9U9SyoJ9khWSVdEv0Ltz7ecPDKf2ri6av2vi+sn25GkmMKWmeFLd12kqoOAeF1+y/9Tylue5pQ1DIvu7RTiijHlLTMSVi+4zTckN5tK/737VhGFu7cvAjFQVGZ1f9LGyb+L2mbdZosx/TvWfJ/14O9ZOEJ+xZiOqZx4v/63oXPcusn8g0Ox0xY+Cy+vl9g1DEbqBUyvdROwJQ4ZgMVMcWOufHw1f97T9+C2fvP3vn/6MX7/8cuPSAod2XFxv83F6+mrmM+fPn+/9fvP2D2ySsP/4PA5TvPCcq9nDnn/6dJk6nrmB3Hb/zfeeImmL358LX/u07e/L/lyDWCctv3nP2/Y/eZweGYHUfP/9957NzgiKYPz9b8//V66eBIwDdu7P5/7/b20az9Hz1ktl488X/r5WODI83suH3w/857+wZHbtp45CIYDwrH7ECSG/Bo+oAkR7XG1Q1yszaSHNnNzgKaNsg7nicUtC5Mq+qTJMoxyZUTxGnRVZmxat87krsqIFDWtSC9fdb6a9OX7vg1a/nu/8Tg6Ut3/p+0eDsYg9gw8RlLdvzpmrPhdmX3/DoGckF9fT3TsZT0PU96+v8TgzeU1f63dQ8DYxAbJn48PetsfX09CwOl4EZ8/Obv/f3/icG7y6r+mzn4gjGIDRO/lZx8lGKHgMCoY3CB64Mpmq5ERS381tdHkWOux8ZuoYpjTnh4yFyLjT31tKjo7dOSki/48Nrk9O8wx4DYT4uLP9xKSLhwKSDAmCqOgYFjTk7Sp5ydlfDhZe7u2vWOXnYgDGIfdnGR+8/AwEisJQAMHsun8I0LRAAAAABJRU5ErkJggg==">
              </i>
              <p>
                Punto de venta
              </p>
            </a>
          </li>
                    <li class="nav-item">
            <a href="/caja-registradora" id="menu_caja" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEVUlEQVR4nM2Y7U9aVxjA3Zcl26d9WNp+2ky/7j/Ykv0B+7QsWbIsTZplW22XJcZhRTcpHdZ35VqQN3lTuYJFAQUUcEIqBSqt4AuKiK2oYFFQr7a6dS/ts53bcfD2ZWP4wk7yC8nD89znd++553BCUdFLRnmT+gy7Xl2cC9+1yN7O1H2q577+hbLy7H/hM3nV6aJ/GmqDwz0TuU/lgkJvM6Ka0t6aD+tHRBHZOPlQdluzlysib1eabWzSvVLG6PCMQo5Da3bpUc0PlpaekWUn5IPE17lxTsw6dWQyV6x8fb4ysnGSOq9mF/8/ZSobVR/x2vXWOqnBrrXc3MxVRtk/kkQ1V/slySOSgdcaO4xzgcUkTMU2wO4J5uoC1rEAXSNzG/61Kau/HgYiQ8Cx8EHh18L1W6oXZc5zibdUJnc6HN8ChMM7lbOMzR2ka+S3jHk9FUSHv4f6XMF6l5a5wG57R2fz7x5GRuOzwWB0OC+ZWrv4PtqjaJmvKvjvGUYDvx5GZnY1RQupfWYMMUyC/Cc7KJ0ODGG5wchpHdI8uSRqOodf3hKO4AOLO/T0MDLhl9Brvw0DY7MMui0+Ro7ZPfP0a+7197HMtzzpJ/bxCByHjOlmiCGjNLkZOahvKU/2MZZh1SkuOgP3YGIhAS1KEwi6zbCc2s2JRqke2rosML208YIMup7NGwabdw4zcjfMyHFO3IPyWlkJlqloUHPGpmOgsbrhQhUBNe3aV97t87AbFHSNeWwy55qDjE3F4HK9qhrLXG5UEb7ZVfDNLgOH0EApTwxlNdKcKOVJoEakg+Dig7xkvKFVqGhQ87O7b3OXdDycyOti4UMyHo5DVUu3BMtUt5Kd/shaQWTuRNaAw9eosQynraf3bjT7mNe29yC9+zOsU/s4tpx6SMcQC2vbdGwxSeHY0voOzs3E4puPGI0TLg8kHS5Y9dzBMdT3CkFmzzNcoXYguLiOE/Yf/04v29/+eMJokBlIIiOYGegGMrmZsbP/mCHzqEMOvxAEUKQOx9Dv4VWh1oRleEK9berA0tw/QRnUl9d+YxjLXBP3uaZjKZwQfUDRDdFnJoamBsUQ83/H5hPZWCTxbOoy04fITCe+RjQO0YUVWFjMLpaZWAquifqcWKZW0u+bXd08dpnA0hZMLG3BZCwbD62koU5i8GKZhg5TcO5A0f4xTVO7cweabTugcmdfdtS3QW4KYBmi0zofLpBMOL4FfJV1HssItbaVg1+uHdPSHprchsHANjhCzOkTkraVZwcrruzNjj7X+klvduEDyPtdybKy1jeKvilvOqOxeqlCymjMHurLqrbTRSVsolimd1LD3ggUCpneSSEPWkbcO0o9fyIbOEFQ/yORIYf8UCsxADnsL7zMj8Je+nCFPgsuox7wQDWfBPWg9/AyF1nNp4Q99o1CvjMC0r6BPOi95vuWLp1AY0+LdKN7J41AY0+j/ox/HpDZpUr+2ZMGP5G/JP4EIXjIKRcVStcAAAAASUVORK5CYII=">
              </i>
              <p>
                Caja registradora
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/historico" id="menu_historico" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAADe0lEQVR4nGNgGAUMDNHth5NSJ5xaHd1yIGlAw8Oreot8yfybL7u3f/hfNP/mS5/yTXI0sSgt7QwrITWhNdvVSxfe/gxyTOmiO+8D6reqUdURoe37jNMnn7mYM/3i7ZiuI5n41PqVbpCKbDmwPX3y2X2xXYdrGagNEnqOLQb5FIQzppw7hschvL6VWxvq6+uZSLLAr3QDb1T7gfKI5r1OhNTGtB0sqV7+8Ffzhpf/ojsOnfOp2GSJriY0dBWzX+WWOtfindwMpILEvuN72je/+V+66Pa7qM5DPrjUgXzpV7mlJrR5f2RE6/5cULrxrd7q5Fu1tRaUOMM79mfGdx+d5lexpdWrZIsEAzkge9qF26Bg79r6/n9Mx6EJuNT5VW7ND6zaLokubl+/nyW0cffs2hWPf3Zte/8/acKp82Q5xK9ym1Zk875D2TMu3k/uP3nOv2prrV/lthAGhv+MKOqqt0X712zVxWVObOehto4t78BpKXPqhaskZ0vfyq2uvlWbC0AWI2dV34odKn5VW0rDmvZXZkw6fzqx9+RF/6rtCfjM9KjfxpfUd3x7+pRz5+I7j2QQdAQozhO6j63PnXXlQUzH4XMBVVv88alPmXjmMiTnvP+f0H10PgM1QUDtFuPqZQ9/giyoWnb/d2DVdiN86pN6T2zq3Pruf/P6F3/iOg7Vk2JX2sx6rsylTXE5y5sSYDhyZpEIXIF3xRbBzKnnb4HiNnPK+Vv+9esF8BkIko/pPNQf3XqwApRdSXFM/PxyhcmH5n1cf2PLfxCef2bF/7QF1W4oimLq96tEtR6sCe/aq8xAQwByzKyTS9/verD3PwivvLwe0zH0AvEjzjFFa9oWd+6dfh2GS1a37Rgwx9Rt6VsFswCEm3dM3jOoHJOyqFoROQtnLquPIdsx0Ytz+fJXt/QUrW7vh+GEedWyxDomf1VL5corG8BZGITbdk55QrZj4knQiMsxW+7sgIv17J/5YHg7pnhN+87WXVOOwnDhquYaqjomdFU9W/z8fAFUXI5VY8ee6eeQLS5f1zmDqo7JWd5Q2n9wzovJh+c9B+HSdZ0PB8wxpGjsGHXM/NGQYRhNM7uGRG7KXd7i37Bl4taGbRM3g3DJmrZtMdOKxSo2dO2AidVs6t2VvKBSr3ht21KYGAgXrmjJKl7d2oAsVrK2fTIpZhKsDugNAHCgPoDg7qQZAAAAAElFTkSuQmCC">
              </i>
              <p>
                Mis ventas
              </p>
            </a>
          </li>
                    <!--Menu llamado depositos-->
          <li class="nav-item">
            <a href="/depositos" id="menu_depositos" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAADhklEQVR4nO2Wy08TURSHu3SjMcaVbox7V7pzafQfcEVcVDE+opFGECj4rAJqLAoFITN90ynM9MGU0hcVSmkrnapUKEgKFBBNVFBDlIcIpcfcWwQfWChYyoJfcnMzd2bOfHPOuedcHm9LW0pSGUT2biF7v/6G5aEuXUPI3q9HHDy+Mn8fGdCOO1+3QLoGGdCOIw4MQ3DURDphCI6aWIQhA8nDaDoMoA4YwRi0A/uyCc/omgoa1+CZNcJYBhxA+upgcOwNLCe0TvjqwBJxpBbGNuQEqY+G79FZSCR0n/TR+PmUwUjb62ByZhpWo8mZKfz8umAa+m0g52hQcXrQ95iXvDLYBOauFkhG5q5msEaWvKPrNmO7cj8NDf32xDCNAw6gAiaIzkexsdYwB0zIhF9Qd+hh7OvnpGBGv3yCmqAev0+HTODu4/A6sq/hTDj//gkjD9DwbXbmN4M1gfjuQC6PxWJJwaDnZf54qJCdXzU9+w3kASYBzDPmnzCyNcJI1wqD3IbcF10Ikyvcvq4wvf/ycTFMTIgFV9iP1+dwmNjEYYonsB0nmJLTLZPAzetKYGQP2VX4GTCvlMArbm0/2tpTqwKZmJnCoU1p0SNXU/Tm4kXPPvQkxe0g4gDCWweRsZFlQSKjI0D4alPfDpy/DE0w3igNC43S8LNRvtzARulMwdicMBlE9u68+ns+sYuAcq9iw4fYRQD6Pj52Ip1VXT1GdRpwLdlorzDdLKDvLx7Kz6kK9wqY208qvAqo8qlA4lXAI7cMJB45lLXJ8VzhVUK5Z+k+WkNz5VMVVD1VA8lRoA7qgOkxganPCo5Vbuu/YJAEzO180k9NqjroKarTCHSIxYYb+mzgGG5e0SiqKQiiNmQE5XMaqtvVIPEo8A9gcPRDHgXInmnB0NsITQs2l4XJlOVuPyUX7j+pyD/AlwoP8qVXDl/Uio5n0aILOUzxrTzjPfFVs5gQ2croYkeFtdj5mLvrrOorb1OMqjt0c/VhCziHV/DEcAuwYSsoX9A4X0rdUrjeWPohU1lwhPc/dFp6eVemuvBQDlN0Pttwt/paQ6nljr0yWOaWvdN2GucTVWFUGAW6ogJeqnVCc2nH6ZqCozn6Eskta1nrQ7f0rf6VOZYWmD/FVwp2CmrvnCk0PWDFLmIIHW/TBvOn17J1JTdFNklvlk4k5G0G8ZU3t2XKcvekm4O36fUDqvfcNMQDMu0AAAAASUVORK5CYII=" alt="money">
              </i>
              <p>
                Depósitos
              </p>
            </a>
          </li>
                    <li class="nav-item">
            <a href="/depositos-consulta" id="menu_depositos_consulta" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAADHUlEQVR4nM3YSU8TYRgHcEw8ePDmza/g0Q/AQaCJLDEscRRlCcYQTdwTY6KpIYEWU6BAWAsooEZBCARUiGIBaaGdoVPWspZSaGkhwJS2dO/ftAnhgEBDZwpP8r9N3vllJs/zvjNRUWe94gQjV3kCecxJE1s48pTPx3lWMGmlJP3m28ymoHNu4yThCYeRUqJcYQVElFMTJsaJk1Z6hQp5HQtIFitXwwYRYWLuVKqgZfzsgAgWMEuMnx0QwRKGFRDBIiZsEMEyJiwQESYmVUyiSW46kEbZGojyUaSKKU3EMPX9y6g7JGW9WiSXkIaIYY6qwLqB9c8ERrtuR1KRwnwmMJMrO7j+btgWUYzd6cWm1Q2//5QwVocHDQN6ZFXTyJGM4WHjJG5XjOJt2yxm12zBa2aNtkB773KKmTfZkF2jRt2gAZp1z/582fbj5+Q27teP44vcAIfbhw8DeidnmHWLC1k1NAYXrQeG3V7mNr14/lmDbpU5AOfuNeV3zqOV2jgUspepdTcyq2modRY7J5gdhwfZterguF8KIRV9etRKdS5OMOTiNgq6FkOCBPJLY8GrFo2XE4x0agPiXn3ImBGdHY+bJnycYGgdE9yNQ8X0TDN4zdWT2XV5kVFFY2HLFxKmuEeHxkEOW7u0R4uGv8ZjIapVJ26Wj2JCz1E37XXUPYkaXeNbh0LGjC5kS8aRUTuBvPY5TxRwjhNMoMwWJx68H4egWwv5kn1/tpjdwYNVaimFR1+XUUV5kds8h0QR2R7Nl57nbG/y+Pz4rjbj2aep4LfT3UoVciRq1PTpML1qRXoljfzfDKooH3I/LtoSRMo/0XzpBU4wx5VuYxe3KlQQ9u+gmvLhSYvenlikJKP50osRxwRqxmhFWpnKLxq0B0Ev2gzOBJFy+lqB4lLEMYHqUpkcN8TUdonMEQS97DC6E4tJWcQxbq8PbaTRwRMqk5PFNFM27EI16UOCSMlw8hdC8J+0KlYtP+g1a/OQ3k6Uk8HDVXyxIjOpmGJSymgmqYSScPJ/hndE4oTDsTFCWfzevWIKhy7zhIorR3bUadQ/uP5wgJe9gbcAAAAASUVORK5CYII=" alt="view-file">
              </i>
              <p>
                Consulta depósitos
              </p>
            </a>
          </li>
                                        <li class="nav-item">
            <a href="/whatsapp" id="menu_whatsapp" class="nav-link">
              <i style="padding-right:15px;">
                <span style="font-size:20px;">&#128241;</span>
              </i>
              <p>
                WhatsApp
              </p>
            </a>
          </li>
<li class="nav-item">
            <a href="/creditos" id="menu_creditos" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC0ElEQVR4nO3XXU/aUBgHcD7KtuyzLLvYB1iyLUuWeLG5qyW78mIXXvhCJmKmOFycQeV1MA1ok41NrUqMrFaYw1VQSktLC4htp4BgfJYegyGbmbQ4jIlP8g/5c3H45XB6CAbD9VyVeWV6f+Ode9Y8+gEzaYnF7u/qHLDdVNd4PeK+bZsKjjlnF7xa4ggs+LqGJm+dYjoHx+/wYh60TvXoCPBwlLXY/d5PoUhlR9yDVE7RlDUqBT1Wz72mMbXh8pJmxJmYh12L1hdDi1vNYKSDclOYl5blxOPe0Kih3RLd6fHF4TIxRl8c2ocjScPz4Qj9J4b4vgXBpTVgOBH1FXIT9foshTcuFKM6zsRonX9hmHQOBCICTKagD1M+rMBBqQzV6hHqpfIh6rVUKtWGMXwsASWTCdJJTh9mg0rCMvEDeHEX9fXYNuq1JNlMQxhxlQTZ7oTim0FQbBNQCGDApLOt/5ryXxbQjuxbrSC7PKCM2aA4MAAcldSGIS7gAO+PjECpvx9Yfvf0PZYRgOHyrd8Z2elCO1PwzwKbylzu08SmMqBM2BGoZDaD5J0CLk5rxxAXeM8IkRjIjpNdKprNIJAbl3fPpGooIopAyvhk6zGFAAZZPITuGS7BQG4ORxjZ5W49RnJ5Ts5LXQ7eWoGjdprDrOg5M1kZ+M0E5IPzCCKurgEj/P2T0NIzw1FJdPGl6bMf73MxDJ8FapuFgvQLdTotoF6fbYZvCHNezsWI+T0EUvaLqAu5Aur1qf1u/XeM1rnGtGRnun1xcGIhCOBkw5nB14H8ScPx8THC0FkZcJKC6flvMD0XbjiTMzj0fqzDGDEJ9KR3mgH3ZwJhvMFV1PWuhTBPh8iE3gWMmASmqRh4vkZh0L+pew01qsPwqAd/1tYfnmgzh8e15knfir/DkVKM2B50OGhF7XrWUT9fdTT9P/1+59zdB92Lfepr04tdz1WY31QLdJeS4e2VAAAAAElFTkSuQmCC">
              </i>
              <p>
                Créditos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/cartera-de-clientes" id="menu_clientes" class="nav-link">
              <i style="padding-right:15px;">
                <img src="data:image/png;base64,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">
              </i>
              <p>
                Clientes
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/garantia" id="menu_garantia" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAExElEQVR4nO2Yf0yTRxjHux/ZlmzZsh9/LNmy/bFk/23Jkv21AYoiChgGKmMB2vdKsTgEnaAC7R0HQ2m5K5sMUGbIomIcY8aZqQSRYBxxbgYJxpEpG5iADDYYiYMwUdtb7tp729oiL7/JwiXf9M31fnzuuXuee95Xp1suy0VbMWDyNoDkgILofgDL3tItpaLHjjcBImMAUcalQDLK63RLpSiQNHIwYwFxcXkgG3VLoSjQESctV3s8gx3+NkM8u0U+WFS4rKzyJxVEuzjMx3tKnKN94WysL5xl7N3r9FixW8H4qUUDBIhCaa2WlhTGht4Xam5OUa2oQGJdFLg0bH9VOgbaB13OP0NUQP4My6E4iwCR8VQrfX3BARVI66SVrnfEqnBSXVdjudPIs/j1gsIZrY4QgNzeWn10ewCc1P4jn3i32kpXLghcQkL9YwoiHXzSzYV213BPxKSAIzcjmLnI5tlq+gvG+PF5BwSIZkqrnG4wTgon9X1Dqo8VydZ5hTPhshcAosN8sp2OIufdwdApAe8NhrLdDiytOGLGjpfmDVBBtFpao+PyBnbnj1DW/2u4ABm/Fcq62lcHiLe58tMmb/CG5MC8wOmh4x2AyH0+CT24S0C1NEaxGIOJdV9dxe4NhrAsSzJbk5Tmp/Nno0XbsppdnrhInamW0nfnGI89AhC5wCcw4VLXQNdaMem+qk0CIn2nnt0dCGF9neEBgLwNbzv4WyQzYbv7nkbkIh9zzvCMkKTILao/aVbPV5aPxVqb14m6BwG3WZLV9nXfpXsdpsCRPCdwGbjyGYDILT5opm2Pc7x/pTphghmoIBfOBQdM3OL19H/7V7BttmJxTwNIB5Jx+bOzBlQQsctVX2z9yM9DowymgLPGt9sXMNpg8uvT+kOSj8NQ26zgDFbyhoLIHT5YcVW+y/WX977lik8zqiDcmm2tkezS+bV+gBs3+8dKPsanlRYJODGrxBZAclokooiw7mvrA2IctiX6wUQmm1jNV/EsM997NgvtiQH9bnbGiDHdDkNPzdR6kXIrDn2zNWgQvt4WwdbpvdssFWtMFb9RehO7cWV10L41xzK9DoNozLTgEjB+AkByw52I2pz/9LqDcTA1nYn2O4tSvO5cQ9Sk/W73rmJbikvcDoPo7zz51QyoIJInV9fUbJjyOuvrDGeV1RvZemASqqreIOqm6tfYpPg6TK42OExeBpDc5p3yP0Ou+xruW+ZRWo5eSGt7nthaPkfyJWvUYCl7ZUpAAMlRuaprbfGaJ2MzAOTqbI/zPYu1D4ezOt6TiWjV4R3TmojNEJDri0PZMvN2Gaw0LCgcxvhRBZLLMhEd6lmzYIDDPRFiTg9kO0+Kg1iPpEtTnzzjH/21aGIghIHtBiH+PN3+J06l+Wy1w+wHl5Rne16BZIj/uaO02DkxEKZp0LHeMHakNo7lFCSJtEuGGP7M6/h/vI22BYax7NIi6TB/6/NKXvR6LiQVkr7tUoLmVY/0rBA3x8PE22gd7+cfP/R9n65QrcfvRF5JDu6e9tawOZb9y1z1njbn2p/T6bH9NUmdTYpchRVWtpjKJu5t5lLjIoCk2fvBZ8no7ANJKYXiawGk9YspBdI6/j0nJYc+PeWtslz+D+U/T5o88Y0xYQ0AAAAASUVORK5CYII=" alt="glass-hazard">
              </i>
              <p>
                Garantía
              </p>
            </a>
          </li>
                    <li class="nav-item">
            <a href="/garantia-atender" id="menu_garantia_atender" class="nav-link">
              <i style="padding-right:15px;">
              <img src="data:image/png;base64,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" alt="toolbox-emoji">
              </i>
              <p>
                Atender Garantías
              </p>
            </a>
          </li>
                                  <li class="nav-item">
          <a href="/auditoria" id="menu_auditoria" class="nav-link">
            <i style="padding-right:15px;">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEpklEQVR4nM2X/U8bdRzHO58ypz9scS4xOh//AsGpmU9b4sOPxodh4kzUuOGCTxu43pUOvo6Bpfc9Sjser/e9a2mhQAu0BVoC8qBlbHQz0wXGXBQm2YJDiDokbMSZM58u6JXdtVdgw0/ySfv93ufz/r7u8/l+71qdbgUMIXSb7v9gqKwhzWT3DR2weR5fXRDkvcNSEzgVio5IVlfrd5kcd/uqweRaxLTazoH5cHRE8nQemz/AkhtfnRxcuYnG/LsUY/8InMYki2Lsr+Zg16YSR8u46O/52+L0/2woEu6FeRrzH1OY30czwl6KIe/B/JIXN/HezBJnoL+oqqGYwmQ/zZKdqNSxXh5jxOQhq9M/ODw2cXV8akYaGp24Wlrjj37OcI/J4+gva+6hMf8+xfC0yd5ojumSxt2aq0Baei5A6V3h/nlkczytFIcQt87fF70IIAsOY5hXijfaxK3u0MAV0OWbey5oqhYE8f7uSUhyh4/M5dmc6Wowgd7opBwGxmoweVZxS23HkTnQtTd1T2pu3RdlLp/VFQwfqvCEE8XZ3IGuobFfYiBDYxPSYVewM1H8wYq6DtA9WOZu0Gk1miHZsU9MsvaVeO9Ui8strXnFEeyVakMRSQz0STBWi0XIsZbGZA98h32oDYTl0ynMb4PvRmx/hGKE19RijRbny76eExKUHj5hrBZrwPzrC5vbYCbbc81iWlIYOJI7vN5bZeOclYCR6yCEbqEx/2lSGHg26ORjTLJysOuu5cDApl5oURycJK1RBdlvJk9QmDwftyArboYSLweGYsgblMnxsHzOYBZegPU0t2jxhl4qjFI+tIpiyWc6NVO7SGOyR6lVWmBiLWL5TFVIpVbpi/knKVZ4VinJyIqbodRLgaEZsgNeHUq6BrP9ORpzWxQohb1QOqUktVJrhFFsccwkaQ3FkE+um1x8ihabnuE/XNyqZDDQIgNDEr4UrztVNBae0rPc1kRJ+mL7AwZsfzMVGD0WMqhi7sFEugaGfwa2yH8wUJVEZ1522nQpwCyOT94qaJGWp6EO7oLsRuXld2uBgZYasPCBFt0YNBSDMvHbaEbMphnhxaRuJjv1sgUSwVBmfhfEa9JlxGzg0BVWee+31Hf93jYyJSXz8mD/TJ7NuV0LjMEqvlQR6L+kRddS3/VbrsV9XyyxoMrb2nZ6+nLkoiSpeefo7F9FnO9YKnumkG86DnmJdNtOT88dqva2xPWtoNoXbfp2fFYpoXV46nKhvfkMQmhtKjCZHLeukG/+oW341ytKurBeQVVj3A3+a/mHaxHr6Rjl2o5OuSNn/uDaB6dLPF3n8mye6uW8m/LL6zhLw1fnuNDgNOhWtR+dYt3hUWOZO1+XzBByrM9jnekIcRsTxaXye+aaLrfxmm78P40VMVN1o9kT6peaugalulBEKua8Jt1qGCO0vBM5eXZW/u8gcvLsnyVi09s3Haa2tS8iB1lwd2vfNzcFwMx736r0tFsr6ttLg1+fOK8EE+g9fh6uQxwWfBk3BAQLvoyBUz9dUgJQ84Hvf5zBQnPcy3VFzFoTrO4eHJ5N1SFvxWFoU+UG2iI8mrKbKjdoXeQfimy/d0MyDkoAAAAASUVORK5CYII=">
            </i>
            <p>
              Auditoría
            </p>
          </a>
        </li>
                        <li class="nav-item">
          <a href="/cartera-de-usuarios" id="menu_usuarios" class="nav-link">
            <i style="padding-right:15px;">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAACQElEQVR4nGNgGAWDBER3HjqZMeXsUWQc333stF/pBim6OyZn5sUH3ds//EfGpYvuvPcv365Ac8szS7r0Uit77VOq+g1AOHvmhWfojilZdPsDRY6JaT94IHXymT3YcEznoQ0wdSmVfWtSK/vPpVb2bwDhnGlnvqI7pmzh7U8UOSYHS3DDcNb08+dg6lIr+xtSqvoTaBpNOWQ6JmPK2Sct61/+R8aFc699JMkxaeUTbNLK+oPSy/sdQDh7+vkXuByTOfnUbZi6tIr+CciOCW3aHxPecjABGYc1HYjzqd/ERbRjUiv7VqRW9B9Jq+xfAMLZ005/wemYScffwtSB9CE7hioglYi4JyaaotsP3syafuE6Mo7vOX7bp3yTHN0dk0ONBJyKZmhCz9HHIEOw4YSe45fh+ir6p6RW9U9Kq+wPAOHs6edfYThm4W1IAo6eVSZTtbHncsee6eew4eJVHRuwOQakGRcOLNsmA1OXVt5bk17etwKUkEE4e9qZT5iOuQNxTPz8coVZJ5e+3/Vg739suHnH5D3YHEMuwBtN9HZMcv/Jp9XLH/xCxtnTLxLnmIaNk6+CHJFa2bc/paq/k1LH+FdtMcCGQ+tXsRF0TN3ayfdAoZJa0f8gpaJvPQMtAbHRlFLRNyO1uq9sQEOmmd5ppmpD9+Oe/TMfYMPFq9sOUNMxNCn0/GlRzgyqEnhY1E05A11rp1b2zxsU7Zn08n6FtPIem0HR0htUbeBB3TuIGUz9pkHVoxySfe1BPQrBMFQAACGOOqoIeCanAAAAAElFTkSuQmCC">
            </i>
            <p>
              Usuarios
            </p>
          </a>
        </li>
                <li class="nav-item">
          <a href="/configuracion" id="menu_configuracion" class="nav-link">
            <i style="padding-right:15px;">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE5UlEQVR4nMWYy08bZxDAKW0qtVTtpUoaqUql3npuz63UvyCH9lhVveTWQ4VUUhK6qMVg42BssLHXD/wOsMWxdzF+PzDrR2womEceGDBguw40EU2VEELAnuqjsmObtdeBhIw0Wn3rne/77cx8M5+3oeEEwm3RXhBcVn1aUDRueB3Cb9Y1aXodadI49big6l5bRowR7506jAQbvuimbuUiEykoqItczIkx4ptTh1F2k4qwf6MIghSN5RyL7lRBoAHe0Pe7J0pBCqoW2MIYhjW+cgjhL9fP4RxSq+m133GaF3aZYNzkrV292D2Pd1k0op/1H790CAzzvyXrNAuH8IkM7Vo9AsCkQe8aEIrJjIxzQ4JhxNvHWBR/V9ZpmZR1WtR92PWvEcQ1zPihvJuK+qx3y5K1oNFAGqaCmcMr0+9+eyKn7LZGBa3688S3xJuiNuNXim6rEOdSXm4L/kFVGLyLlAYcKxDyrcP4yMxTg9izpBU5l0PetbIF4rEsrCW24e/sDmzf3y0qGq8l/oF47F7Z82g+g9h7m1DScyiM6B5aR9ph1jCC8K+MfvKHis7Ucv0UnYaN1X/LAKppKvkQYsFMzVAO44FNydWhz47AKHhjtrBvvarhn5EsbP31uC6QUk/NRLJV5wx51wHvIn0VuYI1KnhjgaAnyWgUo9OwlX0xkCLQvR2YCjF7aNK1ArIO8zR+CT9THqZmXZOKP+6jGYA26gxNrZBVzhlwrgDeSUZFP+rfZ8ybnp+IdxR8yj3pWsmXJmu1RR5s7sBcNAlh9+LhFY2rPRuPPk/qgH05L+VYwqx9TNCq/cJumtsrGKJdwzT5+vJ9GDPSS7YR+nunKX4WXSkDnUD3mZ5H8xTmdIzG93qvGL5sYBNZl+Vqae5Ubt+CRxAIk/2YkV5+sPWEMZmLBdGTBCnH8hsrzGCPVVxa0JjeEoXETtz8jsmeGg79MB9NMtqVFkYll8JZYdQ9DkWxrgQzjJOGXAtgN02fZ7JH99HvTHaldUd1bZy9uw8KbCI2z6A3RzlS1TMxds8oeJScFUbeRbaiJseWMyhZK20Bg8YxY5A9Z7xrMPC7uYMVRtRm/Nxpni/upmTt3bSMPOEh5891SobSmFCbb+/TQXufnkF1gIme669C7bM2oWavoK2Cwb0jdQbnkpMT9kSxzszWqjNbTw5DhuqMQGWCy3zlibSsAit4VAhVxkhFtUzVUYH7NJaXA4N6k7yLDKNewdibgmnYZGmSOrMHCFvg2CoYND0qekbFHycrD9iRyq5do1n6QnNwEnEHZ9aLMMIW4oJJE07VOn/E6DSkVh++epjDbc2z9B+ecf0pcFkWd0fkgYUhqX8x5Cv32Gw0C8mlbdgq2fb1whzkDsAQoeDR053aMOgMLOeNuRQ8q7hwBuZjurMqvi044UhUPQOjykrZY0cWzufzZeP93AGI3Dogpuzsnqkm+CX8jJRj6R1VB1PodMYERTLAKAIE3FyN/w9ysA8ClxrMM+76wsQmYoz4SMm14sYB76yHur3LBoNC0U72g/dOBPgOFVjn/FXD98Iwpf8oDRKPlw0Gye6zPeDacHAs0lVBTgSDRNFtldYDgySXz9UEOTGMBBu+6Bu/e1APTD1yIhh+s65JK3SmCt9miBv0/muDQVL65WpAa7a76OkMmvQ4KtGT6/8BiSLWgRkGE0MAAAAASUVORK5CYII=">
            </i>
            <p>
              Configuración
              <!--span class="right badge badge-danger">New</span-->
            </p>
          </a>
        </li>
                            <!-- Botón para rol de Vendedor -->
            
    </ul>
  </nav>
</div>
				<!-- /.sidebar -->
			</aside>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<div class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
	<div class="col-sm-6">
		<h1 class="m-0 text-dark txt-title"></h1>
	</div><!-- /.col -->
	<div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="/mi-local">Mi local</a></li>
			<li class="breadcrumb-item txt-blue active">Productos</li>
		</ol>
	</div>
</div>
					</div><!-- /.container-fluid -->
				</div>
				<!-- /.content-header -->
				<!-- Main content -->
				<section class="content">
					<div class="container-fluid">

												
												
<div class="row">
			<button class="btn btn-app" onclick="modal_add_producto()" style="padding-top: 2px;">
			<i>
				<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAABb0lEQVR4nGNgGApgabtf9/LOgEmDCS9t9+uGO3D3rNhH7080/h9MePes2EdwB+6bG//g67nW/4MJ75sb/2DUgV+pFYJLO/yvbpsW+XP37Lh/gwFvmxb5E+QmlJy8d07czYFOd1+hGOQWjKJm1IHn6ByCb47V/5/f5P1/WpULXjyvyev/66N19I/il0dq/8+ucyfoQJCaV0dqByYNfjzZ+P/F4Rq8+MOpptFM8n+0mDk3Wg62jtYkDKNV3bnRxkLraHPr60C2Bz+cIV58QBw4ZUrP/12rO1DEdq3q/D9hUs/AO/Dk5o7/ObX9/8ua+/4/OdIGFgPRID5IHCQ/4CE4Y3r3/8MbUB0C4oPEBzwEv4468NwICMEXx1r/vzuJKgbig8QH3IFL53X9b+rsxYnnz+4eWAc+PNz+/8Ze3Pj+wbaBj+KvJOCh6cBds6Jvg/q5gwHvmhV9G8OBS9p8G1d2B/UPBrykzbcRw4GjgIE8AAD1qGiPQw6+cgAAAABJRU5ErkJggg==">
			</i>
			<br> Producto
		</button>
		<button class="btn btn-app" onclick="modal_add_producto_masivo()" style="padding-top: 2px;">
			<i>
				<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAABfElEQVR4nGNgoAKwr69nYRgMIH5WiUHC/IqnCTMrTAfUIbFzyg1TFlS/nXF88f+UBTXvQPwBcUjM3HL91IVVb5dfWvd/14O9/1de2fAfxI+dU2pCd4ekLKh6A3MIDK+8vJ6+DooBO6T6zfKL61Ecguqgato7KIaAQ9AdFD+7wpgmDombXaIHjhoCDqG5g+JIdAiyg1Ko6aCEeeW6iXMrvjRvn/R/2pEFKHje6RUoloP46GpA+hLnVX4BmUORQ+rr65liZ5cWJ8+vmoCOk+ZVzstf2fwG2TEgPkgcm3qQOSDzGGgB4ueXK+StbH6N7BgQHyROEwvxgVHH4AKjIYMLjIYMLjAaMrjAaMjgAqMhgwuMhgy+kEmcV/GtYGXLCxgG8QekCWFfX8+SOLdCCR0Pmq4uxSB+fj1H/OxyhwHH8+s5wHGfNL/ya82mnrcDhZPmV34FpzUQkb8CtS27i84YZP/gdEzC3PLvIIGBwglzy7+DHQPKiiDGQGNQkQAAvwBxPJpvjRUAAAAASUVORK5CYII=" alt="upload--v1">
			</i>
			<br> Carga masiva
		</button>
				<button class="btn btn-app" onclick="modal_departamentos()" style="padding-top: 2px;"><i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAACjUlEQVR4nO2TT2jTUBzHA3oWD152EQQPEwQPDrwIXWnee+nunvcHEuqhhzEY/bPS22ibNJmKh9qmLe62zukUvDn/zDVJLThB1A5bmH9OenJN0nWzkUw8dGs227RrVvqFL4/3Hvnmw+/3exh2EuSJJGLBYPA0ZlV56ITmj6ZeBW/Nn8GsCsjdX1Z9bLLQLYbpUPySn00VvAwvz0T5dJBLna0DfCp90PTVKID0sBvtACENcvxcaj3xcKW2vPpOm5t/XPGxyZKPjQ0cADQy5eUOvff8p41yvAyvPcm+3+PQHeEXd7xR/qVlKjgzl34bX3pWe/T6bwX90WSxroJHzWCnAb1MbNDPpj/6onw5wCZTB2bwqFest6YdgFQrOR46fvvGwsKpf3vB4dD2Ww9udC40aaOcpoD1D1SOq7MevP9MbcGNcnofsEiSih5cpCjFDFzRIMc0oABA9eY0/V0EYNsMoGCQY76CFKWIAFRNV5BqnNOWGeykhT4g169gD81gZmioZav9R8KdgBarVgfM9Gew11usWh0w05/BXmnx18nJmsKyHQVTWHbvP80D4vj1Nwi9yDmd5U23+7fMMG0H+zY1VcuPjMgSAOtZhwPHWpFot1/JOZ1LIkJyyeXaLdO0KTCZYbQvbveuRBByjiCeC8PD17B2aA2AyxJBPJAg1EF3ypFIU2BlmtY2dTCEFAmhFdFuv4p1QiIAF3II3RMhlDcmJra3QqHDwcJhreRyVXMQKiJCi2sQDmLHoVUcPy8hFJMgVD+NjlZ+zc7WgW2Fw9pnktwWAagICGWyNttFrBvKIzQgAXBHxHG5MDYm/wwEtML4uCoBoIgQ3tXvMSsob7OdkyAMiwD80Fd9322mY9Mf7uawC9iFg1cAAAAASUVORK5CYII="></i><br> Departamentos</button>
				<button class="btn btn-app" onclick="modal_proveedores()" style="padding-top: 2px;"><i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE5ElEQVR4nM2X609aZxjAccvWfdq3/QldPy37vEuyZEs2VrOuXvDe6Tp1XkCrc1URRVGwE7DWarGoTEAqAqLGClaFIypey1S8YKWrtNF6q7NqrTovz/KeVkPJjIBH65P8kifP+5z3/HLOk5PzkkiEBXgEsLEEvxzsCspJpyW+YmIf+DCbPvdn6e+kyiY3EQHZrTJUQ2tvTewCQ3vuJ3azNorftpQqse6y6+eBq32Og/JU6cOtiML+ieDc9hpvRvPZE1QDj0CWriiuqGftj8Z/9qUOAvXQSszTQTlYwbG/eiaT+U4IW9+YqXi8c5gY14FM5fRGMLu9Du1xbIIhuYZiluqJy3LcPUnV040gdtvNY5GjsHTn4gWmFXfluK+JEw4/9We2fEy44CWOvsqZmeM6MZNBnHYx4YKReS22o8pxXxPOM4wSLhjD071wvBGnYQEY8sdAr7RCitgCKZIxnGTxGF5Da/afnz2i+boVwgVj87HNV4M+BXTJKGRXW+CW9m9oGlmEjkerYJpeh7HFbZhYBhia/xd6p17ia8VND4FVPQZ06ShkKJ7ggjF8bJNwwWi+bosuMUNV1zQ8WNoF6wq4xIPnu6Dsm4E06TBE8fRbhAvG8Fq23RGzOjC+tAOx/JZtwgVzQi+aeRIMfxIDs5sgwyxQUtcJQuU9ECkbQaSoh9IqNQ7KUQ2tldQZocowDkNzm/iTzylrBdbPXkOEC3KCvtQJfw+DdJ54V6aqBlsnF9b+YjvFZAcXpCoFpPMqdsuSLgEn4IsmwgULqV9bxTRPKE0kOy225oAwkQzl1PNQdOWbMcIFawv8rCUx3x1JsDSRDILYb6G+0G+CcEFVvndXdab3kQVV2b6gyvc2Ei5YwfKM75ZFrNfmUdwWrM3zg57KiHVJjieVcEEgkTwqOT+KdWWh2+4K6spDt2W5FyrQXqTjCu2tYLO7go2CYOI/L46h5PvIV/qzXZZb6c+GmnzfO8cuWM7w/NSkiF5wVbBPHjUvYnz/CekkQsHzks11pu84K4d65VwvKemk4gaNfEbF98XmOjMOlZvtZICS56NH15yY4J6kOp+yalLG4PP1fzNnUsaC+rrPqoJJeZ/0NuJuUcDkcC0N9OWhYJSEw7OuDByUo9pwLRVQD+qtTfaf6i2g2lwBXeOyFIWieJeSrv3sF76Bqi4KW350LwkQloYE6BBfxrE0xOM1BOpBvcbrcTPQWQaugCSdFgtkNZ+NF/Tpfy0wLqtNs2tdtheg+TNuX+QgUA/q7RKk7drffK35JoyUXoVh4StQjmouC5JpmjPU4p56utSygU5kVytG938+2zEV9CoOluxV0MBgqMF775cz37i57U4WSGI9QRxzHgflqOaSIIWt+Sgsr83GsTv42AtaVwDadHLQiKgwoKaCVZuIM1hDBY2IBgZ99X6fo+CRXzGZqfnwN2Hv0DXV2BsnMkdBK/qNX3wJxj49tGpKcVCOavY9BAuCR7yguwWdzvLU44cKWp2AUMHLXCxKO/JsDW186gQjI++/x5QODO5t7CgYf3sQr7lKexGdGMGQnNYfGs0L6wcJukt7cToxggm3e+ToaHlqBRliU7f97Jw6wWvKYfOpFgznYuVRBR3yfW4Ya6ILe7CjUpLFmC5jpc3YU5qZOi3KSpncJyPJepceWLFHXTKl5D/4A/GBX5S9xAAAAABJRU5ErkJggg=="></i><br> Proveedores</button>
		<button class="btn btn-app" onclick="modal_compras()" style="padding-top: 2px;"><i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAACYUlEQVR4nNWYPWgUQRTHJ6CNWKlgoRZqYaNY2AiChUhAkYCgaBojyc67nCmiwcvOu7u4huQSNLfz9owxHMn5AUFyJ6YwAb+4WCRoIQYTCw2KXKGoIH6BRmxGphBUrogXF58/+Dc7vJkfMyzMGyH+B5rzXbXeGM0kCoGJnk4bifqdRH0novzdggPeaDBzq1Q0NifGaLrB85c5rr8XkGYBdScvwVGa/vG9SfUuB6WfOSrY808Fjw2nDtqds2kpdBz4eUy6VC+RrperG+queTGeqyuFETv3vOQbMFgJSr8vNzaeqyt9nkqZMGLnnvcOS6Q3TmtmNVtBQCrKOO3iK6gogDjF/lTw7V3PZJPV5lx85y/JtlWb1xNtf1HQ1Y5U+hLbHXTi/laJNMVW8Ih3dqlE+uJ53iKWghZQ+nljkjYIroJS0bUI6n2cBVMS6SRbwYiiWqn0VbaCjRhstLcbtoIA2cWgaA687BKWghap9CNHZbbwFUR9GZSuYysIqOOA1MNWUCqqAaVvsBV0EmfWAuqXbAWFMFVS6Y+2V2EqKOzd8B64tJ2vINIAoG5i0zT9jpP03fru1LdDg60m3MSeikpwXL0DUE8IrkBLegUgfbA/jOCKbUOjifQawRVAus3mYakc9kEJUPcKrkAis94eM7j+JsEVUMF+ifQKlIZI3F8lOBJx/c22mQKkT4BkFppQJKOeXtfemy9dKT4050cmv8ZOXbgYRk3FxFIDPXah412Dpj9fNJ19hcdh1FRMc2f/4dzI5JxdaPjmA5P0h+6HUbMATJU9oo6+whO70NH2/m3h1DDlO2YNDOvC1tYBAAAAAElFTkSuQmCC"></i><br>Comprar</button>
		<button class="btn btn-app" onclick="modal_historial_compras()" style="padding-top: 2px;"><i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEhUlEQVR4nO2Va0xbZRjH36iJLU6TEaMmxiXuiyaOTS7DoeHejp4DOFkgQCBsU8SQyCfdZqbAPrgPOsUeEOZA2bh4ewcSsgyyDTag0nIplMu4BzpgQGVQWlDunL85Hc4BLRnQ0i88yS8n5z05eX/nfc7zPITsxE7sxMbCQInj/Z+J62OTR15DMnmCbEcM5hB2KIfMDuUQbIR7OUQ7cJm421ywL4vka7MINkUmMWozySGbCnZlENqVQbBZOjOIsTudeNtMsEVOaIucYCsMFzvzM/VhMzPq8KnZhoiJ2cbIsTlNlG6uKXpovjmmf6H1WO9C64nuxbb32xfbYluXOuI06PxIvcxV9MS/YlFQfZ5Q9XmCzTJc7Ap0fLg12mMzLAoqzxGqPEewGQYLXYD2D6wBtSjYeWlvZd9vTtgIWrof4+USoO24tbAsaKyS9aEtBnaGrifYjjvRsCutUZYF/1EyjWiNhH2JsCw4rWRVaAmHnaEWBWdr2dtoCYWdoRYF5+uCStF8FHal6ahlwaWG4CI0HYGdoRYFoQmi0ATDvgStJ8hQaFjYF2a9FMso3yiDLVlUB+BvpRQGhQT6Sn9MVEkwVS3FXF2A6bngsI6glPINUtiChXoJ9BW+aL/mDe4XX8Rm+yMkU4KYH/3xRa4fKou8MFrug2mlX4FlQbU/5dX+sCbdCgkSrxxGb6knErJ94ZjKQMyxZgm4KMG1Qq+ydQR9KK/2gTXJKvYzbb4nTWa6uubF44eGYrSP9GHcoEff6CCKOxSIvPolHLhA7OaYBYfvAgPMC9Z5Ur7eC9ZiodYTcbnS5RMKREhRMgxGIyYnJ81S2lWDly+GQ8wx02K57KAZwbcpX/cOrMV4+Vs4eOnYwxQ+l/YuNPe6zMoZjUaM6HRQaJvgmP4exHKmgyR7P7VSsNaD8rUesAaLKg90lviipEuJmz21aBvuxfD4qEW5FHkalKoa031SVbbpg0SpbOQqQXfK17rDGkxVuKGg7CR6dP0YWRYTRMrKb2FsbGyFnDz1e5Tfqni41n9/GM+mBUMkZwpXCqrcKF/jBmswUeaMbEUaVHdbMWbQmzbW6ydw+kwiQiOicDk3Hz09PWvk/uNQ/sfCv9i/StCZ8ioXWIPxGwfAKdJR0FaBCaNhxeaapmac/OwM9ry6F58nJplN+5E/EiGWs7MrBav35/PKA7AG+hv7kFRyFl+rfsVf+v9T+igpEVJotVqzz/x+/wQijtGtEITCiVmqdprhq52wVYw338CpKzE4UfIVlHdbzEoMnI02uy70x5cuhAkn+OeaVgOF024onFy3yvztfW4JObKEN3PiEH9dviHBvObrD6qYCzxNbB0ijhkRJoTQhB9HcGhch9ezjwsFMvlMhvQFmws+LWekIjnLCxNCaMKPygx+m7BG7jA9tdzYZfFku0LEsZ+K5cy0MCESK38y9bnV/5yQ1gcnxwr9jyPbHQ4pQS4ijr0jCOxKDTL1uZCiJFO1vnghdFmMNWzrya0JGvakML6ECSHm2AExx8wJrUSoVqEgdn0T9Pzqd/4FqAvblnbrRdcAAAAASUVORK5CYII=" alt="history-folder"></i><br>Historial Compras</button>
		<button class="btn btn-app" onclick="modal_historial_traspasos()" style="padding-top: 2px;"><i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEhUlEQVR4nO2Va0xbZRjH36iJLU6TEaMmxiXuiyaOTS7DoeHejp4DOFkgQCBsU8SQyCfdZqbAPrgPOsUeEOZA2bh4ewcSsgyyDTag0nIplMu4BzpgQGVQWlDunL85Hc4BLRnQ0i88yS8n5z05eX/nfc7zPITsxE7sxMbCQInj/Z+J62OTR15DMnmCbEcM5hB2KIfMDuUQbIR7OUQ7cJm421ywL4vka7MINkUmMWozySGbCnZlENqVQbBZOjOIsTudeNtMsEVOaIucYCsMFzvzM/VhMzPq8KnZhoiJ2cbIsTlNlG6uKXpovjmmf6H1WO9C64nuxbb32xfbYluXOuI06PxIvcxV9MS/YlFQfZ5Q9XmCzTJc7Ap0fLg12mMzLAoqzxGqPEewGQYLXYD2D6wBtSjYeWlvZd9vTtgIWrof4+USoO24tbAsaKyS9aEtBnaGrifYjjvRsCutUZYF/1EyjWiNhH2JsCw4rWRVaAmHnaEWBWdr2dtoCYWdoRYF5+uCStF8FHal6ahlwaWG4CI0HYGdoRYFoQmi0ATDvgStJ8hQaFjYF2a9FMso3yiDLVlUB+BvpRQGhQT6Sn9MVEkwVS3FXF2A6bngsI6glPINUtiChXoJ9BW+aL/mDe4XX8Rm+yMkU4KYH/3xRa4fKou8MFrug2mlX4FlQbU/5dX+sCbdCgkSrxxGb6knErJ94ZjKQMyxZgm4KMG1Qq+ydQR9KK/2gTXJKvYzbb4nTWa6uubF44eGYrSP9GHcoEff6CCKOxSIvPolHLhA7OaYBYfvAgPMC9Z5Ur7eC9ZiodYTcbnS5RMKREhRMgxGIyYnJ81S2lWDly+GQ8wx02K57KAZwbcpX/cOrMV4+Vs4eOnYwxQ+l/YuNPe6zMoZjUaM6HRQaJvgmP4exHKmgyR7P7VSsNaD8rUesAaLKg90lviipEuJmz21aBvuxfD4qEW5FHkalKoa031SVbbpg0SpbOQqQXfK17rDGkxVuKGg7CR6dP0YWRYTRMrKb2FsbGyFnDz1e5Tfqni41n9/GM+mBUMkZwpXCqrcKF/jBmswUeaMbEUaVHdbMWbQmzbW6ydw+kwiQiOicDk3Hz09PWvk/uNQ/sfCv9i/StCZ8ioXWIPxGwfAKdJR0FaBCaNhxeaapmac/OwM9ry6F58nJplN+5E/EiGWs7MrBav35/PKA7AG+hv7kFRyFl+rfsVf+v9T+igpEVJotVqzz/x+/wQijtGtEITCiVmqdprhq52wVYw338CpKzE4UfIVlHdbzEoMnI02uy70x5cuhAkn+OeaVgOF024onFy3yvztfW4JObKEN3PiEH9dviHBvObrD6qYCzxNbB0ijhkRJoTQhB9HcGhch9ezjwsFMvlMhvQFmws+LWekIjnLCxNCaMKPygx+m7BG7jA9tdzYZfFku0LEsZ+K5cy0MCESK38y9bnV/5yQ1gcnxwr9jyPbHQ4pQS4ijr0jCOxKDTL1uZCiJFO1vnghdFmMNWzrya0JGvakML6ECSHm2AExx8wJrUSoVqEgdn0T9Pzqd/4FqAvblnbrRdcAAAAASUVORK5CYII=" alt="history-folder"></i><br>Historial Traspasos</button>
				<form id="form_productos_excel" action="/productosExcel" method="POST">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">		<button class="btn btn-app" type="submit" style="padding-top: 2px;"><i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAACZklEQVR4nO3U/0sTYRzA8f3WP9GP/RsRBcWMvg0c9OUUZKCtprRcTddmF5RuytqyiDQ1l6Hmcg4RbEcWRLkWgVubHppjY0wUKQsn/TK3TzyjRbp23pfn2S3YGz4cB2N78dx9plBUwphhzJbse+uOSR3zhCNlcFuDCtw9fe+NAYaGA5MwHBzP6sc65ssWyMRn8CNxAxncSBJABieSFJDBhSQJZHAgSQMZqchSABkpSFzAYJIF16yHcyxeZ0o2IJ/Qb1WAUqqc4H9/gh+XQ6Drp8sT+CkaBrVdB2xyufTAht4b8Dri5zw5NcKtRDm/hxgwupaAmvvN4JgagHRmW/DJEQeiNn9ugfGZDfSDt2Fj64dgHMoTnv5ODIjKZDPw+NUoXOjWgyfg4/VY852ra4KG63Sm5lJrLD9HT1NJrMB8byIfcki+ONQ1+i70vZjZMXWNlhgRYCjOwvl7V3LXsgOG4ixU2y+D2z+du/JFlgQYTizm3ru52ELuPpJY+n0/Lz9wNy4fXyRRYP6xFnucoTgLza52eYDFTk5oxIAXe82CthVFW52gaTLBVUvnn2l/MFQAbLP3p/7+X1TV6lYPVqlHRL2DQkqnt8F4qwsejfoKUMWm57kPVJR29oBSuY84EPX12wYYbnbxBlJa4+IhpWq/6C0W09znBTB3PNwTpzPZ1o6dpJSit1hKnsmXcKfbVRRHOwY2j6s1LaKXBEdWZw84nkwU4JyD3syJs/U7l0IOYPofS4OW4gyl9Rcsxe5a3Pb4SGDqC+kZeje+Xm9sy+65FHJ25FTt4UZT5wrnUshdVbWmFQ3Xh34B0yYDfc/M08MAAAAASUVORK5CYII="></i><br>Descargar</button>
	</form>

	<!--Genera un formulario oculto que tenga un input name"id"-->
	<form id="form_id" action="/descargarTraspasoPdf" method="POST">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">		<input type="hidden" name="id" id="idTraspaso">
	</form>

			<button class="btn btn-app" onclick="modal_traspaso()" style="padding-top: 2px;"><i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAADxElEQVR4nO2X709bVRjHm5joX6L/hcaXhr3QN76aLr5g6aQU1l/rxthKGT8rWlvs/VHLpL2l0LvbH9CW2/a22NJbJI5uID8iBjaIIiROt2xRSQz9mnsJV5qUMOdwaO5Jvi/OuTnP+dznPM85z9Fo/q/tXZZ9yUKIr1vpsrPNN7tgoUu/mMgiJBmo7J6BSv3eSkduN9HDzW+5XK/8a2A2dullC1lutVIzD22ByiPv1OYfsaVH6M+k8SHTCU8pgJE7kyAKcXSwMRioyaqFEp+YKVEvzT1ROBNZevMSPbPTy37zOLn6K6Z3oKi4vQfXdA430h5kN/KyJr7j0ei/jujyA0hzrPTMtumz8hsnAneRSHRZ6NLude4WWsIONId6YEsNI7n+QIEMzC+hPeFSALszBHoETvnOzf+MS3RpV09yrucK1+LlIgYqja7UEHxfh3Dz9hj6BALdGQ8uMHYFkviqJENl7+fRI1C4GB6A8P1vCmBfPobWkBNGMgO9l409FzgdNWI1kTmE7qZkr4wuxKBlOnB14nM0jXSiKdgBx9SEDNCdCcNZGMKV+Me4HCeROwQXXl6HLnQDqbUMQneSMJI5NHuDtn8EpyVvvmokp6p0KS7DScYvBO1gFlbkRW+t3MfQ3Bwym4/lvrPI44Ohy/goP47Cj3t/xedOFS1j/RiuhJXtp8VxGIl8tdHrfe2ZAa1UeOvqSFQxOlj6ArZUQF6U33iIvlwU7pKA/A+78pgEGlvdqkmeA7UnfDjvb0d8dX8nJLUFozDTY1vPBMf167dN5JfglnnF4MCUF87pNALzi9AyNjjyXtj5QVii7hqP1dMnBR4tY92YXM8o9rhFHtIabJ/+p78FJ9jP3hugSLQF97f2QOxSHOf916Af7UJoISKPZTZyMHMOMPPLdcGK21X05jgY2F752DlsT/YiMw4HRSBnP7v5VHCM7ky54tTCQicxmK8FlJS+J9T049+mZODA3cW6gJ9OZ2GJOGo8d1juXBxmOoE5pxYBfUPlWDjO+A5m3XqYiSJSa7UwB0qupUGU/ejiPWgcvga3KNSFG1/bgX60F97ZYF07+4knyNfj7KAOrOFtBJqPgJTgGF2D/CdZsg0mKlvXYGQlIWdyJx+Ep1xAZvPJkXEnHUVS3EphcBSgJBMlIEVdQcWlBaM7Ux/S39SA0yRNPUCIvlMhvwooqh70PVWsTNreOzbYedu5FxeDvO3c8YAd76tJAvWYEdVz0KfeJBr1LhbVYsGnlltQ60FRrah9L/w9AvVNIp6gB0+TNP+V9ifu7CfVmJe7+AAAAABJRU5ErkJggg=="></i><br> Traspaso </button>
		<button class="btn btn-app" onclick="modal_movimiento_mercancia()" style="padding-top: 2px;"><i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAADr0lEQVR4nO2YWU8TURTH64uJH8JPw4tfwDeCQlWk9YUlSFdlCWgUZmgpjalOC6VCZGirgJRFIYJLRCQYI6godQHBYHCBQNH0b85NKpRZaMugwXCSk5m50/n3d88599x2dLp9+98t52opdsN1WgL2Re9o6jn7gNE9EsHgRASNYRE1nuuwcgJzOneHRYQmev4dYOTtABpDIipcfgw8eIq5hUXE1n8yn11YZGMVrmYG2vtWHlDvNfO53rKvqS6qXKHsh95rrtRBd0AVkOAuNbeiKdyHtdg6lIzu+UK9uNzcJoEkzdLgxZXwVHfKZSG+6ISx5fzKcaH0mCqgKyQyuLgiGvB9eYUd4/E4g6Qy2AqYDlzC/ePt0PtM0aQobgakmqO0qkXuVfQj6gTxz/XqWoyle3NNqtV1iXhx23RnXy0+KwtINUX1pZbWC1fa8HLmQ9J4//0xuG92pARI9+7NQ9E9o49xosm2JAtY7Qlg7vMXRcCOyDCCvSOS8dmFRdR4ApoADs39hN5nR7an5IgE0Mp5EVuXTy8ndMBa55VN/1psHTbeqwkgee3QbegF66QEkL5Eqf7qfUGY6wS8eT+364CR6DfkChYUuCyHpSleWFRM8czHeVS5Aph+N5uc4nntUpxwe3cTjl0rC0sXyf0xqBlF0B3oTBrrH9FukSS87fk0TvrssSRAahXUMtTazFZbXYuhvMGP0LOIpoB3Z2M4fs0ESaOmKFLzpSa8ncXjcXiDtF9vRE8rQHK2p28Vo22Lti+CpOioRc4XiqDW34rIzMDfA0xA0vZFqaMmTIuA2g+lns5pjO41hjskcFrsJEm/zNXEqCap+GmFUo8kp3P3LTGp5vrSAEzHtwXciXDf/wq42fYG4MOsLCR8HzCaQQ0qRbBnegB8eyuKqhtRYKlnR779Onqm+3eU4p40dBVrkETMnAeVDS3oHBzF8NgUO1Y6AzBxnm0hcxQA09VVBKQZksijidcSr3C2gBdbMwLk09ANv+xGrmBakgWksHcNjrIHXf5OGG0OuPxd7JrGi2vcGQEWpahLcCXihZU8b1md7MujfDOP4SeT7EGDzYFTJg5Gu5Nd03i+hc/ohVJ+irp5gmkpTzBxR2+cOyj7Z95oc85vzLSLiblaNmZqtDo+ZfKSwKiVrsHCV5fX+1flasXONS0bLHxVJoAGrXQLC2sPGWyOiXOcf5lmNjI2xWZIIkabY5zuZwJYqKUuffi0xVF1xub8lG/iftGRZpgp3G7r7tuetd84+IVi6fV0SQAAAABJRU5ErkJggg=="></i><br> Movimiento Mercancía</button>
		<button class="btn btn-app" onclick="sincronizar()" title="Sincroniza la mercancía registrada en otras tiendas" style="padding-top: 2px"><i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFKklEQVR4nN1YW08bVxBGat/a/ow2UVO1732JCPauaVNVUEXhIbTP5CFpgYAv67XTOFKjAFKK8W1N+w5USiEoCoSE9KVRi2kQlySVAgZqaKOkafGeNb6sT/Wti7mY3T3GJpU60kir47Ozn+fMzDdzamr+rxLs5N4LCJa2qNd2U/LY4mHBSgIuSw6KZ8lrW8JvfS5rK/a+FFDRC++/EXbxHZKbX/3myw+S45Hm1Oz35+nqhJ3+ed9Lk1OXNMUz1uaun6PjUrOCvRHRthJ0W9r9nuOvVx2Yx3P81aCLaw8LXPKGv4ksjXdQMn2ZXWM+ujTWQUf8TTJshAWuDTarAi7s4I5KIv/rdz2NyfV7rvKATZfq2j0XHepuTEoi/zgg1B6pCFzQyX8cdnPy9NBZtVJgZI/GBs+qsN3ntH50MM/ZrZ9FRZ6sTNirCozs0OXbnTTq5UnQyX1atucArhpHShiOPOq1kYC97iSb5xzcUbj+MD1H9mj8difFN3vtdW8ZgkNmISFYY06Z6aHp5Rs099djqm4+o1TNaIpnrKXjI1SZ6WaMyZacJNoeGWY3Cu9QzycbpgYfXKGZtUmaz21SM8nnUjSTuKO9Y2Z3sKtBDrn4Vv0iLHCmpUSZ/ZqqZL0USFbW1qF43isqSVBl9ppxPE46aVjgNvYt5mAIFGEzcPnMxo6vZmn2j/s0tRAp2Zt6KNHs05/gwuL29G/jpl4c9p+WwTglAEFfcSOGeHBll+fU1FOqzAdMP5iaD1E19Yxm1n9gisXFsQtUEvnlXeBA5uBLUJLei4i5XeBmutgzlSH+ihrz0f6L9XLAYX1nG6CLawfxG2VrMSHULJPniJ6tuV6qJpepMt+nu+dWpFkJOLnPiwD7vbab6Er0XkAp2RLEXCXg8psvCkmV/lsXJLqgfm/96DZAkY+v3nHoGkZN25L9EoKUCa6Y+TogQRJRr+3JdgYLnIweTs94fvN5sZRUC5wRyOc/elFuktsF2mnJJaf0E4Sqac2YStaqCk4P5MbPl2jQZc3qAjyIUcKoqvJ74f3cpu6eEoD7HXG5x0IYNZ8lmg3wtd6ekiPWS5JyApswKNhlS3IvHunuK0kSTF9IbbMYqgQcmb5coL5/JR0f1t03e/0cjYr86K4uZsyoUM/5C8V1zn9gcKmFUJGX0eEYtWFj4WYlKFjOFwH2OU68a0Z1ldCXMtOt8fGWZBITxlTnrZf9Qu2x3c2Ch49jNGT5IIgfQZ5aCDF5bic4tF1Gf+7JWEdps6DxsdvSjrnV7INombYzJqfFFYKfpd3SYtikJxzuPS0j5EoAoklEamOIMTyu2WtUlROlpSdLdjSshVJSbsOauOukIYHbuNpmfa1mPwm5ra2D3Ywtf2JCC3a2ln+Ckl++MrYZ89GBrkY5tLOL0RmaHmOoZg3+dHxYq2lanIES1bT2jDX8xjo0TQ205CIi/3Dg1KlXdAEWSk7tEYyAGKqZM3q6MkUnHxZ4OShY3qxhEVxHSCJPzOKRVEETk04aEXmlz2n5sKYcwXUEbhcwVB+m5ySRJyHBeqYscMXjttedxHHHBlqqe3kU82kxh2Mt23N7BdcRmPgHuxqSmFsrPtK7TjpwtSGJhGCOOaYLTMH6BYbqkd4meRHjaTm0GPNp4ySKMOoceNY0Ww8iKOZgHMltW/72Yn0S0xe6oJXiFbBPUzxjDV3JrfAZBWNkROTjYIhDuQLeF6xQewyjYdTLj0Y9tsWQwMkBZ10OigYYa2iZsCfoOPH2SwH1X8g/Cn3cmsH+d0oAAAAASUVORK5CYII="></i><br>Sincronizar</button>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="card card-secondary">
			<div class="card-header">
				<h3 class="card-title"><i clas="fa fa-list-ul"></i> MIS PRODUCTOS</h3>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-sm" id="tbl_productos">
						<thead>
							<tr>
								<th scope="col">Clave</th>
								<th scope="col">C. Alterna</th>
								<th scope="col">Categoría</th>
								<th scope="col">Descripción</th>
								<th scope="col">Stock</th>
								<th scope="col">Compra</th>
								<th scope="col">Venta</th>
							</tr>
						</thead>
						<tbody>
							@foreach($productos as $p)
							<tr>
								<td>{{ $p->clave }}</td>
								<td>{{ $p->clave_alterna }}</td>
								<td></td>
								<td>{{ $p->descripcion }}</td>
								<td>{{ $p->stock }}</td>
								<td>{{ $p->precio_compra }}</td>
								<td>{{ $p->precio_1 }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>
						<!-- /.row -->
					</div><!--/. container-fluid -->
				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<!-- Control Sidebar -->
			<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>			<!-- /.control-sidebar -->
			<!-- Main Footer -->
			<footer class="main-footer">
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> <a href="/mi-local/productos#" target="_blank">FD3-ACCESORIOS</a> | Icons <a href="https://iconos8.es/">Icons8</a>
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 2.1.0
  </div>
</footer>		</div>
		<!-- ./wrapper -->
		<!-- REQUIRED SCRIPTS -->
		<!--Start of Tawk.to Script-->
		<script type="text/javascript">
			/*var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6646a478981b6c564771483c/1hu1v4u0p';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();*/
		</script>
		<!--End of Tawk.to Script-->
		<!-- jQuery -->
		<script src="/plugins/jquery/jquery.min.js"></script>
		<!-- Bootstrap -->
		<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- overlayScrollbars -->
		<script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
		<!-- AdminLTE App -->
		<script src="/dist/js/adminlte.js"></script>
		<!-- OPTIONAL SCRIPTS -->
		<script src="/dist/js/demo.js"></script>
		<!-- PAGE PLUGINS -->
		<!-- jQuery Mapael -->
		<!--script src="/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
	<script src="/plugins/raphael/raphael.min.js"></script>
	<script src="/plugins/jquery-mapael/jquery.mapael.min.js"></script>
	<script src="/plugins/jquery-mapael/maps/usa_states.min.js"></script-->
		<!-- ChartJS -->
		<!--script src="/plugins/chart.js/Chart.min.js"></script-->

		<!-- PAGE SCRIPTS -->
		<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
		<script src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.js"></script>

		<script src="/plugins/moment/moment.min.js"></script>
		<script src="/plugins/moment/locale/es.js"></script>
		<script src="/toast/javascript/jquery.toastmessage.js"></script>

		<script src="/plugins/select2/js/select2.full.min.js"></script>
<script src="/jspdv/departamentos.js"></script>
<script src="/jspdv/productos.js"></script>
<script src="/jspdv/pedidos.js"></script>
<script src="/jspdv/proveedores.js"></script>
<script src="/jspdv/comprar.js"></script>
<script src="/jspdv/solicitud.js"></script>
<script>
	window.localId = "1";
	window.userType = "Propietario";
	window.dominio = "fd3-accesorios";
	window.localClasificacion = "";
	window.routeGetDatosGenerales = '/get_datos_generales';
	window.routeGetProductosAll = "/get_productos_all";
	window.routeCategoriasStore = '/store_categoria';
	window.routeGetProveedores = '/getProveedores';
	window.routeGetProductosMatriz = '/getProductosMatriz';
	window.routeGetTiendasVinculadas = '/getTiendasVinculadas';
	window.routeCrearSolicitud = '/crearSolicitud';
	window.routeGetProductos = '/getProductos';
	window.routeGetRequisicionesActivas = '/getRequisicionesActivas';
	window.routeGetRequisicionesSurtidas = '/getRequisicionesSurtidas';
	window.routeStatusProveedor = '/statusProveedor';
	window.routeAddProveedor = '/addProveedor';
	window.routeDeptosStore = '/store';
	window.routeDeptosStatusDepto = '/status_depto';
	window.routeCategoriasDelete = '/delete_cat';
	window.routeAddProductos = '/form_add_productos';
	window.routeFormEditProducto = '/form_edit_productos';
	window.routeConfigFormAddUnidadCompra = '/form_add_unidad_compra';
	window.routeConfigFormAddUnidadVenta = '/form_add_unidad_venta';
	window.routeProductosStock = '/stock';
	window.routeProductosEditProducto = '/editProducto';
	window.routeProductosMercanciaSinStock = '/mercanciaSinStock';
	window.routeSetProductosRequisicion = '/setProductosRequisicion';
	window.routeVerRequisicion = '/verRequisicion';
	window.routeAutorizarCompra = '/autorizarCompra';
	window.routeFinalizarCompra = '/finalizarCompra';
	window.routeGetCaja = '/getCaja';
	window.routeProductosComprar = '/productos.comprar';
	window.routeGetMovimientoMercancia = '/getMovimientoMercancia';
	window.routeGetMovimientoMercanciaDetalles = '/getMovimientoMercanciaDetalles';
	window.routeUpdateCantidadSolicitada = '/updateCantidadSolicitada';
	window.routeEnviarMovimientoMercancia = "/enviarMovimientoMercancia";
	window.routeEliminarMovimientoMercancia = "/eliminarMovimientoMercancia";
	window.routeIngresarMovimientoMercanciaDetalles = "/ingresarMovimientoMercanciaDetalles";
	window.routeRechazarMovimientoMercanciaDetalles = "/rechazarMovimientoMercanciaDetalles";
	window.routeProductosSincronizar = "/sincronizar";
</script>
<script src="/js/app-inventario.js%3Fid=818049daf0e81a067c700099dd947654"></script>

<script>
	function modal_add_producto_masivo() {
		$('#modal_add_producto_masivo').modal('show');
	}

	$('#form_producto_masivo').on('submit', function(e) {
		e.preventDefault();

		let formData = new FormData(this);

		$.ajax({
			url: "/form_add_productos_masivo",
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Agregar CSRF Token
			},
			beforeSend: function() {
				$('.btn-primary').prop('disabled', true).text('Subiendo...');
			},
			success: function(response) {
				$().toastmessage('showSuccessToast', "<br>" + response.message + "<br>");
				$('#modal_add_producto_masivo').modal('hide');
				$('#form_producto_masivo')[0].reset();
				$('#tbl_productos').DataTable().ajax.reload();
			},
			error: function(xhr) {
				let mensaje = xhr.responseJSON?.message || 'Ocurrió un error.';
				$('#alerta_error').removeClass('d-none').text(mensaje);
			},
			complete: function() {
				$('.btn-primary').prop('disabled', false).text('Subir Archivo');
			}
		});
	});

	// Variables globales para el stock modal
	window.currentProductoUnidadCompra = '';
	window.currentProductoUnidadVenta = '';
	window.currentProductoFactor = 1;

	// Función para actualizar las opciones del select con las unidades específicas del producto
	function actualizarOpcionesUnidadStock() {
		const factor = window.currentProductoFactor || 1;
		const unidadCompra = window.currentProductoUnidadCompra || 'Unidad de Compra';
		const unidadVenta = window.currentProductoUnidadVenta || 'Unidad de Venta';
		
		// Actualizar opción de unidad de compra
		$('#option_compra').text(unidadCompra.toUpperCase() + ' (Equivale a ' + factor + ' ' + unidadVenta + (factor === 1 ? '' : 's') + ')');
		
		// Actualizar opción de unidad de venta
		$('#option_venta').text(unidadVenta.toUpperCase());
	}

	// Función para cambiar la etiqueta del stock
	function cambiarEtiquetaStock() {
		const tipoUnidad = $('#tipo_unidad_stock').val();
		const factor = parseFloat($('#producto_factor').val()) || 1;
		
		if (tipoUnidad === 'compra') {
			$('#label_stock').text('CANTIDAD A AGREGAR (' + window.currentProductoUnidadCompra + ')');
			$('#conversion_info').text('Se agregará en unidades de compra');
		} else {
			$('#label_stock').text('CANTIDAD A AGREGAR (' + window.currentProductoUnidadVenta + ')');
			$('#conversion_info').text('Se convertirá automáticamente: ' + factor + ' ' + window.currentProductoUnidadVenta + ' = 1 ' + window.currentProductoUnidadCompra);
		}

		// Las etiquetas siempre muestran unidades de venta para mejor comprensión
		$('#unidad_stock_actual').text(window.currentProductoUnidadVenta);
		$('#unidad_nuevo_stock').text(window.currentProductoUnidadVenta);

		// Actualizar display de stock actual
		actualizarStockActual();
		// Recalcular nuevo stock
		calcularNuevoStock();
	}

	// Función para actualizar el display del stock actual
	function actualizarStockActual() {
		const stockActual = window.currentProductoStock || 0;
		const factor = parseFloat($('#producto_factor').val()) || 1;
		
		// Convertir stock de compra a venta (stock * factor)
		const stockEnVenta = stockActual * factor;
		$('#stock_actual_display').val(parseFloat(stockEnVenta).toFixed(2));
	}

	// Función para calcular el nuevo stock en tiempo real
	function calcularNuevoStock() {
		const cantidadAgregar = parseFloat($('#stock').val()) || 0;
		const stockActual = parseFloat(window.currentProductoStock) || 0;
		const tipoUnidad = $('#tipo_unidad_stock').val();
		const factor = parseFloat($('#producto_factor').val()) || 1;

		// Stock actual en unidades de venta
		const stockActualEnVenta = stockActual * factor;

		if (cantidadAgregar <= 0) {
			$('#nuevo_stock_display').val(stockActualEnVenta.toFixed(2));
			return;
		}

		let cantidadEnCompra = 0;
		if (tipoUnidad === 'compra') {
			cantidadEnCompra = cantidadAgregar;
		} else {
			cantidadEnCompra = cantidadAgregar / factor;
		}

		// Calcular nuevo stock en unidades de compra y luego convertir a venta
		const nuevoStockEnCompra = stockActual + cantidadEnCompra;
		const nuevoStockEnVenta = nuevoStockEnCompra * factor;
		
		$('#nuevo_stock_display').val(nuevoStockEnVenta.toFixed(2));
	}
</script>
		<script>
			window.noSugerirUrl = "/noSugerir";
		</script>
		<script src="/js/app-private.js%3Fid=a204b2bfe62c171855fbbba358a7c760"></script>
	</body>

	</html>