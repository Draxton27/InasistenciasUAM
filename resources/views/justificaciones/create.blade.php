@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-6 sm:px-8 lg:px-10 bg-gradient-to-br from-indigo-50 to-white dark:from-gray-800 dark:to-gray-900 shadow-2xl rounded-3xl border border-indigo-100 dark:border-gray-700">
    <h2 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400 mb-8 flex items-center gap-2">
        <i class="fas fa-clipboard-check text-indigo-500 dark:text-indigo-300 text-2xl"></i>
        Justificación de Inasistencia
    </h2>

    <form method="POST" action="{{ route('justificaciones.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <div id="justificaciones-multiples">
            <div class="justificacion-item border-b border-gray-200 pb-4 mb-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Docente</label>
                    <select name="justificaciones[0][profesor_id]" class="profesor-select w-full px-5 py-3 rounded-lg border border-indigo-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-400 transition">
                        <option disabled selected>Selecciona un docente</option>
                        @foreach($profesores as $profesor)
                            <option value="{{ $profesor->id }}">{{ $profesor->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Clase Afectada</label>
                    <select name="justificaciones[0][clase_profesor_id]" class="clase-select w-full px-5 py-3 rounded-lg border border-indigo-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-400 transition" required>
                        <option disabled selected>Selecciona una clase</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Fecha de la Ausencia</label>
                    <input type="date" name="justificaciones[0][fecha]" required class="w-full px-5 py-3 rounded-lg border border-indigo-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-400 transition" />
                </div>
            </div>
        </div>

        <div class="mb-4">
            <button type="button" id="agregar-justificacion" class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg shadow">
                + Añadir Otra Justificación
            </button>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Tipo de Constancia</label>
            <select name="tipo_constancia" required class="w-full px-5 py-3 rounded-lg border border-indigo-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-400 transition">
                <option disabled selected>Selecciona una opción</option>
                <option value="trabajo">Trabajo</option>
                <option value="enfermedad">Enfermedad</option>
                <option value="otro">Otro</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Notas Adicionales</label>
            <textarea name="notas_adicionales" rows="4"
                      class="w-full px-5 py-3 rounded-lg border border-indigo-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-indigo-300 dark:placeholder-gray-500 shadow-sm focus:ring-2 focus:ring-indigo-400 transition resize-none" placeholder="Puedes agregar detalles adicionales..."></textarea>
        </div>

        <div class="mb-6">
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
let count = 1;

document.getElementById('agregar-justificacion').addEventListener('click', () => {
    const container = document.getElementById('justificaciones-multiples');
    const original = container.querySelector('.justificacion-item');
    const clone = original.cloneNode(true);

    clone.querySelectorAll('select, input').forEach(input => {
        const oldName = input.name;
        if (!oldName) return;
        const newName = oldName.replace(/\[\d+\]/, `[${count}]`);
        input.name = newName;
        input.value = '';

        if (input.classList.contains('clase-select')) {
            input.innerHTML = '<option disabled selected>Selecciona una clase</option>';
        }

        if (input.classList.contains('profesor-select')) {
            input.innerHTML = `<option disabled selected>Selecciona un docente</option>
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
                claseSelect.innerHTML = '<option disabled selected>Selecciona una clase</option>';
                data.forEach(c => {
                    claseSelect.innerHTML += `<option value="${c.id}">${c.nombre} (Grupo ${c.grupo})</option>`;
                });
            })
            .catch(error => {
                console.error('Error al cargar clases:', error);
            });
    }
});
</script>
@endsection
