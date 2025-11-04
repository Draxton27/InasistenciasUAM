<?php

namespace App\Presentation\Http\Controllers\Auth;

use App\Presentation\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Infrastructure\Persistence\Eloquent\Models\Estudiante;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => [
            'required',
            'email',
            'unique:users,email',
            function ($attribute, $value, $fail) {
                if (!str_ends_with($value, '@uamv.edu.ni')) {
                    $fail('El correo debe ser institucional (@uamv.edu.ni).');
                }
            },
        ],
            'password' => 'required|string|min:8|confirmed',
            'cif' => 'required|string|max:20|unique:estudiantes,cif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::create([
            'name' => $data['nombre'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'alumno',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            if ($file->isValid()) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $destination = storage_path('app/public/estudiantes');

                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $file->move($destination, $filename);
                $data['foto'] = 'estudiantes/' . $filename;
            } else {
                return back()->withErrors(['foto' => 'El archivo de foto no es válido.'])->withInput();
            }
        }

        Estudiante::create([
            'user_id' => $user->id,
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'cif' => $data['cif'],
            'email' => $data['email'],
            'foto' => $data['foto'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('justificaciones.index');
    }
}
