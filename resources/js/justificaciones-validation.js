document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');

    // Función para mostrar mensajes de error
    function showError(input, message) {
        const errorDiv = input.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('error-message')) {
            errorDiv.textContent = message;
        } else {
            const div = document.createElement('div');
            div.className = 'error-message text-red-500 text-sm mt-1';
            div.textContent = message;
            input.parentNode.insertBefore(div, input.nextSibling);
        }
        input.classList.add('border-red-500');
    }

    // Función para limpiar mensajes de error
    function clearError(input) {
        const errorDiv = input.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('error-message')) {
            errorDiv.remove();
        }
        input.classList.remove('border-red-500');
    }

    // Validar campos requeridos
    function validateRequired(input) {
        if (!input.value.trim()) {
            showError(input, 'Este campo es requerido');
            return false;
        }
        clearError(input);
        return true;
    }

    // Validar fecha
    function validateDate(input) {
        const date = new Date(input.value);
        const today = new Date();
        
        if (date > today) {
            showError(input, 'La fecha no puede ser futura');
            return false;
        }
        clearError(input);
        return true;
    }

    // Validar archivo
    function validateFile(input) {
        if (input.files.length > 0) {
            const file = input.files[0];
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!allowedTypes.includes(file.type)) {
                showError(input, 'Solo se permiten archivos PDF, JPEG y PNG');
                return false;
            }

            if (file.size > maxSize) {
                showError(input, 'El archivo no debe superar los 5MB');
                return false;
            }
        }
        clearError(input);
        return true;
    }

    // Validar notas adicionales
    function validateNotes(input) {
        if (input.value.length > 500) {
            showError(input, 'Las notas no deben exceder los 500 caracteres');
            return false;
        }
        clearError(input);
        return true;
    }

    // Validar todo el formulario
    function validateForm() {
        let isValid = true;
        const justificaciones = document.querySelectorAll('.justificacion-item');

        justificaciones.forEach((justificacion, index) => {
            const profesorSelect = justificacion.querySelector('.profesor-select');
            const claseSelect = justificacion.querySelector('.clase-select');
            const fechaInput = justificacion.querySelector('input[type="date"]');

            if (!validateRequired(profesorSelect)) isValid = false;
            if (!validateRequired(claseSelect)) isValid = false;
            if (!validateRequired(fechaInput) || !validateDate(fechaInput)) isValid = false;
        });

        const tipoConstancia = form.querySelector('select[name="tipo_constancia"]');
        const notasAdicionales = form.querySelector('textarea[name="notas_adicionales"]');
        const archivo = form.querySelector('input[type="file"]');

        if (!validateRequired(tipoConstancia)) isValid = false;
        if (notasAdicionales.value && !validateNotes(notasAdicionales)) isValid = false;
        if (archivo.files.length > 0 && !validateFile(archivo)) isValid = false;

        return isValid;
    }

    // Agregar validación al enviar el formulario
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });

    // Agregar validación en tiempo real
    form.querySelectorAll('select, input, textarea').forEach(input => {
        input.addEventListener('blur', function() {
            if (input.type === 'date') {
                validateRequired(input);
                validateDate(input);
            } else if (input.type === 'file') {
                validateFile(input);
            } else if (input.name === 'notas_adicionales') {
                validateNotes(input);
            } else {
                validateRequired(input);
            }
        });
    });
}); 