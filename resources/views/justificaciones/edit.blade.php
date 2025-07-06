@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight">
            Editar Justificación
        </h2>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Modifica los datos de tu justificación de inasistencia
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        @include('justificaciones.form', ['profesores' => $profesores, 'clases' => $clases, 'justificacion' => $justificacion])
    </div>
</div>
@endsection 