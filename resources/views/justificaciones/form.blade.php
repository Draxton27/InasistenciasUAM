@php
    $isEdit = isset($justificacion);
@endphp
<form action="{{ $isEdit ? route('justificaciones.update', $justificacion->id) : route('justificaciones.store') }}" method="POST" enctype="multipart/form-data" class="p-6" novalidate>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif
    <div id="justificaciones-multiples" class="space-y-6">
        <div class="justificacion-item bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Profesor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Profesor
                    </label>
                    <select name="justificaciones[0][profesor_id]" class="profesor-select w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm" required>
                        <option value="">Selecciona un profesor</option>
                        @foreach($profesores as $profesor)
                            <option value="{{ $profesor->id }}" {{ ($isEdit && $justificacion->claseProfesor->profesor_id == $profesor->id) ? 'selected' : '' }}>{{ $profesor->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Clase -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Clase Afectada
                    </label>
                    <select name="justificaciones[0][clase_profesor_id]" class="clase-select w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm" required>
                        <option value="">Selecciona una clase</option>
                        @foreach($clases as $clase)
                            <option value="{{ $clase->id }}" {{ ($isEdit && $justificacion->clase_profesor_id == $clase->id) ? 'selected' : '' }}>{{ $clase->clase->name }} (Grupo {{ $clase->grupo }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- Fecha -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Fecha de la Ausencia
                </label>
                <input type="date" name="justificaciones[0][fecha]" required max="{{ date('Y-m-d') }}" value="{{ $isEdit ? $justificacion->fecha : '' }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm" />
            </div>
        </div>
    </div>
    <!-- Botón Agregar y Quitar Justificación fuera del bloque de justificación (solo en create) -->
    @unless($isEdit)
    <div class="flex flex-col sm:flex-row justify-center mb-6 gap-2 sm:gap-4 w-full">
        <button type="button" 
                id="agregar-justificacion" 
                class="w-full sm:w-auto inline-flex items-center px-4 py-2 bg-[#009CA9] bg-opacity-10 text-[#009CA9] border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-opacity-20 focus:bg-opacity-20 active:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-[#009CA9] focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-plus mr-2"></i>
            Añadir Otra Justificación
        </button>
        <button type="button"
                id="quitar-justificacion"
                class="w-full sm:w-auto hidden inline-flex items-center px-4 py-2 bg-red-100 text-red-700 border border-red-300 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-red-200 focus:bg-red-200 active:bg-red-300 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition ease-in-out duration-150"
                title="Quitar última justificación">
            <i class="fas fa-times mr-2"></i>
            Quitar Justificación
        </button>
    </div>
    @endunless
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Tipo de Constancia -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tipo de Constancia
            </label>
            <select name="tipo_constancia" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm">
                <option value="">Selecciona una opción</option>
                <option value="trabajo" {{ ($isEdit && $justificacion->tipo_constancia == 'trabajo') ? 'selected' : '' }}>Trabajo</option>
                <option value="enfermedad" {{ ($isEdit && $justificacion->tipo_constancia == 'enfermedad') ? 'selected' : '' }}>Enfermedad</option>
                <option value="otro" {{ ($isEdit && $justificacion->tipo_constancia == 'otro') ? 'selected' : '' }}>Otro</option>
            </select>
        </div>
        <!-- Archivo -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Archivo Adjunto
            </label>
            @if($isEdit && $justificacion->archivo)
                <div class="mb-2">
                    <a href="{{ asset('storage/' . $justificacion->archivo) }}" target="_blank" class="text-xs text-[#009CA9] hover:underline">Ver archivo actual</a>
                </div>
            @endif
            <input id="archivo" name="archivo" type="file" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 dark:text-gray-400" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PDF, JPG, PNG hasta 10MB</p>
        </div>
    </div>
    <!-- Notas Adicionales -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Notas Adicionales
        </label>
        <textarea name="notas_adicionales" rows="4" maxlength="500" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm resize-none" placeholder="Puedes agregar detalles adicionales...">{{ $isEdit ? $justificacion->notas_adicionales : '' }}</textarea>
        <div class="flex justify-between items-center mt-1">
            <div class="text-error text-sm text-red-600 dark:text-red-400"></div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <span id="notas-count">{{ $isEdit ? strlen($justificacion->notas_adicionales) : 0 }}</span>/500 caracteres
            </div>
        </div>
    </div>
    <div class="flex flex-col sm:flex-row justify-center mb-6 gap-2 sm:gap-4 w-full mt-4">
        <a href="{{ route('justificaciones.index') }}"
           class="w-full sm:w-auto inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-300 dark:active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition ease-in-out duration-150 justify-center">
            Cancelar
        </a>
        <button type="submit"
                class="w-full sm:w-auto inline-flex items-center px-4 py-2 bg-[#009CA9] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#007c8b] focus:bg-[#007c8b] active:bg-[#007c8b] focus:outline-none focus:ring-2 focus:ring-[#009CA9] focus:ring-offset-2 transition ease-in-out duration-150 justify-center">
            <i class="fas fa-save mr-2"></i>
            {{ $isEdit ? 'Guardar Cambios' : 'Enviar Justificación' }}
        </button>
    </div>
</form> 