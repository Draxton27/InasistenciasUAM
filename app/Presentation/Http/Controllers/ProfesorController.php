<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\ProfesorService;
use App\Application\DTOs\ProfesorDTO;
use App\Infrastructure\Persistence\Eloquent\Models\Profesor as ProfesorModel;
use App\Infrastructure\Persistence\Eloquent\Models\Clase as ClaseModel;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\ProfesorStoreRequest;
use App\Presentation\Http\Requests\ProfesorUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Controller: ProfesorController
 * Capa: Presentation
 * Maneja las operaciones CRUD de profesores
 */
class ProfesorController extends Controller
{
    public function __construct(
        private ProfesorService $profesorService
    ) {}

    public function index()
    {
        // Temporal: usar modelos directamente para compatibilidad con vistas
        $profesores = ProfesorModel::with('clases')->get();
        return view('profesores.index', compact('profesores'));
    }

    public function create()
    {
        $clases = ClaseModel::orderBy('name')->get();
        return view('profesores.create', compact('clases'));
    }

    public function store(ProfesorStoreRequest $request)
    {
        $validated = $request->validated();
        $data = [
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        // Manejo de archivo
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
                return back()->withErrors(['foto' => 'El archivo de foto no es válido.'])->withInput()->with('error', 'El archivo de foto no es válido.');
            }
        }

        try {
            $dto = ProfesorDTO::fromArray([
                'nombre' => $data['nombre'],
                'email' => $data['email'],
                'password' => $data['password'],
                'foto' => $data['foto'] ?? null,
                'clase_grupo' => $validated['clase_grupo'] ?? [],
            ]);

            $this->profesorService->create($dto);

            return redirect()->route('profesores.index')->with('success', 'Profesor creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit(ProfesorModel $profesor)
    {
        $clases = ClaseModel::orderBy('name')->get();
        return view('profesores.edit', compact('profesor', 'clases'));
    }

    public function update(ProfesorUpdateRequest $request, ProfesorModel $profesor)
    {
        $validated = $request->validated();
        $data = [
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
        ];

        // Manejo de archivo
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
                return back()->withErrors(['foto' => 'El archivo de foto no es válido.'])->withInput()->with('error', 'El archivo de foto no es válido.');
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

        try {
            $dto = ProfesorDTO::fromArray([
                'id' => $profesor->id,
                'user_id' => $profesor->user_id,
                'nombre' => $data['nombre'],
                'email' => $data['email'],
                'foto' => $data['foto'] ?? $profesor->foto,
                'clase_grupo' => $validated['clase_grupo'] ?? [],
            ]);

            $this->profesorService->update($dto);

            return redirect()->route('profesores.index')->with('success', 'Profesor actualizado.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->profesorService->delete($id);
            return redirect()->route('profesores.index')->with('success', 'Profesor y su usuario eliminados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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

        // Manejo de archivo
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
                return back()->withErrors(['foto' => 'El archivo de foto no es válido.'])->withInput()->with('error', 'El archivo de foto no es válido.');
            }
        }

        if ($request->input('eliminar_foto') === "1" && $profesor->foto) {
            if (Storage::disk('public')->exists($profesor->foto)) {
                Storage::disk('public')->delete($profesor->foto);
            }
            $data['foto'] = null;
        }

        try {
            $dto = ProfesorDTO::fromArray([
                'id' => $profesor->id,
                'user_id' => $profesor->user_id,
                'nombre' => $data['nombre'],
                'email' => $data['email'],
                'foto' => $data['foto'] ?? $profesor->foto,
            ]);

            $this->profesorService->update($dto);

            return back()->with('success', 'Perfil actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->with('error', $e->getMessage());
        }
    }
}
