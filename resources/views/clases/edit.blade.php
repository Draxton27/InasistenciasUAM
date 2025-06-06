@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400 mb-6">Editar Clase</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('clases.update', $clase) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name', $clase->name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profesores asignados (con grupo)</label>
            <div id="profesor-grupo-container" class="space-y-3">
                @foreach ($clase->profesores as $i => $prof)
                    <div class="flex items-center gap-4">
                        <select name="profesor_grupo[{{ $i }}][profesor_id]"
                            class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                            <option value="">Selecciona un profesor</option>
                            @foreach ($profesores as $p)
                                <option value="{{ $p->id }}" {{ $prof->id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="profesor_grupo[{{ $i }}][grupo]" placeholder="Grupo"
                            value="{{ $prof->pivot->grupo }}"
                            class="w-1/2 px-3 py-2 border rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                        <button type="button" onclick="this.parentElement.remove()"
                            class="text-red-500 hover:text-red-700 text-sm">Quitar</button>
                    </div>
                @endforeach
            </div>

            <button type="button" onclick="agregarProfesorGrupo()"
                class="mt-3 px-3 py-2 bg-indigo-500 text-white rounded-md text-sm hover:bg-indigo-600">
                + Añadir otro profesor
            </button>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('clases.index') }}"
                class="inline-block px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                Cancelar
            </a>
            <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md">
                Guardar cambios
            </button>
        </div>
    </form>
</div>

<script>
    let index = {{ count($clase->profesores) }};
    const profesores = @json($profesores);

    function agregarProfesorGrupo() {
        const container = document.getElementById('profesor-grupo-container');

        const row = document.createElement('div');
        row.className = 'flex items-center gap-4 mt-2';

        let selectHTML = `<select name="profesor_grupo[${index}][profesor_id]"
            class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
            <option value="">Selecciona un profesor</option>`;
        profesores.forEach(p => {
            selectHTML += <option value="${p.id}">${p.nombre}</option>;
        });
        selectHTML += </select>;

        row.innerHTML = `
            ${selectHTML}
            <input type="text" name="profesor_grupo[${index}][grupo]" placeholder="Grupo"
                class="w-1/2 px-3 py-2 border rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
            <button type="button" onclick="this.parentElement.remove()"
                class="text-red-500 hover:text-red-700 text-sm">Quitar</button>
        `;

        container.appendChild(row);
        index++;
    }
</script>
@endsection