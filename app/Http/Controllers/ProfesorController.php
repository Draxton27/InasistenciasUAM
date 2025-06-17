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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
    $validator = Validator::make($request->all(), [
    'nombre' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    'clase_grupo.*.grupo' => 'nullable|integer|min:1',
], [
    'clase_grupo.*.grupo.integer' => 'El grupo debe ser un número válido.',
    'clase_grupo.*.grupo.min' => 'El grupo debe ser mayor a 0.',
]);


    // Validación personalizada: clase + grupo no deben repetirse
    $validator->after(function ($validator) use ($request) {
        $combinaciones = [];

        foreach ($request->input('clase_grupo', []) as $index => $entry) {
            $clase = $entry['clase_id'] ?? null;
            $grupo = $entry['grupo'] ?? null;

            if ($clase && $grupo) {
                $clave = $clase . '-' . $grupo;

                if (in_array($clave, $combinaciones)) {
                    $validator->errors()->add("clase_grupo.$index.grupo", "Ya se asignó este grupo a esta clase.");
                } else {
                    $combinaciones[] = $clave;
                }
            }
        }
    });

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $data = $request->only('nombre', 'email');

    $user = User::create([
        'name' => $data['nombre'],
        'email' => $data['email'],
        'password' => Hash::make('password123'),
        'role' => 'profesor',
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
        $validator = Validator::make($request->all(),[
            'nombre' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$profesor->user_id}",
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'clase_grupo.*.grupo' => 'nullable|integer|min:1',
        ], [
            'clase_grupo.*.grupo.integer' => 'El grupo debe ser un número válido.',
            'clase_grupo.*.grupo.min' => 'El grupo debe ser mayor a 0.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $combinaciones = [];
            foreach ($request->input('clase_grupo', []) as $index => $entry) {
                $clase = $entry['clase_id'] ?? null;
                $grupo = $entry['grupo'] ?? null;                
                if ($clase && $grupo) {
                    $clave = $clase . '-' . $grupo;
                    if (in_array($clave, $combinaciones)) {
                        $validator->errors()->add("clase_grupo.$index.grupo", "Ya se asignó este grupo a esta clase.");
                    } else {
                        $combinaciones[] = $clave;
                    }
                }
            }
        });
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

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

    public function updateProfile(Request $request)
    {
        $profesor = Auth::user()->profesor;

        if (!$profesor) {
            return back()->withErrors(['profesor' => 'No se encontró el perfil del profesor.']);
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
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
            }
            $data['foto'] = null;
        }

        $user = Auth::user();
        $user->update([
            'name' => $data['nombre'],
            'email' => $data['email'],
        ]);

        Log::info("Foto  {$data['foto']}, {$profesor->foto}");
        $profesor->update([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'foto' => $data['foto']
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}