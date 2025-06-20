@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado y botón -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight">
                Profesores
            </h2>
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
                Gestiona los profesores del sistema
            </p>
        </div>
        <a href="{{ route('profesores.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-[#009CA9] hover:bg-[#007c8b] text-white text-sm font-medium rounded-xl shadow-md transition w-full sm:w-auto text-center justify-center">
            <i class="fas fa-plus"></i> Nuevo Profesor
        </a>
    </div>

    <!-- Lista de Profesores -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Clases
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($profesores as $profesor)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-[#009CA9] flex items-center justify-center text-white">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $profesor->nombre }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $profesor->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $profesor->clases->count() }} clases
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('profesores.edit', $profesor) }}" 
                                       class="text-[#009CA9] hover:text-[#007c8b]">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('profesores.destroy', $profesor) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('¿Estás seguro de eliminar este profesor?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg">No hay profesores registrados</p>
                                    <a href="{{ route('profesores.create') }}" 
                                       class="mt-4 inline-flex items-center gap-2 text-[#009CA9] hover:text-[#007c8b]">
                                        <i class="fas fa-plus"></i>
                                        Agregar profesor
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection