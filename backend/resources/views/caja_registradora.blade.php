<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Favicons -->
    <link rel="shortcut icon" type="image/x-icon" href="favico/favicon.ico">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap.css">
	<link rel="stylesheet" href="toast/resources/css/jquery.toastmessage.css"  rel="stylesheet">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" integrity="sha384-OHBBOqpYHNsIqQy8hL1U+8OXf9hH6QRxi0+EODezv82DfnZoV7qoHAZDwMwEJvSw" crossorigin="anonymous">
	<title>Ventas</title>

	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/adminlte.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/app-private.css%3Fid=3a462871c0ee7353baff263b11e1f5fc.css">
		</head>

	<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
		<div class="modal fade" id="modalConfirmaCierre" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="titulo_nota" style="color: red">Corte de caja</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p style="font-size: 18px;">Registre el total contado de cada categoría y el retiro que hará de la caja</p>
				<!-- TABLA RESPONSIVA -->
				<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Contado</th>
							<th scope="col">Calculado</th>
							<th scope="col">Diferencia</th>
							<th scope="col">Retiro</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">Efectivo</th>
							<td><input type="number" id="contadoEfectivo" step="0.1" min="0" class="form-control-sm form-control"></td>
							<td><input type="text" id="calculadoTotalEfectivo" data-calculado="" class="form-control-sm form-control" readonly></td>
							<td>
								<p id="diferenciaEfectivo"></p>
							</td>
							<td><input id="retiroEfectivo" type="number" step="0.1" min="0" class="form-control-sm form-control"></td>
						</tr>
						<tr>
							<th scope="row">Transferencia</th>
							<td><input type="number" id="contadoTransferencia" step="0.1" min="0" class="form-control-sm form-control"></td>
							<td><input type="text" id="calculadoTotalTransferencia" data-calculado="" class="form-control-sm form-control" readonly></td>
							<td>
								<p id="diferenciaTransferencia"></p>
							</td>
							<td><input id="retiroTransferencia" type="number" step="0.1" min="0" class="form-control-sm form-control"></td>
						</tr>
						<tr>
							<th scope="row">Tarjeta</th>
							<td><input type="number" id="contadoTarjeta" step="0.1" min="0" class="form-control-sm form-control"></td>
							<td><input type="text" id="calculadoTotalTarjeta" data-calculado="" class="form-control-sm form-control" readonly></td>
							<td>
								<p id="diferenciaTarjeta"></p>
							</td>
							<td><input id="retiroTarjeta" type="number" step="0.1" min="0" class="form-control-sm form-control"></td>
						</tr>
						<tr>
							<th scope="row">Total</th>
							<td>
								<p id="contadoTotal"></p>
							</td>
							<td>
								<p id="calculadoTotal" data-calculado></p>
							</td>
							<td>
								<p id="diferenciaTotal"></p>
							</td>
							<td></td>
						</tr>
					</tbody>
				</table>
				</div>
				<p>NOTA: El sobrante después del retiro se agregará en la próxima caja</p>
				
				<!-- TABLA RESPONSIVA -->
				<div id="datosRecargas" class="table-responsive">
					
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="cerrarCaja()">Hacer cierre de caja</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalRecaudacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="titulo_nota" style="color: green">Recaudación cobro de servicios</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p style="font-size: 18px;">Registre el total que se retirará del cobro de servicio.</p>
				<!-- TABLA RESPONSIVA -->
				<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th scope="col">fecha</th>
							<th scope="col">Servicio</th>
							<th scope="col">Referencia</th>
							<th scope="col">Efectivo</th>
							<th scope="col">Transferencia</th>
							<!--th scope="col">Retiro</th>
							<th scope="col">Diferencia</th-->
						</tr>
					</thead>
					<tbody id="tblServiciosCobrados">
						
					</tbody>
				</table>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="finalizarRecaudacion()">Hacer recaudación</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_historial" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Seleccione una fecha</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input id="fecha_historico" type="date" class="form-control" max="2026-08-12" value="2026-08-11">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="consultaHistorico()">Consultar</button>
			</div>
		</div>
	</div>
</div>

<!--MODAL DETALLES DE CAJA-->

<div class="modal fade" id="modalDetallesCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="color: #ff7600;font-size: 18px;">Detalles de caja</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- tabla responsiva -->
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Fecha</th>
								<th scope="col">Referencia</th>
								<th scope="col">Movimiento</th>
								<th scope="col">Forma Pago</th>
								<th scope="col">Entrada</th>
								<th scope="col">Salida</th>
							</tr>
						</thead>
						<tbody id="tblDetallesCaja">
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!--MODAL RETIRO DE CAJA-->

<div class="modal fade" id="modalRetiro" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="color: #ff7600;font-size: 18px;"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEB0lEQVR4nO2W308jVRTHeTcmPhofTPwD9NXER/8BH4zPvhgTKVKghS3Q7cryo7SdaUvpjwXbmelPZqadaRHK70UUtkuXuIgENiwurCBmXVegoiAF+jVzZ0F+LC6bbF8MJ/kmJ2fuPZ97T86de0tKLu1/bw4t+5q9lPmALmM/Koo07Ie2stBbZ8B0fWjT3s7vO24UST5xn9YF/7Jo/G+cBFdxBd/DYZyWZ2YAnrv98C0Pwz3WQ2Lu8V74FodUf1SNebJpeOcG4F0YhOdW+kweRRQd26XLGLf1M+6dIzClZXebZ3pB6zjQZSxoXRDm6TQssQSsXBwtU72gjGG0zPWBaonCGhDJN6qCIzGrj4clJcM8nIKNjj0dF1FzlbNoHk6i1Rz5w+niD+gKdtOmC73yFMztVN1J4rSqRyXUDEuoyiZRmxJxlRfhMHYhIt5Fd3oenuYUzL44rgwkoP9Ghm5CRm1v4kweRa3mSE6pFl0f2qArg1/aSgPvngs+LkN/HE6TiL7ZHDI/72F8JY/7vx0g4BqCKSz859zjYLsp/KcrKhXoCnb9QmCLTYA8soRvV/KYWN3DvScFTD/ax9BcDm1GHrrsxcHeHwZAa7mdE+C6lAi7lUWTP4KqrHw0kdaHMbq8i8m1PXz/6wHmnxQwsrxLFsDa+0m5D8eaBAEOC4eGYOxiYP2EDLfJj0eTV9DT4YApxuMw7jTxmFrbR3ZtD/fXC/j6YR4zjw+wtAkkghnUJ0QyVumJzqYOPJ6qQYT2oDYVfz64ekxCZ2MndhcqcDvejAZGXbFSRmdNFBMre3iwAYyv5nHnl30CVRTxjKC2RwUozRV1uJFf1CIdsKNeEC5W6iZ/FO11ATiaOVSPHyv1dR7fLawToNJYSxsq9MffD9BuFEhVDhdpcYVIDsrGQXdbvhi46hwZBRGMaxCjy3ksrheOdjvYO4vWthfrau9zwUpzTaq+LiOjxckj6L2J2Qc5zK9uoy85Dec1Hvox9ayTfsjILwbWZ2Q02qNoskXQREegvyXDFOZhCnSRkluuhkgZW6+F4WwU4TJLoMo5sqDr7hjqEiIM6TiZr4C+uNFF/EYqgprBxPlgXTYJw1dx1HWLaqNkk8Svk0SyiwZfl5ow0EX+Uorf0K42nzEmkONUfVOCiVNPgqE/oc7vFskmngm2admckRdQHxeLJqshuOWZ6jsFLmPet2lZmark/nb6+K1iyCXJG8otdQKsmKXa/ypdyeVcvFRoF2QUQy5eKlA6btu7OPgvmNxSnzPvURomaDeEdtrCCbx0RSR4Mup9fQKsmLM8+Kay8zYmvt0WTOwUQ84OcYuqZH86+xTSMG9TGuYTSsN9WgzZNczH1lL29ZfwXLy0Syt5pv0D/1Kusci6/3cAAAAASUVORK5CYII="> Retiro de caja</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formRetiro">
				<div class="modal-body">
					<div class="form-group">
						<label for="referencia">Referencia:</label>
						<input type="text" class="form-control" id="referencia" name="referencia" placeholder="Ingrese la referencia" required>
					</div>
					<div class="form-group">
						<label for="monto">Monto:</label>
						<input type="number" class="form-control" id="monto" name="monto" step="0.1" placeholder="Ingrese el monto" required>
					</div>
					<div class="form-group">
						<label for="tipoRetiro">Cuenta de retiro:</label>
						<select class="form-control" id="tipoRetiro" name="tipoRetiro" required>
							<option value="">Seleccione el tipo de retiro</option>
							<option value="0">Efectivo</option>
							<option value="1">Transferencia</option>
							<option value="2">Tarjeta</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Registrar Retiro</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--MODAL DEPOSTIO DE CAJA-->

<div class="modal fade" id="modalDeposito" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="color: #ff7600;font-size: 18px;"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEB0lEQVR4nO2W308jVRTHeTcmPhofTPwD9NXER/8BH4zPvhgTKVKghS3Q7cryo7SdaUvpjwXbmelPZqadaRHK70UUtkuXuIgENiwurCBmXVegoiAF+jVzZ0F+LC6bbF8MJ/kmJ2fuPZ97T86de0tKLu1/bw4t+5q9lPmALmM/Koo07Ie2stBbZ8B0fWjT3s7vO24UST5xn9YF/7Jo/G+cBFdxBd/DYZyWZ2YAnrv98C0Pwz3WQ2Lu8V74FodUf1SNebJpeOcG4F0YhOdW+kweRRQd26XLGLf1M+6dIzClZXebZ3pB6zjQZSxoXRDm6TQssQSsXBwtU72gjGG0zPWBaonCGhDJN6qCIzGrj4clJcM8nIKNjj0dF1FzlbNoHk6i1Rz5w+niD+gKdtOmC73yFMztVN1J4rSqRyXUDEuoyiZRmxJxlRfhMHYhIt5Fd3oenuYUzL44rgwkoP9Ghm5CRm1v4kweRa3mSE6pFl0f2qArg1/aSgPvngs+LkN/HE6TiL7ZHDI/72F8JY/7vx0g4BqCKSz859zjYLsp/KcrKhXoCnb9QmCLTYA8soRvV/KYWN3DvScFTD/ax9BcDm1GHrrsxcHeHwZAa7mdE+C6lAi7lUWTP4KqrHw0kdaHMbq8i8m1PXz/6wHmnxQwsrxLFsDa+0m5D8eaBAEOC4eGYOxiYP2EDLfJj0eTV9DT4YApxuMw7jTxmFrbR3ZtD/fXC/j6YR4zjw+wtAkkghnUJ0QyVumJzqYOPJ6qQYT2oDYVfz64ekxCZ2MndhcqcDvejAZGXbFSRmdNFBMre3iwAYyv5nHnl30CVRTxjKC2RwUozRV1uJFf1CIdsKNeEC5W6iZ/FO11ATiaOVSPHyv1dR7fLawToNJYSxsq9MffD9BuFEhVDhdpcYVIDsrGQXdbvhi46hwZBRGMaxCjy3ksrheOdjvYO4vWthfrau9zwUpzTaq+LiOjxckj6L2J2Qc5zK9uoy85Dec1Hvox9ayTfsjILwbWZ2Q02qNoskXQREegvyXDFOZhCnSRkluuhkgZW6+F4WwU4TJLoMo5sqDr7hjqEiIM6TiZr4C+uNFF/EYqgprBxPlgXTYJw1dx1HWLaqNkk8Svk0SyiwZfl5ow0EX+Uorf0K42nzEmkONUfVOCiVNPgqE/oc7vFskmngm2admckRdQHxeLJqshuOWZ6jsFLmPet2lZmark/nb6+K1iyCXJG8otdQKsmKXa/ypdyeVcvFRoF2QUQy5eKlA6btu7OPgvmNxSnzPvURomaDeEdtrCCbx0RSR4Mup9fQKsmLM8+Kay8zYmvt0WTOwUQ84OcYuqZH86+xTSMG9TGuYTSsN9WgzZNczH1lL29ZfwXLy0Syt5pv0D/1Kusci6/3cAAAAASUVORK5CYII="> Depósito de caja</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formDeposito">
				<div class="modal-body">
					<div class="form-group">
						<label for="referencia">Referencia:</label>
						<input type="text" class="form-control" id="referencia" name="referencia" placeholder="Ingrese la referencia" required>
					</div>
					<div class="form-group">
						<label for="monto">Monto:</label>
						<input type="number" class="form-control" id="monto" name="monto" step="0.1" placeholder="Ingrese el monto" required>
					</div>
					<div class="form-group">
						<label for="tipoDeposito">Cuenta de depósito:</label>
						<select class="form-control" id="tipoRetiro" name="tipoDeposito" required>
							<option value="">Seleccione el tipo de depósito</option>
							<option value="0">Efectivo</option>
							<option value="1">Transferencia</option>
							<option value="2">Tarjeta</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Registrar Retiro</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalDetalleMovimiento" tabindex="-1" aria-labelledby="modalDetalleMovimientoLabel" aria-hidden="true" style="overflow-y: auto !important;">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDetalleMovimientoLabel">Detalle del Movimiento</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="detalleMovimiento">
				<!-- Contenido del modal se insertará aquí -->
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
        <a class="nav-link" data-widget="pushmenu" href="/caja-registradora#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/mi-local" class="nav-link"><i class="fas fa-home"></i> Inicio</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/mi-local/productos" class="nav-link"><i class="fas fa-database"></i> Inventario</a>
      </li>
            <li class="nav-item d-none d-sm-inline-block">
        <a href="productos/existencia-excel" class="nav-link"><i class="fas fa-file-excel"></i> Lista de precios</a>
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
        <a class="nav-link" data-toggle="dropdown" href="/caja-registradora#">
                    <i class="fa fa-inbox" style="font-size: 25px;"></i>
                    <span class="badge badge-danger navbar-badge" id="totalNotify">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="/mi-local/productos" class="dropdown-item dropdown-footer">VER PEDIDOS</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="/caja-registradora#">
                      <i class="far fa-bell animated infinite swing" style="font-size: 25px;"></i>
                    <span class="badge badge-warning navbar-badge" id="hTotalSugerencias">65</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header" style="font-weight: bold;color: red;">65 PRODUCTOS POR SURTIR</span>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia28">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUT206 AUDIFONO BLUETOOTH
              <span class="float-right text-sm text-danger" onclick="no_sugerir(28)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia37">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUT250 DIADEMA BT 1HR
              <span class="float-right text-sm text-danger" onclick="no_sugerir(37)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia190">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> BG-139  DIADEMA DE GATO LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(190)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia199">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> BS-09 BARRA DE SONIDO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(199)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia226">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> MB-152 BOCINA MINI LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(226)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia235">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> P47 DIADEMA COLORES
              <span class="float-right text-sm text-danger" onclick="no_sugerir(235)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia244">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> P47M DIADEMA GATO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(244)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia262">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> PAST-001 PROYECTOR ASTRONAUTA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(262)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia271">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA314T BOCINA LINK BITS 3&quot;  TWS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(271)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia298">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> VA369T BOCINA 3&quot; LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(298)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia316">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> OSO GRADUACION
              <span class="float-right text-sm text-danger" onclick="no_sugerir(316)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia325">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUDIFONOS SONYN202
              <span class="float-right text-sm text-danger" onclick="no_sugerir(325)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia334">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FR-3206 AUDIFONO SAMSUNG AKG S10
              <span class="float-right text-sm text-danger" onclick="no_sugerir(334)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia379">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUDIFONOS ZTE FRESHSUN
              <span class="float-right text-sm text-danger" onclick="no_sugerir(379)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia388">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SMARTWATCH ZTE FRESHFUN
              <span class="float-right text-sm text-danger" onclick="no_sugerir(388)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia397">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> X10 SMARTWATCH EARPHONES X10
              <span class="float-right text-sm text-danger" onclick="no_sugerir(397)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia496">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CUBO IPHONE 20W SIN CAJA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(496)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia514">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CUBO SAMSUNG  45W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(514)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia550">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR IPHONE 16 C A C 35W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(550)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia568">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR SAMSUNG 45W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(568)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia580">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR SAMSUNG V8
              <span class="float-right text-sm text-danger" onclick="no_sugerir(580)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia590">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> MJ-6699 AUDIFONO INALAMBRICO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(590)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia600">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TB-6310 SMARTWATCH T500
              <span class="float-right text-sm text-danger" onclick="no_sugerir(600)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia640">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARRITO 8 WHEEL STUNT
              <span class="float-right text-sm text-danger" onclick="no_sugerir(640)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia670">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGADOR XIAOMI TIPO C 33W
              <span class="float-right text-sm text-danger" onclick="no_sugerir(670)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia740">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SOMBRILLAS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(740)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia770">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> VA370T BOCINA 3
              <span class="float-right text-sm text-danger" onclick="no_sugerir(770)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia871">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> KTS-2048 BOCINA 8&quot; LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(871)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia881">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CARGNEBROPROMO PROMOCION CARGADOR NEBRO TIPO C
              <span class="float-right text-sm text-danger" onclick="no_sugerir(881)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia891">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> LABUBU MUÑECO TIPO ORIGINAL
              <span class="float-right text-sm text-danger" onclick="no_sugerir(891)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia911">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> MB-168 BOCINA 3&quot; LINK BITS COLORES
              <span class="float-right text-sm text-danger" onclick="no_sugerir(911)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia945">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XB-5516 POWER BANK 2000 MAH
              <span class="float-right text-sm text-danger" onclick="no_sugerir(945)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia957">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FR-3232 AUD EARPODS LIGHTNING CONNECTOR
              <span class="float-right text-sm text-danger" onclick="no_sugerir(957)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia969">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FM-8226 BARRA DE SONIDO A500
              <span class="float-right text-sm text-danger" onclick="no_sugerir(969)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia981">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XM-9010 CUBETA PARA BEBIDAS CON BOCINA Y LUCES
              <span class="float-right text-sm text-danger" onclick="no_sugerir(981)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1008">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA1238TKL BOCINA 12&quot;C/MICROFONO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1008)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1020">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA8061T BOCINA 8&quot;
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1020)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1032">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> SA438TBOCINA RADIO 4&quot;
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1032)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1056">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIEADEMA DE AIRE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1056)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1068">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIEADEMA DE AIRE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1068)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1092">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FR-2007 DIADEMA BOSE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1092)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1104">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIADEMA DE AIRE
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1104)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1116">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> AUT114 AUDIFONO BLUETOOTH
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1116)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1152">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> ESTRELLAS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1152)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1327">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FM5125 BOCINA SPLASHPROOF
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1327)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1571">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TWS G-TIDE H11
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1571)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1643">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> DIADEMA SONY WH-1000XM5
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1643)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1667">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> GAR264 BATERIA PORTATIL 10000 MAH 3A
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1667)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1694">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TWS G-TIDE L22
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1694)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1886">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> CAB177 CABLE V8 2.1A 1 METRO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1886)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1934">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA KUROMI 7&quot; ANDROID 15 256/8 GB
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1934)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1958">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA STICH 7&quot; ANDROID 15 256/8 GB
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1958)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia1982">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA BOB ESPONJA 10&quot; A08   AZUPIK DOBLE SIM ANDROID 15 512/12 GB
              <span class="float-right text-sm text-danger" onclick="no_sugerir(1982)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2006">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TABLETA UMIIO S25 ULTRA 10.1&quot; 128/12GB ANDROID 13
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2006)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2099">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> KTS-1841 BOCINA 6.5&quot; LINK BITS
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2099)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2165">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> GAR159 BATERIA PORTATIL 20000 2.1
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2165)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2401">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> P9PROMO DIADEMA P9 PROMOCION
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2401)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2426">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> VA370PROMO PROMOCION BOCINA VA370T LINK BITS 3&quot;
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2426)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2451">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XR3101 EXTRA BASS EARPHONE XINMI
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2451)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2476">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> XR3109 STEREO HEADSET  XINMI
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2476)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2501">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TR6061 OWS T2 AUD DE BOLA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2501)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2526">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> JXQ1403 EXTENSION 5 METROS XINMI
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2526)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2551">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> FEE-40313 DIADEMA GUERRERAS K-POP
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2551)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2676">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> PLAYERA SELECCION MEXICANA
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2676)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <div class="dropdown-divider"></div>
          <div id="sugerencia2997">
            <a href="/caja-registradora#" class="dropdown-item">
              <i class="mr-2"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAADOklEQVRIieWWzW8bRRiHn9mPrNdObGfrOHWLSSBU4quAVEBCogIhkPgzqISoxIlI+QO4V0AvHIoQlXrkRFElFISAwgEhcQAEoUWhSeOv2ElK4ti7a+/ODocQN6apa0eOBOJ3mtVvZp55Z+d9Z+D/JnE34503P8orQ8sedGJdUxuz588s9w0+N3cpYcnoipScNgzaliG8QaHtMIoFAZam80MEr86eP7PZE3xu7lLCCqOvJzPGY5apbClh+rgxKJfiqiSUCoRqFlajP5RQL/wTru02LrxxIW6F0ZdZR3/8obxui7v/hb41fcxM3J/TjyrJ1XdnP3T2ep1w2nHrsqGLU4m40Mtrkq2mJIoE5TU5MHDvWNMQWdvS4l6Lj4GXd/sIgPfeupjWNVF+5GTSPnh8vXV9Ydv13eDRufdfvwm3I07HbN1/+rn0oYFLK54fNKNxoAvctxZ+3mZzo935Hk0aPHEqNfBCBgZncxZ2XL8NHtN79B4iODMxQmZi5ECwvdLu3eVw1De43Yr4/EqNlaXuQlYu+MxfruJ7g6VdX2ApFfOfVqkUfb75Yp1SwQegVmnx1fwaq5UWn31SIwyi4YKFgKeeSZOdtDj9Ugbb3hlmmIIXX8mQGjd59vlxhOi/2vUF1jRBfnonxceSOk5m53A5mRFSjgnA8XwM3Rgy+DA0EPhYPoY92p23lqWTnxq84PWVx/WtgI21gGTaZLXUusN3MiMsLbo4jtnZ+qGA/1wPqRTu/R5QkRoueGrGZmpmuPfHv+dwuQ1J+e8CsXitAUC54OE2JJ4rKa54XV6l5NPYDvE9SWF512uCgmrZp74V7gveu9WiXnVZueFSqQaMhAm+u7pJ2or49acmuUkTTRcUim1iMuh4C7+4ZI4YxCyNG0s+tgr4/ttNkmbIb9c8UkmdB07E9wfLQKoo1K315W0sFDlHUK95PHnCpF7zyDkCixCk4L4jdHlHU2ASorUF+YxGveZxcsakse4zMQaGFnHrZoMoiOxQaVEXuDFRLCUaDwY//h6YmkB1r22/4i97eHeOUwr8llJJ6S92tne38fZrF2OpOA9LQxzsZu8hoUQ02vKvn/3grDvsuf87+gvqS028ScYMPgAAAABJRU5ErkJggg=="/></i> TERMO MUNDIAL LARGO
              <span class="float-right text-sm text-danger" onclick="no_sugerir(2997)" title="Marcar leído"><i class="far fa-times-circle" style="font-size: 18px;"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
                    <a href="/mi-local/productos" class="dropdown-item dropdown-footer">IR A MI ALMACÉN</a>
        </div>
      </li>
            <li class="nav-item">
        <a class="nav-link" href="/caja-registradora#" data-toggle="modal" data-target="#updatePasswordModal">
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
          'X-CSRF-TOKEN': 'TnncInpaEGGbJzbpT4sqwEzlwJ0CeKyE3iyGXLeA'
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
				<a href="/caja-registradora#" class="brand-link" style="text-align: center ;">
					<!--img-circle  elevation-3-->
										<img alt="panel-logo" class="brand-image" style="opacity: 1; float:none !important;" src="logo-fd3.jpeg">
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
      <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
    </div>
    <div class="info">
      <a href="/caja-registradora#" class="d-block">Administrador</a>
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
	</div>
	<div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="/mi-local">Mi local</a></li>
			<li class="breadcrumb-item txt-blue active">Caja registradora</li>
		</ol>
	</div>
</div>
					</div><!-- /.container-fluid -->
				</div>
				<!-- /.content-header -->
				<!-- Main content -->
				<section class="content">
					<div class="container-fluid">

												
												<!-- Info boxes -->

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="card">
			<div class="card-header">
				<h5 class="card-title" style="font-weight: bold">CAJA REGISTRADORA</h5>
				<div class="card-tools">
					<a href="/punto-de-venta" class="btn btn-warning btn-tool" style="color: #000; background-color:#ffc107">
						Punto de venta <i class="fa fa-arrow-circle-right"></i>
					</a>
				</div>
				<div class="card-tools">
					<div class="btn-group">
						<div class="dropdown-menu dropdown-menu-right" role="menu">
							<a href="/caja-registradora#" class="dropdown-item">Historial</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" style="border-right: 2px solid #C0C0C0">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="d-flex">
								<b style="font-weight: 100;font-size: x-large;color: #0086de;">Información Caja del
									<input type="date" class="form-control" style="display: inline;width:160px" id="fecha_inicio" value="2026-08-12" onchange="getCajas()">
									al <input type="date" class="form-control" style="display: inline;width:160px" id="fecha_final" value="2026-08-12" onchange="getCajas()"> </b>
							</div>
							<br>
						</div>
						<div class="table-responsive">
							<table class="table">
								<thead class="thead-dark">
									<tr>
										<th scope="col">#</th>
										<th scope="col">Apertura</th>
										<th scope="col">Efectivo</th>
										<th scope="col">Transferencia</th>
										<th scope="col">Tarjeta</th>
										<th scope="col">Total</th>
										<th scope="col">Detalles</th>
									</tr>
								</thead>
								<tbody id="tblCajas">
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<p class="text-center">
							<strong>FUNCIONES DE CAJA</strong>
						</p>
						<img id="status_caja" title="Activar caja" status="0" onclick="activarCaja()" style="cursor:pointer" src="data:image/png;base64,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" />
						<span id="textstatus" style="font-weight:bold;font-size: 20px;color:#e0678f"> Activar caja.....</span>
						<hr style="border: 1px solid #C0C0C0">
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<p style="font-size: 18px;font-weight:bold;color:#ef6464;cursor:pointer" onclick="retiro()"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAACmklEQVR4nO2Xz0vbYBjH87fsL9j+gLGDCMpYE3PdLoIRHPGgiDh22SUXGZOtUt3JzqSndlbdDll1PTSBWnGmg7XCrLa44LQ62eZgh7Y84wlLbEO7JP2RRrovfCE86Zv30+d53vdNCKKXlB4amkUTXtQuRT36Oj19cToz812h6ceEl/SRJB+qk5PF33NzgD6emjpXaJolvKAdn+/+0fj4sQ6nW52YOFEo6kFX4RSf725+bOyLGU53nmXVDyR5rytwOyR5O8cw+UZwug8Y5miXou64Cqf099/8PDy8bwWnOzcykksPDNxyBW67r+/G9uCgmmOY/YO/PhwdVc1QGNPv429xDI4luqE0TQfNgBgjvKJ0zwOyoScqJ/oLzXp29dkv/1s/VBtjrTwTmYwSYSBWeA9eMif6C70BGM6sQSDBQzD5GvhUVHMwGdFieK9rgOLhhgYR25OhXCmDWRiLZWUISDyI+U13ARHueTwIZ5cXYKXi5Td4EX/lGLIlQMycHbhqSMykK4BaX+3J4FSxrAyRzJvWAbEUi7IA/FZUM15XlwczUa/nrFSqlGFBEsDuPA0BF+UQnP48Nx6M1wtyyBi4lIxAs1pKhsHuPA0B8d+YtbwVNQaGUmtNAwqpVbA7j0PAFWOg0FHAFTslFmpSf/LjzFTicJtKLPxzHstFgulerrdIErzW8E5VqpRrthqreVrbZjKSY0DxUwLC2XWXNmqJ1zZfu9JWaOJqi+k4IJYCj6+iDUiEw2NRzG+4B6hDBiQe3mWkuj2JMSwr9qxTuLYAXvXkunZC4CuW/rqFqxXhnfRcxwBjHfL1AmS1j6b50tP4S/CCOXG+VPPR5LUsctXZ81oWuXrZ81IWuXrZq81i8x/bXBvcMHv/RTSnP0w6nQfvYpfbAAAAAElFTkSuQmCC"> HACER UN RETIRO</p>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<p style="font-size: 18px;font-weight:bold;color:#50c27d;cursor:pointer" onclick="deposito()"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC4UlEQVR4nO2XWU8TURTHxyWaGBMTH3w0fhNfffDFh4YwwCcgUQiN8UmCCYmvBjQMhbZMl4EG6EKppQsFKjDdlxgXNIIioAQo1qBAS485gy2TmpFpKdNR+Cf/5OTeuXN+d507BHGqk6BGY1vXvf5HWjm50djWVQBsd3YsuBa8ICe3OzsWTg6gbe45aEIDQE3rOWM88s5ZfcDhV3Z4OkmDJe6GxY0V2M7scMbYHHdD5yTNPVMVQH10CAZCo5DZy4KQMtkM9IfsoIsOSgvIJM1gT44LghXLnhiHgaRFGkDnBzd0+xkoVdQUA855T2UArW8d0DNr5Iwxv66HNcLS5teSAT+nvkAPy4DYPIKAOBWGgA1SW2nOGJuS1kLD7helj15e2FZsHkFAqmj6cpCDLr+x0JBmzVCuaNYMYvMIAuoD1j9erA8cLHDsabkyBA5G6LA8goDYi+MbwWEQm0cQENfB/tr4xhl7xV8bxVNTirCt2Dx/38VzDlCxDGeM+XVYtpQqfRcvplagl+0HsXnKPwfnPWWNIlXJc/DQL0nCDCMJr2g4WwKPlYPpO3ZAbhfGBoEJ2mE3mxEEwzpjcAQMMYm/xXmbX4/u31hiLvi0vgw/d3c4f1xf5sqwzvJmtOT3VgzQ9dt499Py7oMY2+VwH3Qdo/8twMYj/tU9Njx4T9seAt9YVrG/uqMq2Etqt6buA99YRshFwVPA/2kE/ZTiekRd2xnrq1flzapq5ooBsYz/DLbBtpJAhntJ1erY3R/FUEJeHWvaDqrJPkIqARBnwmqSWXM17R4Gt+ZqzoQ0tUPQ2nqWkFImk+JcWEPaNrzNWSG4DU/zXkRT5/C13jxPVEMvTYoLYQ3pTnlbcsVwm96WXERN+hxPbl0kqqkwdftSVFvnT/uUBbj0hBKidN2Mr1NxmZCD3JTiSryvPoJg3yeUEKfrwzMqxVVCTpp+dudaQtcQS+oakhgTctSspuYGutockuoXliGLjo/T1skAAAAASUVORK5CYII="> HACER UN DEPÓSITO</p>
							</div>

							<div class="col-12">
								<button class="btn btn-primary" onclick="recaudacion()">RECAUDACIÓN PAGO SERVICIOS</button>
							</div>
						</div>
					</div>
				</div>
			</div>
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
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> <a href="/caja-registradora#" target="_blank">FD3-ACCESORIOS</a> | Icons <a href="https://iconos8.es/">Icons8</a>
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
		<script src="plugins/jquery/jquery.min.js"></script>
		<!-- Bootstrap -->
		<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- overlayScrollbars -->
		<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
		<!-- AdminLTE App -->
		<script src="dist/js/adminlte.js"></script>
		<!-- OPTIONAL SCRIPTS -->
		<script src="dist/js/demo.js"></script>
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

		<script src="plugins/moment/moment.min.js"></script>
		<script src="plugins/moment/locale/es.js"></script>
		<script src="toast/javascript/jquery.toastmessage.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/es.min.js"></script>


<script src="jspdv/caja.js"></script>

<script>
	window.routeGetCajas = '/getCajas';
	window.routeGetDatosCierreCaja = '/getDatosCierreCaja';
	window.routeCajaStatus = '/caja_status';
	window.routeCajaRetiro = '/retiro';
	window.routeCajaDeposito = '/deposito';
	window.routeCajaDetalleMovimiento = '/detalleMovimiento';
	window.routeServiciosRecaudacion = '/servicios/recaudacion';
	window.routeFinalizarRecaudacion = '/servicios/finalizarRecaudacion';
</script>
<script src="js/app-caja.js%3Fid=c22da927863f7df7243e23f1cdcda28b"></script>
		<script>
			window.noSugerirUrl = "/noSugerir";
		</script>
		<script src="js/app-private.js%3Fid=a204b2bfe62c171855fbbba358a7c760"></script>
	</body>

	</html>