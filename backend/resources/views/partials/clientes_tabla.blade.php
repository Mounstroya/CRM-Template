<table class="table table-hover table-sm">
    <thead class="thead-light">
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Nivel</th>
            <th>Status</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $c)
        <tr>
            <td>{{ $c->nombre }}</td>
            <td>{{ $c->correo }}</td>
            <td>{{ $c->telefono }}</td>
            <td>{{ $c->nivel }}</td>
            <td>
                <span class="badge {{ $c->status === 'Activo' ? 'badge-success' : 'badge-default' }}">{{ $c->status }}</span>
            <td>
                <button type="button" class="btn btn-primary btn-sm" onclick="editarCliente({{ $c->id }})">Editar</button>
                @if($c->status === 'Activo')
                    <button type='button' class='btn btn-danger btn-sm' onclick='eliminarCliente({{ $c->id }})'>Desactivar</button>
                @else
                    <button type='button' class='btn btn-success btn-sm' onclick='activarCliente({{ $c->id }})'>Activar</button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
