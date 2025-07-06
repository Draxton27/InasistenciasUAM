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
        @php
            $clases = \App\Models\ClaseProfesor::with('clase')->get();
        @endphp
        @include('justificaciones.form', ['profesores' => $profesores, 'clases' => $clases])
    </div>
</div>

@push('scripts')
<script>
let count = 1;

function actualizarBotonQuitarGlobal() {
    const items = document.querySelectorAll('.justificacion-item');
    const quitarBtn = document.getElementById('quitar-justificacion');
    if (quitarBtn) {
        if (items.length > 1) {
            quitarBtn.classList.remove('hidden');
        } else {
            quitarBtn.classList.add('hidden');
        }
    }
}

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
            input.innerHTML = `<option value="">Selecciona un profesor</option>`
                + `@foreach($profesores as $profesor)`
                + `<option value=\"{{ $profesor->id }}\">{{ $profesor->nombre }}</option>`
                + `@endforeach`;
        }
    });
    container.appendChild(clone);
    count++;
    actualizarBotonQuitarGlobal();
});

document.getElementById('quitar-justificacion').addEventListener('click', () => {
    const container = document.getElementById('justificaciones-multiples');
    const items = container.querySelectorAll('.justificacion-item');
    if (items.length > 1) {
        items[items.length - 1].remove();
        actualizarBotonQuitarGlobal();
    }
});

// Llama a esta función después de cargar la página para el estado inicial
actualizarBotonQuitarGlobal();

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
