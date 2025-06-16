@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight">
            Nuevo Profesor
        </h2>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Completa el formulario para registrar un nuevo profesor
        </p>
    </div>

    <!-- Formulario -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('profesores.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nombre -->
            <div class="mb-6">
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nombre Completo
                </label>
                <input type="text" name="nombre" id="nombre" 
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 
                              focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                              dark:bg-gray-700 dark:text-white"
                       value="{{ old('nombre') }}" required>
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
                       value="{{ old('email') }}" required>
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
                            Seleccionar imagen
                        </label>
                    </div>
                    <div id="preview" class="ml-4 hidden">
                        <img src="" alt="Preview" class="h-12 w-12 rounded-full object-cover">
                    </div>
                </div>
                @error('foto')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Clases -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Clases Asignadas
                </label>
                <div class="space-y-4">
                    @foreach($clases as $clase)
                        <div class="flex items-center">
                            <input type="checkbox" name="clases[]" value="{{ $clase->id }}" 
                                   id="clase_{{ $clase->id }}"
                                   class="h-4 w-4 text-[#009CA9] focus:ring-[#009CA9] border-gray-300 rounded">
                            <label for="clase_{{ $clase->id }}" 
                                   class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                {{ $clase->name }}
                            </label>
                            <input type="text" name="grupos[{{ $clase->id }}]" 
                                   placeholder="Grupo"
                                   class="ml-4 px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-600 
                                          focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] 
                                          dark:bg-gray-700 dark:text-white">
                        </div>
                    @endforeach
                </div>
                @error('clases')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                    Guardar Profesor
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
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection