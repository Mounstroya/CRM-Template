<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
	body { font-family: sans-serif; font-size: 12px; }
	table { width: 100%; border-collapse: collapse; margin-top: 10px; }
	th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
	th { background-color: #eee; }
</style>
</head>
<body>
	<h2>{{ $traspaso->proveedor_id ? 'Pedido de compra' : 'Traspaso de mercancía' }} R-{{ $traspaso->no_requisicion }}</h2>
	<p>
		{{ $traspaso->proveedor_id ? 'Proveedor' : 'Sucursal' }}:
		{{ $traspaso->proveedor_id ? $traspaso->proveedor?->nombre : $traspaso->sucursal_origen_id }}
		— Generado el {{ now()->format('d/m/Y H:i') }}
	</p>
	<table>
		<thead>
			<tr>
				<th>Producto</th>
				<th>Cant. solicitada</th>
				<th>Cant. enviada</th>
				<th>Cant. recibida</th>
				<th>Costo unitario</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($traspaso->detalles as $d)
				<tr>
					<td>{{ $d->producto?->descripcion }}</td>
					<td>{{ $d->cantidad_solicitada }}</td>
					<td>{{ $d->cantidad_enviada }}</td>
					<td>{{ $d->cantidad_recibida }}</td>
					<td>{{ $d->costo_unitario }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
