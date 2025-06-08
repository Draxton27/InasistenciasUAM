@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400">Lista de Profesores</h2>
        <a href="{{ route('profesores.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium">
            Nuevo Profesor
        </a>
    </div>

    @foreach ($profesores as $profesor)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-md p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    @if ($profesor->foto)
                        <img src="{{ asset('storage/' . $profesor->foto) }}" alt="Foto de {{ $profesor->nombre }}"
                            class="h-12 w-12 rounded-full object-cover shadow border border-gray-300 dark:border-gray-700">
                    @else
                        <div class="h-12 w-12 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-white">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $profesor->nombre }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-300">{{ $profesor->email }}</p>

                    <h4 class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 mt-4">Clases asignadas:</h4>
                    @forelse ($profesor->clases as $clase)
                        <p class="text-sm text-gray-600 dark:text-gray-200">- {{ $clase->name }} (Grupo: {{ $clase->pivot->grupo }})</p>
                    @empty
                        <p class="text-sm text-gray-500 italic">Sin clases asignadas</p>
                    @endforelse
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('profesores.edit', $profesor->id) }}"
                       class="px-3 py-1 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded-md">
                        Editar
                    </a>

                    <form action="{{ route('profesores.destroy', $profesor->id) }}" method="POST"
                          onsubmit="return confirm('¿Estás seguro de eliminar este profesor?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded-md">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection