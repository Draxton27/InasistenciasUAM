@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400 mb-6">Editar Profesor</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profesores.update', $profesor) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $profesor->nombre) }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $profesor->email) }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Clases Asignadas (con grupo)</label>
            <div id="clase-grupo-wrapper" class="space-y-3">
                @foreach ($profesor->clases as $i => $clase)
                    <div class="flex items-center gap-4">
                        <select name="clase_grupo[{{ $i }}][clase_id]"
                            class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                            @foreach ($clases as $opcion)
                                <option value="{{ $opcion->id }}" {{ $opcion->id == $clase->id ? 'selected' : '' }}>
                                    {{ $opcion->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="clase_grupo[{{ $i }}][grupo]" value="{{ $clase->pivot->grupo }}"
                            placeholder="Grupo"
                            class="w-1/2 px-3 py-2 border rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                        <button type="button" onclick="this.closest('.flex').remove()"
                            class="text-red-500 hover:text-red-700 text-sm">
                            Quitar
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="agregarGrupoClase()"
                class="mt-3 px-3 py-2 bg-indigo-500 text-white rounded-md text-sm hover:bg-indigo-600">
                + Añadir otra clase
            </button>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('profesores.index') }}"
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
    let index = {{ $profesor->clases->count() }};

    function agregarGrupoClase() {
        const wrapper = document.getElementById('clase-grupo-wrapper');

        const newRow = document.createElement('div');
        newRow.classList.add('flex', 'items-center', 'gap-4', 'mt-2');
        newRow.innerHTML = `
            <select name="clase_grupo[${index}][clase_id]"
                class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                <option value="">Selecciona una clase</option>
                @foreach ($clases as $clase)
                    <option value="{{ $clase->id }}">{{ $clase->name }}</option>
                @endforeach
            </select>
            <input type="text" name="clase_grupo[${index}][grupo]" placeholder="Grupo"
                class="w-1/2 px-3 py-2 border rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
        `;
        wrapper.appendChild(newRow);
        index++;
    }
</script>
@endsection