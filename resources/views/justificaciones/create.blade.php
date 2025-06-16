@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight">
            Nueva Justificación
        </h2>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Completa el formulario para registrar tu justificación de inasistencia
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('justificaciones.store') }}" method="POST" enctype="multipart/form-data" class="p-6" novalidate>
            @csrf

            <div id="justificaciones-multiples" class="space-y-6">
                <div class="justificacion-item bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                    <!-- Profesor y Clase -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Profesor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Profesor
                            </label>
                            <select name="justificaciones[0][profesor_id]" 
                                    class="profesor-select w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm" 
                                    required>
                                <option value="">Selecciona un profesor</option>
                                @foreach($profesores as $profesor)
                                    <option value="{{ $profesor->id }}">{{ $profesor->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="text-error text-sm mt-1 text-red-600 dark:text-red-400"></div>
                        </div>

                        <!-- Clase -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Clase Afectada
                            </label>
                            <select name="justificaciones[0][clase_profesor_id]" 
                                    class="clase-select w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm" 
                                    required>
                                <option value="">Selecciona una clase</option>
                            </select>
                            <div class="text-error text-sm mt-1 text-red-600 dark:text-red-400"></div>
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fecha de la Ausencia
                        </label>
                        <input type="date" 
                               name="justificaciones[0][fecha]" 
                               required 
                               max="{{ date('Y-m-d') }}" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm" />
                        <div class="text-error text-sm mt-1 text-red-600 dark:text-red-400"></div>
                    </div>
                </div>
            </div>

            <!-- Botón Agregar Justificación -->
            <div class="flex justify-center mb-6">
                <button type="button" 
                        id="agregar-justificacion" 
                        class="inline-flex items-center px-4 py-2 bg-[#009CA9] bg-opacity-10 text-[#009CA9] border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-opacity-20 focus:bg-opacity-20 active:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-[#009CA9] focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    Añadir Otra Justificación
                </button>
            </div>

            <!-- Tipo de Constancia y Archivo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Tipo de Constancia -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tipo de Constancia
                    </label>
                    <select name="tipo_constancia" 
                            required 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm">
                        <option value="">Selecciona una opción</option>
                        <option value="trabajo">Trabajo</option>
                        <option value="enfermedad">Enfermedad</option>
                        <option value="otro">Otro</option>
                    </select>
                    <div class="text-error text-sm mt-1 text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Archivo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Archivo Adjunto
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-700 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="archivo" class="relative cursor-pointer rounded-md font-medium text-[#009CA9] hover:text-[#007c8b] focus-within:outline-none">
                                    <span>Sube un archivo</span>
                                    <input id="archivo" name="archivo" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                </label>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                PDF, JPG, PNG hasta 10MB
                            </p>
                        </div>
                    </div>
                    <div class="text-error text-sm mt-1 text-red-600 dark:text-red-400"></div>
                </div>
            </div>

            <!-- Notas Adicionales -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Notas Adicionales
                </label>
                <textarea name="notas_adicionales" 
                          rows="4" 
                          maxlength="500"
                          class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm resize-none"
                          placeholder="Puedes agregar detalles adicionales..."></textarea>
                <div class="flex justify-between items-center mt-1">
                    <div class="text-error text-sm text-red-600 dark:text-red-400"></div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span id="notas-count">0</span>/500 caracteres
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('justificaciones.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-300 dark:active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition ease-in-out duration-150">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-[#009CA9] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#007c8b] focus:bg-[#007c8b] active:bg-[#007c8b] focus:outline-none focus:ring-2 focus:ring-[#009CA9] focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Enviar Justificación
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let count = 1;

document.getElementById('agregar-justificacion').addEventListener('click', () => {
    const container = document.getElementById('justificaciones-multiples');
    const original = container.querySelector('.justificacion-item');
    const clone = original.cloneNode(true);

    // Limpiar el estado de validación y los mensajes de error
    clone.querySelectorAll('select, input').forEach(input => {
        const oldName = input.name;
        if (!oldName) return;
        const newName = oldName.replace(/\[\d+\]/, `[${count}]`);
        input.name = newName;
        input.value = '';
        
        // Remover clases de validación
        input.classList.remove('is-valid', 'is-invalid');
        input.setCustomValidity('');
        
        // Limpiar mensajes de error
        const errorElement = input.nextElementSibling;
        if (errorElement && errorElement.classList.contains('text-error')) {
            errorElement.textContent = '';
        }

        if (input.classList.contains('clase-select')) {
            input.innerHTML = '<option value="">Selecciona una clase</option>';
        }

        if (input.classList.contains('profesor-select')) {
            input.innerHTML = `<option value="">Selecciona un profesor</option>
                @foreach($profesores as $profesor)
                    <option value="{{ $profesor->id }}">{{ $profesor->nombre }}</option>
                @endforeach`;
        }
    });

    container.appendChild(clone);
    count++;
});

document.addEventListener('change', function (e) {
    if (e.target.classList.contains('profesor-select')) {
        const select = e.target;
        const profesorId = select.value;
        const claseSelect = select.closest('.justificacion-item').querySelector('.clase-select');

        fetch(`/api/profesor/${profesorId}/clases`)
            .then(res => res.json())
            .then(data => {
                claseSelect.innerHTML = '<option value="">Selecciona una clase</option>';
                data.forEach(c => {
                    claseSelect.innerHTML += `<option value="${c.id}">${c.nombre} (Grupo ${c.grupo})</option>`;
                });
            })
            .catch(error => {
                console.error('Error al cargar clases:', error);
            });
    }
});

// Contador de caracteres para notas adicionales
const notasTextarea = document.querySelector('textarea[name="notas_adicionales"]');
const notasCount = document.getElementById('notas-count');

notasTextarea.addEventListener('input', function() {
    const length = this.value.length;
    notasCount.textContent = length;
    if (length > 500) {
        this.value = this.value.substring(0, 500);
        notasCount.textContent = 500;
    }
});

// Mostrar nombre del archivo seleccionado
const archivoInput = document.getElementById('archivo');
archivoInput.addEventListener('change', function() {
    const fileName = this.files[0]?.name;
    if (fileName) {
        const label = this.parentElement.querySelector('span');
        label.textContent = fileName;
    }
});
</script>
@endpush
@endsection
