@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400">Clases Registradas</h2>
        <a href="{{ route('clases.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium">
            Nueva Clase
        </a>
    </div>

    @foreach ($clases as $clase)
        <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow mb-4 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $clase->name }}</h3>

                    <div class="mt-2">
                        <h4 class="text-sm font-medium text-indigo-600 dark:text-indigo-300">Profesores asignados:</h4>
                        @forelse($clase->profesores as $profesor)
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                - {{ $profesor->nombre }} (Grupo: {{ $profesor->pivot->grupo }})
                            </p>
                        @empty
                            <p class="text-sm text-gray-500 italic">Sin asignaciones</p>
                        @endforelse
                    </div>
                </div>

                <div class="flex flex-col items-end gap-2 ml-4">
                    <a href="{{ route('clases.edit', $clase) }}"
                       class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">Editar</a>

                    <form action="{{ route('clases.destroy', $clase) }}" method="POST"
                          onsubmit="return confirm('¿Estás seguro de eliminar esta clase?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm text-red-600 hover:text-red-800 font-medium">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection