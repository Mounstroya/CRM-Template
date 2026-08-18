<table class="table table-hover table-sm">
    <thead class="thead-light">
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($usuarios as $u)
        <tr>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->type }}</td>
            <td><span class="badge {{ $u->status ? 'badge-success' : 'badge-default' }}">{{ $u->status ? 'Activo' : 'Inactivo' }}</span></td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>
