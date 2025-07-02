@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight mb-6">
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

    <form action="{{ route('profesores.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre completo</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                    class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 
                    focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                    dark:bg-gray-700 dark:text-white shadow-sm sm:text-sm" required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 
                    focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                    dark:bg-gray-700 dark:text-white shadow-sm sm:text-sm" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contraseña</label>
                <input type="password" name="password" id="password" 
                    class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 
                    focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                    dark:bg-gray-700 dark:text-white shadow-sm sm:text-sm" required>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 
                    focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                    dark:bg-gray-700 dark:text-white shadow-sm sm:text-sm" required>
            </div>

            <div class="mb-4 col-span-2">
                <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto</label>
                <input type="file" name="foto" id="foto"
                    class="block w-full text-sm text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-100
                        file:text-indigo-700 hover:file:bg-indigo-200" />
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Asignar clases (con grupo)</label>
            <div id="clase-grupo-wrapper" class="space-y-3">
                <div class="flex items-center gap-4">
                    <select name="clase_grupo[0][clase_id]"
                    class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                    focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                    dark:bg-gray-700 dark:text-white text-sm">
                    <option value="">-- Seleccione una clase (opcional) --</option>
                    @foreach ($clases as $clase)
                    <option value="{{ $clase->id }}">{{ $clase->name }}</option>
                    @endforeach
                </select>

                    <input type="text" name="clase_grupo[0][grupo]" placeholder="Grupo"
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
            <button type="button" onclick="agregarGrupoClase()"
                class="mt-4 px-4 py-2 bg-[#009CA9] hover:bg-[#007c8b] text-white rounded-xl text-sm font-medium shadow transition">
                + Añadir otra clase
            </button>
        </div>

        <div class="mt-8 flex justify-end gap-4">
            <a href="{{ route('profesores.index') }}"
                class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium 
                    text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                Cancelar
            </a>
            <button type="submit"
                class="px-6 py-2 bg-[#009CA9] hover:bg-[#007c8b] text-white text-sm font-medium 
                    rounded-xl shadow-md transition">
                Guardar profesor
            </button>
        </div>
    </form>
</div>

<script>
    let index = 1;

    function agregarGrupoClase() {
        const wrapper = document.getElementById('clase-grupo-wrapper');
        const newRow = document.createElement('div');
        newRow.className = 'flex items-center gap-4 mt-2';

        newRow.innerHTML = `
            <select name="clase_grupo[${index}][clase_id]"
            class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
            focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
            dark:bg-gray-700 dark:text-white text-sm">
            <option value="">-- Seleccione una clase (opcional) --</option>
            @foreach ($clases as $clase)
            <option value="{{ $clase->id }}">{{ $clase->name }}</option>
            @endforeach
            </select>
            <input type="text" name="clase_grupo[${index}][grupo]" placeholder="Grupo"
                class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                dark:bg-gray-700 dark:text-white text-sm">
            <button type="button"
                onclick="this.parentElement.remove()"
                class="ml-2 text-red-600 hover:text-red-900 text-sm font-semibold transition-colors">
                Quitar
            </button>
        `;
        wrapper.appendChild(newRow);

        // Mostrar los botones "Quitar" en todas las filas excepto la primera
        const rows = wrapper.querySelectorAll('.flex.items-center');
        rows.forEach(row => {
            const btn = row.querySelector('button[type="button"]');
            btn.classList.remove('hidden');
        });

        index++;
    }
</script>
@endsection