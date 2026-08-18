<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FusionD3 | Iniciar sesión</title>
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
</head>
<body style="background:#f4f6f9;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <img src="{{ asset('logo-fd3.jpeg') }}" style="width:80px;" alt="Logo">
                        <h2>Iniciar sesión</h2>
                        <p class="text-muted mb-0">FusionD3 — copia de respaldo</p>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="email">Correo electrónico</label>
                                <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
