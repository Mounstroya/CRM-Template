<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function tabla()
    {
        return view('partials.usuarios_tabla', ['usuarios' => User::orderBy('name')->get()]);
    }

    /**
     * Real route recovered from the live site: POST /usuarios-store. Real <select>
     * only offers Encargado/Vendedor/Auditor (Propietario isn't creatable via this UI).
     */
    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'type' => $request->input('type'),
            'password' => Hash::make($request->input('password') ?: str()->random(12)),
            'status' => 1,
        ]);

        return response()->json(['ok' => true, 'message' => 'Usuario creado correctamente.', 'usuario' => $user]);
    }

    /**
     * Real route: POST /getUsuario. The real response wraps the role in
     * `usuario.roles[0].name` (a Spatie-style roles relation) — this build keeps the
     * simpler flat `type` column but mirrors that exact JSON shape so the untouched
     * rescued JS (`e.roles[0].name`) keeps working without modification.
     */
    public function getUsuario(Request $request)
    {
        $user = User::findOrFail($request->input('id'));

        return response()->json([
            'usuario' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => [['name' => $user->type]],
            ],
        ]);
    }

    /**
     * Real route: PUT usuarios.update (method-spoofed from formEditUsuario's POST +
     * _method=PUT, matching the rescued JS/form unmodified).
     */
    public function update(Request $request)
    {
        $user = User::findOrFail($request->input('usuario_id'));
        $user->update($request->only(['name', 'email', 'type']));

        return response()->json(['ok' => true, 'message' => 'Usuario actualizado correctamente.', 'usuario' => $user]);
    }

    /** Real route: POST /eliminar (deactivate, not delete — matches the real "dar de baja" confirm text). */
    public function eliminar(Request $request)
    {
        $user = User::findOrFail($request->input('id'));
        $user->status = 0;
        $user->save();

        return response()->json(['ok' => true, 'message' => 'Usuario dado de baja.']);
    }

    /** Real route: POST /activar. */
    public function activar(Request $request)
    {
        $user = User::findOrFail($request->input('id'));
        $user->status = 1;
        $user->save();

        return response()->json(['ok' => true, 'message' => 'Usuario activado.']);
    }

    /**
     * Real route: POST /locales-getSucursales — feeds the sucursal dropdown when
     * creating a user. Confirmed live: returns the child branches (excludes the
     * top-level "Bodega Principal" parent).
     */
    public function getSucursales()
    {
        $sucursales = Auditoria::whereNotNull('parent_id')->orderBy('nombre')->get(['id', 'nombre']);

        return response()->json(['sucursales' => $sucursales]);
    }
}
