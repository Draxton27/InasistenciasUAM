<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JustificacionController;
use App\Http\Controllers\ProfesorController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfesorDashboardController;
use App\Http\Controllers\ClaseController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/justificaciones', [JustificacionController::class, 'index'])->name('justificaciones.index');
    Route::get('/justificaciones/create', [JustificacionController::class, 'create'])->name('justificaciones.create');
    Route::post('/justificaciones', [JustificacionController::class, 'store'])->name('justificaciones.store');
});

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::resource('profesores', ProfesorController::class)->parameters([
    'profesores' => 'profesor'
]);
    Route::resource('clases', ClaseController::class);
});

Route::middleware(['auth', 'profesor'])->group(function () {
    Route::get('/profesor/dashboard', [ProfesorDashboardController::class, 'index'])->name('profesor.dashboard');
    Route::get('/api/profesor/{id}/clases', function ($id) {
    $profesor = \App\Models\Profesor::with(['clases'])->findOrFail($id);

    return $profesor->clases->map(function ($clase) use ($profesor) {
        $pivot = \App\Models\ClaseProfesor::where('clase_id', $clase->id)
                    ->where('profesor_id', $profesor->id)->first();

        return [
            'id' => $pivot->id,
            'nombre' => $clase->name,
            'grupo' => $pivot->grupo,
        ];
    });
});

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::patch('admin/justificaciones/{id}/aprobar', [AdminController::class, 'aprobar'])->name('admin.justificaciones.aprobar');
    Route::patch('admin/justificaciones/{id}/rechazar', [AdminController::class, 'rechazar'])->name('admin.justificaciones.rechazar');
});

Route::middleware(['auth', 'profesor'])->group(function () {
    Route::get('/profesor/dashboard', [ProfesorDashboardController::class, 'index'])->name('profesor.dashboard');
});
require __DIR__.'/auth.php';