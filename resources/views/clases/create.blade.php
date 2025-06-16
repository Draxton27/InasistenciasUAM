@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight">
            Nueva Clase
        </h2>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Completa el formulario para registrar una nueva clase
        </p>
    </div>

    <!-- Formulario -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('clases.store') }}" method="POST">
            @csrf

            <!-- Nombre -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nombre de la Clase
                </label>
                <input type="text" name="name" id="name"
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600
                              focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9]
                              dark:bg-gray-700 dark:text-white"
                       value="{{ old('name') }}" required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notas (opcional) -->
            <div class="mb-6">
                <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Notas
                </label>
                <textarea name="note" id="note" rows="3"
                          class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600
                                 focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9]
                                 dark:bg-gray-700 dark:text-white">{{ old('note') }}</textarea>
                @error('note')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profesores dinámicos -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Profesores Asignados (con grupo)
                </label>
                <div id="profesores-container" class="space-y-4">
                    <div class="flex items-center gap-4">
                        <select name="profesor_grupo[0][profesor_id]"
                                class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                                       focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9]
                                       dark:bg-gray-700 dark:text-white text-sm" required>
                            <option value="">Selecciona un profesor</option>
                            @foreach ($profesores as $profesor)
                                <option value="{{ $profesor->id }}">{{ $profesor->nombre }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="profesor_grupo[0][grupo]" placeholder="Grupo"
                               class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                               focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9]
                               dark:bg-gray-700 dark:text-white text-sm">
                        <button type="button"
                                onclick="this.parentElement.remove()"
                                class="ml-2 text-red-600 hover:text-red-900 text-sm font-semibold transition-colors hidden">
                            Quitar
                        </button>
                    </div>
                </div>

                <button type="button" id="addProfesorBtn"
                    class="mt-4 px-4 py-2 bg-[#009CA9] hover:bg-[#007c8b] text-white rounded-xl text-sm font-medium shadow transition">
                    + Añadir otro profesor
                </button>
                @error('profesor_grupo')
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
                    Guardar Clase
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let profIndex = 1;
    const profesores = @json($profesores);

    document.getElementById('addProfesorBtn').addEventListener('click', function() {
        const container = document.getElementById('profesores-container');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-4 mt-2';

        let selectHTML = `<select name="profesor_grupo[${profIndex}][profesor_id]"
            class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                   focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9]
                   dark:bg-gray-700 dark:text-white text-sm" required>
            <option value="">Selecciona un profesor</option>`;
        profesores.forEach(p => {
            selectHTML += `<option value="${p.id}">${p.nombre}</option>`;
        });
        selectHTML += `</select>`;


        row.innerHTML = `
            ${selectHTML}
            <input type="text" name="profesor_grupo[${profIndex}][grupo]" placeholder="Grupo"
                class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9]
                dark:bg-gray-700 dark:text-white text-sm">
            <button type="button" onclick="this.parentElement.remove()"
                class="ml-2 text-red-600 hover:text-red-900 text-sm font-semibold transition-colors">
                Quitar
            </button>
        `;

        container.appendChild(row);

        // Muestra los botones "Quitar" en todas menos la primera
        const rows = container.querySelectorAll('.flex.items-center');
        rows.forEach(row => {
            const btn = row.querySelector('button[type="button"]');
            btn.classList.remove('hidden');
        });

        profIndex++;
    });
</script>
@endpush
@endsection