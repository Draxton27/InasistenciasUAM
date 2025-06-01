<?php
namespace App\Http\Controllers;

use App\Models\Profesor;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfesorController extends Controller
{
    public function index()
    {
        $profesores = Profesor::all();
        return view('profesores.index', compact('profesores'));
    }

    public function create()
    {
        return view('profesores.create');
    }

        public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $data['nombre'],
            'email' => $data['email'],
            'password' => Hash::make('password123'),
            'role' => 'profesor',
        ]);

        Profesor::create([
            'user_id' => $user->id,
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'especialidad' => $data['especialidad'],
        ]);

        return redirect()->route('profesores.index')->with('success', 'Profesor creado exitosamente.');
    }

    public function edit(Profesor $profesor)
    {
        return view('profesores.edit', compact('profesor'));
    }

    public function update(Request $request, Profesor $profesor)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:profesores,email,' . $profesor->id,
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'nullable|string|max:255',
        ]);

        $profesor->update($data);

        return redirect()->route('profesores.index')->with('success', 'Profesor actualizado.');
    }

public function destroy($id)
{
    $profesor = Profesor::find($id);

    Log::info('Destroy usando ID manual', ['profesor' => $profesor]);

    if (!$profesor) {
        return back()->with('error', 'Profesor no encontrado.');
    }

    $user = $profesor->user;
    $profesor->delete();

    if ($user) {
        $user->delete();
    }

    return redirect()->route('profesores.index')->with('success', 'Profesor eliminado.');
}

}
