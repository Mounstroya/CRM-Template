<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="/favico/favicon.ico">
	<link rel="stylesheet" href="/toast/resources/css/jquery.toastmessage.css">
	<title>Toma de auditoría — {{ $sucursal->nombre }}</title>
	<link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<link rel="stylesheet" href="/dist/css/adminlte.min.css">
	<link rel="stylesheet" href="/css/app-private.css%3Fid=3a462871c0ee7353baff263b11e1f5fc.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">
	<nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
		<ul class="navbar-nav">
			<li class="nav-item"><a href="/auditoria" class="nav-link"><i class="fas fa-arrow-left"></i> Volver a auditoría</a></li>
			<li class="nav-item d-none d-sm-inline-block"><a href="/mi-local" class="nav-link"><i class="fas fa-home"></i> Inicio</a></li>
		</ul>
	</nav>

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6"><h1 class="m-0 text-dark">Toma de auditoría</h1></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/mi-local">Mi local</a></li>
							<li class="breadcrumb-item"><a href="/auditoria">Auditoría</a></li>
							<li class="breadcrumb-item active">{{ $sucursal->nombre }}</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<section class="content">
			<div class="container-fluid">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title" style="font-weight:bold">
							{{ $sucursal->nombre }} — Auditoría no. {{ $sucursal->ultima_auditoria_no }}
							@if ($sucursal->ultima_auditoria_fin)
								<span class="badge badge-success">FINALIZADA</span>
							@else
								<span class="badge badge-warning">EN PROCESO</span>
							@endif
						</h5>
						<div class="card-tools">
							<button type="button" class="btn btn-success" id="btnFinalizarAuditoria" @if($sucursal->ultima_auditoria_fin) disabled @endif>
								<i class="fas fa-check"></i> Finalizar auditoría
							</button>
						</div>
					</div>
					<div class="card-body table-responsive">
						<table class="table table-bordered table-striped" id="tblConteo">
							<thead>
								<tr>
									<th>Clave</th>
									<th>Producto</th>
									<th>Stock sistema</th>
									<th>Stock contado</th>
									<th>Diferencia</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($productos as $p)
									@php $conteo = $conteos->get($p->id); @endphp
									<tr data-producto-id="{{ $p->id }}">
										<td>{{ $p->clave }}</td>
										<td>{{ $p->descripcion }}</td>
										<td>{{ $p->stock }}</td>
										<td>
											<input type="number" class="form-control input-conteo" style="width:120px"
												value="{{ $conteo->stock_contado ?? '' }}"
												@if($sucursal->ultima_auditoria_fin) disabled @endif>
										</td>
										<td class="celda-diferencia">{{ $conteo->diferencia ?? '' }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</section>
	</div>

	<footer class="main-footer">
		Copyright &copy;{{ date('Y') }} <a href="/mi-local">FD3-ACCESORIOS</a>
	</footer>
</div>

<script src="/plugins/jquery/jquery.min.js"></script>
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/dist/js/adminlte.js"></script>
<script src="/toast/javascript/jquery.toastmessage.js"></script>
<script>
	var routeGuardarConteo = "{{ url('/auditoria/'.$sucursal->ultima_auditoria_id.'/conteo') }}";
	var routeFinalizarAuditoria = "{{ url('/auditoria/'.$sucursal->ultima_auditoria_id.'/finalizar') }}";
	var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	document.querySelectorAll('.input-conteo').forEach(function (input) {
		input.addEventListener('change', function () {
			var row = input.closest('tr');
			var productoId = row.getAttribute('data-producto-id');
			var stockSistema = parseInt(row.children[2].textContent, 10);
			var stockContado = parseInt(input.value, 10);
			if (isNaN(stockContado)) return;

			fetch(routeGuardarConteo, {
				method: 'POST',
				headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/x-www-form-urlencoded' },
				body: 'producto_id=' + productoId + '&stock_contado=' + stockContado
			})
				.then(function (r) { return r.json(); })
				.then(function (data) {
					row.querySelector('.celda-diferencia').textContent = data.conteo.diferencia;
					$().toastmessage('showSuccessToast', 'Conteo guardado');
				});
		});
	});

	document.getElementById('btnFinalizarAuditoria').addEventListener('click', function () {
		if (!confirm('¿Finalizar la auditoría? Ya no se podrán editar los conteos.')) return;
		fetch(routeFinalizarAuditoria, {
			method: 'POST',
			headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/x-www-form-urlencoded' },
		})
			.then(function (r) { return r.json(); })
			.then(function (data) {
				$().toastmessage('showSuccessToast', data.message + ' (' + data.diferencias + ' productos con diferencia)');
				setTimeout(function () { window.location.href = '/auditoria'; }, 1500);
			});
	});
</script>
</body>
</html>
