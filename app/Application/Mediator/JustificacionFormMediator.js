/**
 * Clase: FormMediator
 * Capa: Application
 * Patrón: Mediator (GoF)
 * 
 * Centraliza la lógica de interacción entre los campos del formulario de justificación
 * y el botón de envío, habilitando o deshabilitando el botón según el estado de los campos.
 * Reduce el acoplamiento entre elementos del formulario y mejora la mantenibilidad.
 * 
 * Participantes del patrón:
 * - Mediator: Esta clase (FormMediator)
 * - Colleagues: Los campos del formulario (inputs, selects, textareas) y botones de envío
 */
class FormMediator {
    /**
     * Constructor del FormMediator
     * @param {HTMLElement} formEl - Elemento <form> HTML que este mediador gestionará
     * @param {Object} options - Opciones de configuración
     * @param {string} options.requiredSelector - Selector CSS para identificar campos requeridos (default: '[data-mediator="required"]')
     * @param {string} options.submitSelector - Selector CSS para botones de envío (default: 'button[type="submit"]')
     * @param {Function} options.validateFn - Función de validación personalizada (field, form) => boolean
     * @param {boolean} options.fileRequiredMustExist - Si true, requiere que archivos existan para campos tipo file (default: true)
     */
    constructor(formEl, options = {}) {
        // Validación: el formulario es requerido
        if (!formEl) {
            throw new Error('Error: se debe proveer un elemento de formulario al FormMediator.');
        }

        // Almacenar referencia al formulario
        this.form = formEl;

        // Fusionar opciones por defecto con las proporcionadas
        this.opts = Object.assign({
            requiredSelector: '[data-mediator="required"]',
            submitSelector: 'button[type="submit"]',
            validateFn: null,
            fileRequiredMustExist: true
        }, options);

        // Seleccionar botones de envío
        this.submitButtons = Array.from(this.form.querySelectorAll(this.opts.submitSelector));

        // Inicializar observadores y listeners
        this.observeRequiredFields();
        this.attachDynamicListeners();
        this.update(); // Actualización inicial
    }

    /**
     * Método: fields
     * Devuelve los campos actuales que el mediador debe vigilar
     * @returns {HTMLElement[]} Array de elementos de campos requeridos
     */
    fields() {
        return Array.from(this.form.querySelectorAll(this.opts.requiredSelector));
    }

    /**
     * Método: observeRequiredFields
     * Agrega listeners a los campos requeridos para detectar cambios
     */
    observeRequiredFields() {
        // Bind del método update para usarlo como event handler
        this._onChange = this.update.bind(this);
        
        // Agregar listeners a cada campo requerido
        this.fields().forEach(f => this.attachFieldListeners(f));
    }

    /**
     * Método: attachFieldListeners
     * Agrega listeners a un campo específico, evitando duplicados
     * @param {HTMLElement} field - Campo al que se le agregarán los listeners
     */
    attachFieldListeners(field) {
        // Evitar múltiples listeners - remover primero
        field.removeEventListener('input', this._onChange);
        field.removeEventListener('change', this._onChange);

        // "input" para la mayoría de campos, "change" para selects y checkboxes/radios
        field.addEventListener('input', this._onChange);
        field.addEventListener('change', this._onChange);

        // Para campos de tipo file, también escuchar 'blur' si es necesario
        // (se puede extender según necesidad)
    }

    /**
     * Método: attachDynamicListeners
     * Configura un MutationObserver para detectar cambios dinámicos en el DOM
     * (campos agregados/removidos) y reconfigurar los listeners
     */
    attachDynamicListeners() {
        // Si el DOM cambia (campos agregados/removidos), rebind listeners
        // MutationObserver para observar nuevos/removidos nodos en el formulario
        const mo = new MutationObserver(mutations => {
            let rebind = false;
            for (const m of mutations) {
                if (m.addedNodes.length || m.removedNodes.length) {
                    rebind = true;
                    break;
                }
            }

            if (rebind) {
                this.observeRequiredFields();
                this.update();
            }
        });

        // Observar cambios en el formulario (hijos directos y subárbol)
        mo.observe(this.form, { 
            childList: true, 
            subtree: true 
        });

        // Guardar referencia para poder desconectarlo después
        this._mutationObserver = mo;
    }

    /**
     * Método: update
     * Método principal para actualizar el estado del botón de envío
     * Valida todos los campos y habilita/deshabilita el botón según corresponda
     */
    update() {
        // Validar todos los campos - todos deben ser válidos
        const allOk = this.fields().every(field => this.isFieldValid(field));
        
        // Actualizar estado del botón de envío
        this.setSubmitEnabled(allOk);
    }

    /**
     * Método: isFieldValid
     * Valida un campo individual según sus reglas específicas
     * @param {HTMLElement} field - Campo a validar
     * @returns {boolean} true si el campo es válido, false en caso contrario
     */
    isFieldValid(field) {
        // Validación personalizada (tiene prioridad)
        if (typeof this.opts.validateFn === 'function') {
            try {
                const custom = this.opts.validateFn(field, this.form);
                if (typeof custom === 'boolean') {
                    return custom;
                }
            } catch (e) {
                // Ignorar excepciones de validación personalizada
            }
        }

        // Validación para elementos SELECT
        if (field.tagName === 'SELECT') {
            return field.value !== '' && field.value !== null;
        }

        // Validación para campos de tipo FILE
        const type = (field.type || '').toLowerCase();
        if (type === 'file') {
            if (this.opts.fileRequiredMustExist) {
                return field.files && field.files.length > 0;
            }
            return true;
        }

        // Validación genérica: verificar si el campo tiene el atributo required
        // y si tiene valor (o si no es required, siempre válido)
        if (field.hasAttribute('required')) {
            // Para textareas, inputs, etc.
            if (field.tagName === 'TEXTAREA' || field.tagName === 'INPUT') {
                return field.value.trim() !== '';
            }
            // Fallback: usar la validación nativa del navegador
            return field.checkValidity();
        }

        // Si no es requerido, es válido por defecto
        return true;
    }

    /**
     * Método: setSubmitEnabled
     * Habilita o deshabilita los botones de envío y actualiza su estado visual
     * @param {boolean} enabled - true para habilitar, false para deshabilitar
     */
    setSubmitEnabled(enabled) {
        this.submitButtons.forEach(btn => {
            // Actualizar estado disabled
            btn.disabled = !enabled;
            
            // Actualizar atributo de accesibilidad
            btn.setAttribute('aria-disabled', (!enabled).toString());

            // Actualizar clases CSS para feedback visual
            if (enabled) {
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    }

    /**
     * Método: destroy
     * Limpia listeners y observers para evitar fugas de memoria
     * Debe llamarse cuando el formulario ya no está en uso
     */
    destroy() {
        // Desconectar MutationObserver
        if (this._mutationObserver) {
            this._mutationObserver.disconnect();
            this._mutationObserver = null;
        }

        // Remover listeners de todos los campos
        this.fields().forEach(f => {
            f.removeEventListener('input', this._onChange);
            f.removeEventListener('change', this._onChange);
        });

        // Limpiar referencia al handler
        this._onChange = null;
    }
}

/**
 * Inicialización automática de mediadores en formularios
 * Busca todos los formularios con el atributo data-mediator="true"
 * y crea una instancia de FormMediator para cada uno
 */
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar mediadores en formularios con data-mediator="true"
    const forms = document.querySelectorAll('form[data-mediator="true"]');

    forms.forEach(form => {
        // Parsear opciones desde el atributo data-mediator-options (JSON)
        let opts = {};
        try {
            const raw = form.getAttribute('data-mediator-options');
            if (raw) {
                opts = JSON.parse(raw);
            }
        } catch (e) {
            // Ignorar errores de parsing JSON
        }

        // Crear instancia del mediador y almacenarla en el elemento form
        form._mediator = new FormMediator(form, opts);
    });
});