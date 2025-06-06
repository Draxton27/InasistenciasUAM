<?php
namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\Clase;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfesorController extends Controller
{
    public function index()
    {
        $profesores = Profesor::with('clases')->get();
        return view('profesores.index', compact('profesores'));
    }
    public function create()
    {
        $clases = \App\Models\Clase::orderBy('name')->get();
        return view('profesores.create', compact('clases'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            // 'telefono' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $data['nombre'],
            'email' => $data['email'],
            'password' => Hash::make('password123'),
            'role' => 'profesor',
        ]);

        $profesor = Profesor::create([
            'user_id' => $user->id,
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            // 'telefono' => $data['telefono'],
        ]);

        if ($request->has('clase_grupo')) {
            foreach ($request->clase_grupo as $entry) {
                if (!empty($entry['clase_id'])) {
                    $profesor->clases()->attach($entry['clase_id'], [
                        'grupo' => $entry['grupo'] ?? null
                    ]);
                }
            }
        }


        return redirect()->route('profesores.index')->with('success', 'Profesor creado exitosamente.');
    }

    public function edit(Profesor $profesor)
    {
        $clases = Clase::orderBy('name')->get();
        return view('profesores.edit', compact('profesor', 'clases'));
    }

    public function update(Request $request, Profesor $profesor)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:profesores,email,' . $profesor->id,
        ]);

        $profesor->update($data);

        $clases = $request->input('clases', []);
        $grupos = $request->input('grupos', []);

        $sincronizar = [];

        foreach ($clases as $claseId) {
            $grupo = $grupos[$claseId] ?? null;
            $sincronizar[$claseId] = ['grupo' => $grupo];
        }

        $profesor->clases()->detach(); // limpia todo

        if ($request->has('clase_grupo')) {
            foreach ($request->clase_grupo as $entry) {
                if (!empty($entry['clase_id'])) {
                    $profesor->clases()->attach($entry['clase_id'], [
                        'grupo' => $entry['grupo'] ?? null
                    ]);
                }
            }
        }


        return redirect()->route('profesores.index')->with('success', 'Profesor actualizado.');
    }

    public function destroy($id)
    {
        $profesor = Profesor::findOrFail($id);

        $user = $profesor->user;

        $profesor->clases()->detach();

        $profesor->delete();

        if ($user) {
            $user->delete();
        }

        return redirect()->route('profesores.index')->with('success', 'Profesor y su usuario eliminados correctamente.');
    }

}