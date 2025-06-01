@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400 tracking-tight">
            Profesores Registrados
        </h2>
        <a href="{{ route('profesores.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md transition">
            <i class="fas fa-plus"></i> Nuevo Profesor
        </a>
    </div>

    @forelse ($profesores as $profesor)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md p-6 mb-6 transition hover:shadow-xl">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $profesor->nombre }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">{{ $profesor->email }}</p>
                    @if ($profesor->telefono)
                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ $profesor->telefono }}</p>
                    @endif
                    @if ($profesor->especialidad)
                        <p class="text-sm text-gray-500 dark:text-gray-300">Especialidad: {{ $profesor->especialidad }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('profesores.edit', $profesor) }}"
                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        Editar
                    </a>
                    <form action="{{ route('profesores.destroy', $profesor->id) }}" method="POST"
                        onsubmit="return confirm('¿Estás seguro de que deseas eliminar este profesor?');"
                        class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm font-medium text-red-600 hover:text-red-800 transition underline">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-16 text-gray-500 dark:text-gray-400">
            <i class="fas fa-user-times text-3xl mb-4"></i>
            <p>No hay profesores registrados.</p>
        </div>
    @endforelse
</div>
@endsection
