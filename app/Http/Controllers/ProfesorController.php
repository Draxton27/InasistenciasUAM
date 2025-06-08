<?php
namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\Clase;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::create([
            'name' => $data['nombre'],
            'email' => $data['email'],
            'password' => Hash::make('password123'),
            'role' => 'profesor',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            if($file->isValid()) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $destination = storage_path('app/public/profesores');

                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $file->move($destination, $filename);
                $data['foto'] = 'profesores/' . $filename;
            } else {
                return back()->withErrors(['foto' => 'El archivo de foto no es válido.'])->withInput();
            }
        }

        $data['user_id'] = $user->id;
        $profesor = Profesor::create($data);

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
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {

            $file = $request->file('foto');
            if ($file->isValid()) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $destination = storage_path('app/public/profesores');

                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $file->move($destination, $filename);
                $data['foto'] = 'profesores/' . $filename;
            } else {
                return back()->withErrors(['foto' => 'El archivo de foto no es válido.'])->withInput();
            }
        }

        if ($request->input('eliminar_foto') === "1" && $profesor->foto) {
            if (Storage::disk('public')->exists($profesor->foto)) {
                Storage::disk('public')->delete($profesor->foto);
                Log::info("Foto eliminada del sistema de archivos: {$profesor->foto}");
            } else {
                Log::warning("No se encontró la foto en el disco para eliminar: {$profesor->foto}");
            }

            $data['foto'] = null;
        }

        if ($profesor->user) {
            $profesor->user->update([
                'name' => $data['nombre'],
                'email' => $data['email'],
            ]);
            Log::info("Usuario actualizado para el profesor ID {$profesor->id}");
        }
        $profesor->update($data);
        $profesor->clases()->detach();

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