<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EstudianteController extends Controller
{
    public function editProfile()
    {
        $estudiante = Auth::user()->load('estudiante')->estudiante;

        if (!$estudiante) {
            abort(404, 'Perfil de estudiante no encontrado.');
        }

        return view('estudiantes.edit', compact('estudiante'));
    }

    public function updateProfile(Request $request)
    {
        $estudiante = Auth::user()->estudiante;

        if (!$estudiante) {
            return back()->withErrors(['estudiante' => 'No se encontró el perfil del estudiante.']);
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cif' => 'required|string|max:20|unique:estudiantes,cif,' . $estudiante->id,
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $file = $request->file('foto');
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $destination = storage_path('app/public/estudiantes');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $data['foto'] = 'estudiantes/' . $filename;

            if ($estudiante->foto) {
                Storage::disk('public')->delete($estudiante->foto);
            }
        }

        if ($request->input('eliminar_foto') === "1" && $estudiante->foto) {
            Storage::disk('public')->delete($estudiante->foto);
            $data['foto'] = null;
        }

        Auth::user()->update([
            'name' => $data['nombre'],
            'email' => $data['email'],
        ]);

        Log::info("Foto {$data['foto']}");
        $estudiante->update([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'cif' => $data['cif'],
            'email' => $data['email'],
            'foto' => $data['foto'],
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
