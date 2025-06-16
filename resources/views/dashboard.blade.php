@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400 tracking-tight">
            Bienvenido al Sistema de Justificaciones
        </h1>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Gestiona tus ausencias y justificaciones de manera eficiente
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Tarjeta de Justificaciones -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Mis Justificaciones</h3>
                <i class="fas fa-clipboard-list text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Gestiona tus justificaciones de ausencia</p>
            <a href="{{ route('justificaciones.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">
                Ver justificaciones →
            </a>
        </div>

        <!-- Tarjeta de Clases -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Mis Clases</h3>
                <i class="fas fa-chalkboard-teacher text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Clases asignadas para este semestre</p>
            <a href="{{ route('clases.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">
                Ver clases →
            </a>
        </div>

        <!-- Tarjeta de Nueva Justificación -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Nueva Justificación</h3>
                <i class="fas fa-plus-circle text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Registra una nueva justificación de ausencia</p>
            <a href="{{ route('justificaciones.create') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">
                Crear justificación →
            </a>
        </div>
    </div>
</div>
@endsection
