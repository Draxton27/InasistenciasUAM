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

    <form method="POST" action="{{ route('profesores.update', $profesor) }}" enctype="multipart/form-data">
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
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto de perfil</label>

            @if ($profesor->foto)
            <input type="hidden" name="eliminar_foto" id="eliminar_foto" value="0">
                <div id="foto-preview" class="relative inline-block mb-2">
                <img src="{{ asset('storage/' . $profesor->foto) }}" alt="Foto actual"
                    class="h-20 w-20 object-cover rounded-md border border-gray-300 dark:border-gray-600">
                <button type="button" onclick="eliminarFoto()"
                        class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-700">
                    &times;
                </button>
                </div>
            @endif

            <div id="upload-foto" style="{{ $profesor->foto ? 'display: none;' : '' }}">
                <input type="file" name="foto"
                    class="block mt-1 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md
                            file:border-0 file:text-sm file:font-semibold
                            file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200" />
            </div>
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
<script>
    function eliminarFoto() {
    document.getElementById('foto-preview').remove();
    document.getElementById('eliminar_foto').value = 1;

    const uploadFoto = document.getElementById('upload-foto');
    uploadFoto.style.display = 'block';
}

</script>

@endsection