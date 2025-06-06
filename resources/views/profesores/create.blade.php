@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-400 tracking-tight mb-6">
        Registrar nuevo profesor
    </h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profesores.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre completo</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>

        <div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Asignar clases (con grupo)</label>
    <div id="clase-grupo-wrapper" class="space-y-3">
        <div class="flex items-center gap-4">
            <select name="clase_grupo[0][clase_id]"
                    class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                <option value="">Selecciona una clase</option>
                @foreach ($clases as $clase)
                    <option value="{{ $clase->id }}">{{ $clase->name }}</option>
                @endforeach
            </select>
            <input type="text" name="clase_grupo[0][grupo]" placeholder="Grupo"
                   class="w-1/2 px-3 py-2 border rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
                   <!-- <div class="mt-4">
        <button type="button" onclick="addClaseGrupo()"
            class="inline-flex items-center px-3 py-2 bg-indigo-500 text-white rounded-md text-sm font-medium hover:bg-indigo-600">
            + Añadir clase
        </button>
    </div> -->
        </div>
    </div>

    <button type="button" onclick="agregarGrupoClase()"
            class="mt-3 px-3 py-2 bg-indigo-500 text-white rounded-md text-sm hover:bg-indigo-600">
        + Añadir otra clase
    </button>
</div>


        <div class="mt-8 flex justify-end">
            <a href="{{ route('profesores.index') }}"
                class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                Cancelar
            </a>
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 transition">
                Guardar profesor
            </button>
        </div>
    </form>
</div>

<script>
    function toggleGrupoInput(checkbox) {
        const input = checkbox.closest('div').querySelector('input[type="text"]');
        input.disabled = !checkbox.checked;
    }
</script>
<script>
    let index = 1;

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
        <button type="button"
                onclick="this.parentElement.remove()"
                class="text-red-500 hover:text-red-700 text-sm ml-2">
            Quitar
        </button>
    `;
    wrapper.appendChild(newRow);
    index++;
}

</script>

<script>
    const clases = @json($clases);

    function addClaseGrupo() {
        const container = document.getElementById('clases-container');
        const index = container.children.length;

        const wrapper = document.createElement('div');
        wrapper.className = "flex items-center gap-4";

        let select = `<select name="clases[]" class="w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm">
            <option value="" disabled selected>Selecciona una clase</option>`;
        clases.forEach(c => {
            select += <option value="${c.id}">${c.name}</option>;
        });
        select += </select>;

        wrapper.innerHTML = `
            ${select}
            <input type="text" name="grupos_temp[]" placeholder="Grupo"
                class="w-1/2 px-3 py-2 border rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm" />
            <button type="button" onclick="this.closest('.flex').remove()"
                class="text-red-500 hover:text-red-700 text-sm">Quitar</button>
        `;

        container.appendChild(wrapper);
    }
</script>

@endsection