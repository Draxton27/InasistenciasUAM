<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JustificacionController;
use App\Http\Controllers\ProfesorController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfesorDashboardController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\Auth\LoginRedirectController;
use App\Http\Controllers\ReprogramacionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/redirect', [LoginRedirectController::class, 'redirect'])
    ->middleware(['auth'])
    ->name('redirect');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/perfil/estudiante', [EstudianteController::class, 'editProfile'])->name('estudiante.profile.edit');
    Route::put('/perfil/estudiante', [EstudianteController::class, 'updateProfile'])->name('estudiante.profile.update');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/justificaciones', [JustificacionController::class, 'index'])->name('justificaciones.index');
    Route::get('/justificaciones/create', [JustificacionController::class, 'create'])->name('justificaciones.create');
    Route::post('/justificaciones', [JustificacionController::class, 'store'])->name('justificaciones.store');
    Route::delete('/justificaciones/{justificacion}', [JustificacionController::class, 'destroy'])->name('justificaciones.destroy');
    Route::post('/justificaciones/{justificacion}/destroy-and-create', [JustificacionController::class, 'destroyAndCreate'])->name('justificaciones.destroy-and-create');
});

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::resource('profesores', ProfesorController::class)->parameters([
    'profesores' => 'profesor'
]);
    Route::resource('clases', ClaseController::class);
});

use App\Models\ClaseProfesor;

Route::get('/api/profesor/{id}/clases', function ($id) {
    $clases = ClaseProfesor::with('clase')
        ->where('profesor_id', $id)
        ->get()
        ->map(function ($registro) {
            return [
                'id' => $registro->id,
                'nombre' => $registro->clase->name,
                'grupo' => $registro->grupo,
            ];
        });

    return response()->json($clases);
});


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::patch('admin/justificaciones/{id}/aprobar', [AdminController::class, 'aprobar'])->name('admin.justificaciones.aprobar');
    Route::get('admin/justificaciones/{id}/rechazar', [AdminController::class, 'showRechazar'])->name('admin.justificaciones.show-rechazar');
    Route::patch('admin/justificaciones/{id}/rechazar', [AdminController::class, 'rechazar'])->name('admin.justificaciones.rechazar');
});

Route::middleware(['auth', 'profesor'])->group(function () {
    Route::get('/profesor/dashboard', [ProfesorDashboardController::class, 'index'])->name('profesor.dashboard');
    Route::put('/perfil/profesor', [ProfesorController::class, 'updateProfile'])->name('profesor.profile.update');
    Route::get('/reprogramaciones/create/{justificacion}', [ReprogramacionController::class, 'create'])->name('reprogramaciones.create');
    Route::post('/reprogramaciones', [ReprogramacionController::class, 'store'])->name('reprogramaciones.store');
    Route::get('/reprogramaciones/{reprogramacion}/edit', [\App\Http\Controllers\ReprogramacionController::class, 'edit'])->name('reprogramaciones.edit');
    Route::put('/reprogramaciones/{reprogramacion}', [\App\Http\Controllers\ReprogramacionController::class, 'update'])->name('reprogramaciones.update');
});
require __DIR__.'/auth.php';