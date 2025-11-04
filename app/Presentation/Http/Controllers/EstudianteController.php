<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\EstudianteService;
use App\Application\DTOs\EstudianteDTO;
use App\Infrastructure\Persistence\Eloquent\Models\Estudiante;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\EstudianteUpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Controller: EstudianteController
 * Capa: Presentation
 * Maneja las operaciones del perfil de estudiantes
 */
class EstudianteController extends Controller
{
    public function __construct(
        private EstudianteService $estudianteService
    ) {}

    public function editProfile()
    {
        $estudiante = Auth::user()->load('estudiante')->estudiante;

        if (!$estudiante) {
            abort(404, 'Perfil de estudiante no encontrado.');
        }

        return view('estudiantes.edit', compact('estudiante'));
    }

    public function updateProfile(EstudianteUpdateProfileRequest $request)
    {
        $estudiante = Auth::user()->estudiante;

        if (!$estudiante) {
            return back()->withErrors(['estudiante' => 'No se encontró el perfil del estudiante.']);
        }

        $validated = $request->validated();
        $data = $validated;

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
            $this->estudianteService->deleteFoto($estudiante->id);
            $data['foto'] = null;
        }

        // Actualizar usuario
        Auth::user()->update([
            'name' => $data['nombre'],
            'email' => $data['email'],
        ]);

        // Actualizar estudiante usando el servicio
        $dto = EstudianteDTO::fromArray([
            'id' => $estudiante->id,
            'userId' => Auth::id(),
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'cif' => $data['cif'],
            'email' => $data['email'],
            'foto' => $data['foto'] ?? $estudiante->foto,
        ]);

        $this->estudianteService->update($dto);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
