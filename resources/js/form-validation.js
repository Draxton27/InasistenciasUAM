document.addEventListener('DOMContentLoaded', function() {
    // Función para mostrar mensajes de error
    function showError(input, message) {
        const errorDiv = input.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('error-message')) {
            errorDiv.textContent = message;
        } else {
            const div = document.createElement('div');
            div.className = 'error-message text-red-500 text-sm mt-1 flex items-center gap-1';
            div.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            input.parentNode.insertBefore(div, input.nextSibling);
        }
        input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
    }

    // Función para limpiar mensajes de error
    function clearError(input) {
        const errorDiv = input.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('error-message')) {
            errorDiv.remove();
        }
        input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
    }

    // Función para mostrar mensaje de éxito
    function showSuccess(input) {
        const successDiv = input.nextElementSibling;
        if (successDiv && successDiv.classList.contains('success-message')) {
            successDiv.remove();
        }
    }

    // Validar campos requeridos
    function validateRequired(input) {
        if (!input.value.trim()) {
            showError(input, 'Este campo es obligatorio');
            return false;
        }
        clearError(input);
        showSuccess(input);
        return true;
    }

    // Validar email
    function validateEmail(input) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (input.value && !emailRegex.test(input.value)) {
            showError(input, 'Por favor, ingresa un correo electrónico válido');
            return false;
        }
        clearError(input);
        if (input.value) showSuccess(input);
        return true;
    }

    // Validar correo institucional
    function validateInstitutionalEmail(input) {
    const institutionalDomain = '@uamv.edu.ni';
    if (!input.value.endsWith(institutionalDomain)) {
        showError(input, 'Debes ingresar tu correo institucional (@uamv.edu.ni)');
        return false;
    }
    return true;
    }

    // Validar fecha
    function validateDate(input) {
        if (input.value) {
            const date = new Date(input.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (date > today) {
                showError(input, 'La fecha no puede ser futura');
                return false;
            }
        }
        clearError(input);
        if (input.value) showSuccess(input);
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
        if (input.files.length > 0) showSuccess(input);
        return true;
    }

    // Validar longitud de texto
    function validateLength(input, min, max) {
        const length = input.value.length;
        if (min && length < min) {
            showError(input, `El campo debe tener al menos ${min} caracteres`);
            return false;
        }
        if (max && length > max) {
            showError(input, `El campo no debe exceder ${max} caracteres`);
            return false;
        }
        clearError(input);
        if (input.value) showSuccess(input);
        return true;
    }

    // Validar número
    function validateNumber(input, min, max) {
        const value = parseFloat(input.value);
        if (input.value && isNaN(value)) {
            showError(input, 'Por favor, ingresa un número válido');
            return false;
        }
        if (min !== undefined && value < min) {
            showError(input, `El valor mínimo permitido es ${min}`);
            return false;
        }
        if (max !== undefined && value > max) {
            showError(input, `El valor máximo permitido es ${max}`);
            return false;
        }
        clearError(input);
        if (input.value) showSuccess(input);
        return true;
    }

    // Validar selección
    function validateSelect(input) {
        if (input.value === '') {
            showError(input, 'Por favor, selecciona una opción');
            return false;
        }
        clearError(input);
        showSuccess(input);
        return true;
    }

    // Validar todo el formulario
    function validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input, select, textarea');

        // Remover mensaje de error general anterior si existe
        const previousError = form.querySelector('.form-error-message');
        if (previousError) {
            previousError.remove();
        }

        inputs.forEach(input => {
            if (input.hasAttribute('required')) {
                if (input.tagName === 'SELECT') {
                    if (!validateSelect(input)) isValid = false;
                } else if (!validateRequired(input)) {
                    isValid = false;
                }
            }

            if (input.type === 'email' && !validateEmail(input)) {
                isValid = false;
            }else if (!validateInstitutionalEmail(input)) {
                isValid = false;
            }

            if (input.type === 'date' && !validateDate(input)) {
                isValid = false;
            }

            if (input.type === 'file' && !validateFile(input)) {
                isValid = false;
            }

            if (input.hasAttribute('minlength') || input.hasAttribute('maxlength')) {
                const min = input.getAttribute('minlength');
                const max = input.getAttribute('maxlength');
                if (!validateLength(input, min, max)) {
                    isValid = false;
                }
            }

            if (input.type === 'number') {
                const min = input.getAttribute('min');
                const max = input.getAttribute('max');
                if (!validateNumber(input, min, max)) {
                    isValid = false;
                }
            }
        });

        if (!isValid) {
            // Mostrar mensaje general de error
            const errorMessage = document.createElement('div');
            errorMessage.className = 'form-error-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
            errorMessage.innerHTML = `
                <strong class="font-bold">¡Error!</strong>
                <span class="block sm:inline"> Por favor, corrige los errores en el formulario antes de continuar.</span>
            `;
            form.insertBefore(errorMessage, form.firstChild);

            // Hacer scroll al primer error
            const firstError = form.querySelector('.error-message');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        return isValid;
    }

    // Agregar validación a todos los formularios
    document.querySelectorAll('form').forEach(form => {
        // Validación al enviar el formulario
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });

        // Validación en tiempo real
        form.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required')) {
                    if (this.tagName === 'SELECT') {
                        validateSelect(this);
                    } else {
                        validateRequired(this);
                    }
                }
                if (this.type === 'email') {
                    validateEmail(this);
                }
                if (this.type === 'date') {
                    validateDate(this);
                }
                if (this.type === 'file') {
                    validateFile(this);
                }
                if (this.hasAttribute('minlength') || this.hasAttribute('maxlength')) {
                    const min = this.getAttribute('minlength');
                    const max = this.getAttribute('maxlength');
                    validateLength(this, min, max);
                }
                if (this.type === 'number') {
                    const min = this.getAttribute('min');
                    const max = this.getAttribute('max');
                    validateNumber(this, min, max);
                }
            });

            document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            if (!form) return;

           form.addEventListener('submit', function (event) {
           const isValid = validateForm(form);
           if (!isValid) {
            event.preventDefault(); 
            }
           });
            });


            // Limpiar mensajes al enfocar
            input.addEventListener('focus', function() {
                clearError(this);
                const successDiv = this.nextElementSibling;
                if (successDiv && successDiv.classList.contains('success-message')) {
                    successDiv.remove();
                }
            });
        });
    });
}); 