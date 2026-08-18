<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
	body { font-family: sans-serif; font-size: 12px; }
	table { width: 100%; border-collapse: collapse; margin-top: 10px; }
	th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
	th { background-color: #eee; }
	.total { text-align: right; font-weight: bold; }
</style>
</head>
<body>
	<h2>NOTA: A{{ $venta->no_venta }}</h2>
	<p>Fecha: {{ $venta->fecha_compra }}</p>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>Producto</th>
				<th>Precio</th>
				<th>Cant.</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($items as $i => $item)
				<tr>
					<td>{{ $i + 1 }}</td>
					<td>{{ $item['producto'] }}</td>
					<td>${{ number_format($item['precio'], 2) }}</td>
					<td>{{ $item['cantidad'] }}</td>
					<td>${{ number_format($item['total'], 2) }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<p class="total">Total: ${{ number_format($venta->total, 2) }}</p>
</body>
</html>
