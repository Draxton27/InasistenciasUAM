@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight mb-6">
            Editar Profesor
        </h2>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Actualiza la información del profesor
        </p>
    </div>

    <!-- Formulario -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('profesores.update', $profesor) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div class="mb-6">
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nombre Completo
                </label>
                <input type="text" name="nombre" id="nombre"
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 
                              focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                              dark:bg-gray-700 dark:text-white"
                       value="{{ old('nombre', $profesor->nombre) }}" required>
                @error('nombre')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Correo Electrónico
                </label>
                <input type="email" name="email" id="email"
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 
                              focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                              dark:bg-gray-700 dark:text-white"
                       value="{{ old('email', $profesor->email) }}" required>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto -->
            <div class="mb-6">
                <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Foto de Perfil
                </label>
                <div class="mt-1 flex items-center">
                    <div class="relative">
                        <input type="file" name="foto" id="foto"
                               class="hidden"
                               accept="image/*">
                        <label for="foto"
                               class="cursor-pointer inline-flex items-center gap-2 px-4 py-2
                                      bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                                      rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300
                                      hover:bg-gray-50 dark:hover:bg-gray-600">
                            <i class="fas fa-upload"></i>
                            Cambiar imagen
                        </label>
                    </div>
                    <div id="preview" class="ml-4">
                        @if($profesor->foto)
                            <img src="{{ asset('storage/' . $profesor->foto) }}"
                                 alt="Foto de {{ $profesor->nombre }}"
                                 class="h-12 w-12 rounded-full object-cover">
                        @else
                            <div class="h-12 w-12 rounded-full bg-[#009CA9] flex items-center justify-center text-white">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                </div>
                @error('foto')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Clases dinámicas -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Clases Asignadas (con grupo)
                </label>
                <div id="clase-grupo-wrapper" class="space-y-4">
                    @foreach ($profesor->clases as $i => $clase)
                        <div class="flex items-center gap-4">
                            <select name="clase_grupo[{{ $i }}][clase_id]"
                                    class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                                           focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9]
                                           dark:bg-gray-700 dark:text-white text-sm" required>
                                <option value="">Selecciona una clase</option>
                                @foreach ($clases as $opcion)
                                    <option value="{{ $opcion->id }}" {{ $opcion->id == $clase->id ? 'selected' : '' }}>
                                        {{ $opcion->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="clase_grupo[{{ $i }}][grupo]" value="{{ $clase->pivot->grupo }}"
                                   placeholder="Grupo"
                                   class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                                          focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9]
                                          dark:bg-gray-700 dark:text-white text-sm">
                            <button type="button" onclick="this.closest('.flex').remove()"
                                class="ml-2 text-red-600 hover:text-red-900 text-sm font-semibold transition-colors">
                                Quitar
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="agregarGrupoClase()"
                    class="mt-4 px-4 py-2 bg-[#009CA9] hover:bg-[#007c8b] text-white rounded-xl text-sm font-medium shadow transition">
                    + Añadir otra clase
                </button>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('profesores.index') }}"
                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium
                          text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-[#009CA9] hover:bg-[#007c8b] text-white text-sm font-medium
                               rounded-xl shadow-md transition">
                    Actualizar Profesor
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview de imagen
    document.getElementById('foto').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="h-12 w-12 rounded-full object-cover">`;

            }
            reader.readAsDataURL(file);
        }
    });

    // Clases dinámicas
    let index = {{ $profesor->clases->count() }};
    function agregarGrupoClase() {
        const wrapper = document.getElementById('clase-grupo-wrapper');
        const newRow = document.createElement('div');
        newRow.className = 'flex items-center gap-4 mt-2';
        newRow.innerHTML = `
            <select name="clase_grupo[${index}][clase_id]"
                class="w-1/2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9]
                dark:bg-gray-700 dark:text-white text-sm" required>
                <option value="">Selecciona una clase</option>
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
        index++;
    }
</script>
@endpush
@endsection