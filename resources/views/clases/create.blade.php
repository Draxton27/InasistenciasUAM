@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400 mb-6">Crear Nueva Clase</h2>

    <form method="POST" action="{{ route('clases.store') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre de la Clase</label>
            <input type="text" name="name" id="name"
                class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Asignar Profesores (con grupo)</label>
            <div id="profesores-container" class="space-y-3">
                <div class="flex items-center gap-4">
                    <select name="profesor_grupo[0][profesor_id]"
                            class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                        <option value="">Selecciona un profesor</option>
                        @foreach ($profesores as $profesor)
                            <option value="{{ $profesor->id }}">{{ $profesor->nombre }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="profesor_grupo[0][grupo]" placeholder="Grupo"
                           class="w-1/2 px-3 py-2 border rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                </div>
            </div>

            <button type="button" onclick="agregarProfesorGrupo()"
                class="mt-3 px-3 py-2 bg-indigo-500 text-white rounded-md text-sm hover:bg-indigo-600">
                + Añadir otro profesor
            </button>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Guardar Clase
            </button>
        </div>
    </form>
</div>

<script>
    let profIndex = 1;
    const profesores = @json($profesores);

    function agregarProfesorGrupo() {
        const container = document.getElementById('profesores-container');

        const row = document.createElement('div');
        row.className = 'flex items-center gap-4 mt-2';

        let selectHTML = `<select name="profesor_grupo[${profIndex}][profesor_id]"
            class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
            <option value="">Selecciona un profesor</option>`;
        profesores.forEach(p => {
            selectHTML += <option value="${p.id}">${p.nombre}</option>;
        });
        selectHTML += </select>;

        row.innerHTML = `
            ${selectHTML}
            <input type="text" name="profesor_grupo[${profIndex}][grupo]" placeholder="Grupo"
                class="w-1/2 px-3 py-2 border rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-sm ml-2">
                Quitar
            </button>
        `;

        container.appendChild(row);
        profIndex++;
    }
</script>
@endsection