@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-6 sm:px-8 lg:px-10 bg-gradient-to-br from-indigo-50 to-white dark:from-gray-800 dark:to-gray-900 shadow-2xl rounded-3xl border border-indigo-100 dark:border-gray-700">
    <h2 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400 mb-8 flex items-center gap-2">
        <i class="fas fa-clipboard-check text-indigo-500 dark:text-indigo-300 text-2xl"></i>
        Justificación de Inasistencia
    </h2>

    <form method="POST" action="{{ route('justificaciones.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <div>
            <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Docente</label>
            <select id="profesor_select" class="w-full px-5 py-3 rounded-lg border border-indigo-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-400 transition">
                <option disabled selected>Selecciona un docente</option>
                @foreach($profesores as $profesor)
                    <option value="{{ $profesor->id }}">{{ $profesor->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Clase Afectada</label>
            <select name="clase_profesor_id" id="clase_profesor_select" required class="w-full px-5 py-3 rounded-lg border border-indigo-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-400 transition">
                <option disabled selected>Selecciona una clase</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Fecha de la Ausencia</label>
            <input type="date" name="fecha" required
                   class="w-full px-5 py-3 rounded-lg border border-indigo-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-400 transition" />
        </div>

        <div>
            <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Tipo de Constancia</label>
            <select name="tipo_constancia" required
                    class="w-full px-5 py-3 rounded-lg border border-indigo-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-400 transition">
                <option disabled selected>Selecciona una opción</option>
                <option value="trabajo">Trabajo</option>
                <option value="enfermedad">Enfermedad</option>
                <option value="otro">Otro</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Notas Adicionales</label>
            <textarea name="notas_adicionales" rows="4"
                      class="w-full px-5 py-3 rounded-lg border border-indigo-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-indigo-300 dark:placeholder-gray-500 shadow-sm focus:ring-2 focus:ring-indigo-400 transition resize-none" placeholder="Puedes agregar detalles adicionales..."></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-2">Archivo Adjunto</label>
            <input type="file" name="archivo"
                   class="block w-full text-sm text-indigo-600 dark:text-indigo-400 file:mr-4 file:py-2 file:px-5 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200" />
        </div>

        <div class="text-right">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-700 hover:from-indigo-600 hover:to-indigo-800 text-white font-bold text-sm rounded-xl shadow-lg transition-all">
                <i class="fas fa-paper-plane"></i> Enviar Justificación
            </button>
        </div>
    </form>
</div>

<script>
const profesorSelect = document.getElementById('profesor_select');
const claseSelect = document.getElementById('clase_profesor_select');

profesorSelect.addEventListener('change', function () {
    const profesorId = this.value;

    fetch(`/api/profesor/${profesorId}/clases`)
        .then(res => res.json())
        .then(data => {
            claseSelect.innerHTML = '<option disabled selected>Selecciona una clase</option>';
            data.forEach(c => {
                claseSelect.innerHTML += `<option value="${c.id}">${c.nombre} (Grupo ${c.grupo})</option>`;
            });
        })
        .catch(error => {
            console.error('Error al cargar clases:', error);
        });
});
</script>

@endsection