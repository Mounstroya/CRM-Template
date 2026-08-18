<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
	body { font-family: sans-serif; font-size: 12px; }
	h2 { margin-bottom: 0; }
	table { width: 100%; border-collapse: collapse; margin-top: 10px; }
	th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
	th { background-color: #eee; }
	.diff-pos { color: green; }
	.diff-neg { color: red; }
</style>
</head>
<body>
	<h2>Reporte de auditoría — {{ $sucursal->nombre }}</h2>
	<p>Auditoría no. {{ $sucursal->ultima_auditoria_no }} — Generado el {{ now()->format('d/m/Y H:i') }}</p>
	<table>
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
			@foreach ($conteos as $c)
				<tr>
					<td>{{ $c->producto?->clave }}</td>
					<td>{{ $c->producto?->descripcion }}</td>
					<td>{{ $c->stock_sistema }}</td>
					<td>{{ $c->stock_contado }}</td>
					<td class="{{ $c->diferencia > 0 ? 'diff-pos' : ($c->diferencia < 0 ? 'diff-neg' : '') }}">{{ $c->diferencia }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
