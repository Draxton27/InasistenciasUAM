@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight">
            Editar Clase
        </h2>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Actualiza la información de la clase
        </p>
    </div>

    <!-- Formulario -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('clases.update', $clase) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nombre de la Clase
                </label>
                <input type="text" name="name" id="name" 
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 
                              focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                              dark:bg-gray-700 dark:text-white"
                       value="{{ old('name', $clase->name) }}" required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notas -->
            <div class="mb-6">
                <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Notas
                </label>
                <textarea name="note" id="note" rows="3"
                          class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 
                                 focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                                 dark:bg-gray-700 dark:text-white">{{ old('note', $clase->note) }}</textarea>
                @error('note')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profesores -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Profesores Asignados
                </label>
                <div class="space-y-4">
                    @foreach($profesores as $profesor)
                        <div class="flex items-center">
                            <input type="checkbox" name="profesores[]" value="{{ $profesor->id }}" 
                                   id="profesor_{{ $profesor->id }}"
                                   class="h-4 w-4 text-[#009CA9] focus:ring-[#009CA9] border-gray-300 rounded"
                                   {{ $clase->profesores->contains($profesor->id) ? 'checked' : '' }}>
                            <label for="profesor_{{ $profesor->id }}" 
                                   class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                {{ $profesor->nombre }}
                            </label>
                            <input type="text" name="grupos[{{ $profesor->id }}]" 
                                   placeholder="Grupo"
                                   value="{{ $clase->profesores->contains($profesor->id) ? $clase->profesores->find($profesor->id)->pivot->grupo : '' }}"
                                   class="ml-4 px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-600 
                                          focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                                          dark:bg-gray-700 dark:text-white">
                        </div>
                    @endforeach
                </div>
                @error('profesores')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('clases.index') }}" 
                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium 
                          text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-[#009CA9] hover:bg-[#007c8b] text-white text-sm font-medium 
                               rounded-xl shadow-md transition">
                    Actualizar Clase
                </button>
            </div>
        </form>
    </div>
</div>
@endsection