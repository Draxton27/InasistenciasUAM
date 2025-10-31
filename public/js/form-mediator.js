class FormMediator {
   
    constructor(formEl, options = {}) {
        if (!formEl) throw new Error('Error: se debe proveer un elemento de formulario al FormMediator.');

        this.form = formEl;
        this.opts = Object.assign({
            requiredSelector: '[data-mediator="required"]',
            submitSelector: 'button[type="submit"]',
            validateFn: null,
            fileRequiredMustExist: true
        }, options);

        this.submitButtons = Array.from(this.form.querySelectorAll(this.opts.submitSelector));
        this.observeRequiredFields();
        this.attachDynamicListeners();
        this.update(); // inicial
    }

    // devuelve los campos actuales que el mediator debe vigilar
    fields() {
        return Array.from(this.form.querySelectorAll(this.opts.requiredSelector));
    }

    // agrega listeners a los campos requeridos
    observeRequiredFields() {
        this._onChange = this.update.bind(this);
        this.fields().forEach(f => this.attachFieldListeners(f));
    }

    attachFieldListeners(field) {
        // evitar múltiples listeners
        field.removeEventListener('input', this._onChange);
        field.removeEventListener('change', this._onChange);

        // "input" para la mayoría, "change" para selects y checkboxes/radios
        field.addEventListener('input', this._onChange);
        field.addEventListener('change', this._onChange);

        // para campos de tipo file, también escuchar 'blur' si es necesario
    }

    // If DOM changes (fields added/removed), rebind listeners
    attachDynamicListeners() {
        // MutationObserver to watch for new/remove nodes in the form
        const mo = new MutationObserver(mutations => {
            let rebind = false;
            for (const m of mutations) {
                if (m.addedNodes.length || m.removedNodes.length) { rebind = true; break; }
            }
            if (rebind) {
                this.observeRequiredFields();
                this.update();
            }
        });
        mo.observe(this.form, { childList: true, subtree: true });
        this._mutationObserver = mo;
    }

    // metodo principal para actualizar el estado del submit
    update() {
        const allOk = this.fields().every(field => this.isFieldValid(field));
        this.setSubmitEnabled(allOk);
    }

    isFieldValid(field) {
        // si se provee validateFn
        if (typeof this.opts.validateFn === 'function') {
            try {
                const custom = this.opts.validateFn(field, this.form);
                if (typeof custom === 'boolean') return custom;
            } catch (e) { /* ignore custom exceptions */ }
        }

        // Validación por tipo:
        if (field.tagName === 'SELECT') {
            return field.value !== '' && field.value !== null;
        }

        const type = (field.type || '').toLowerCase();

        if (type === 'file') {
            if (this.opts.fileRequiredMustExist) {
                return field.files && field.files.length > 0;
            }
            return true;
        }

        // checkbox/radio: si está marcado
        if (type === 'checkbox' || type === 'radio') {
            // si hay varios con mismo name, al menos uno checked
            const name = field.name;
            if (name) {
                const group = Array.from(this.form.querySelectorAll(`[name="${CSS.escape(name)}"]`));
                return group.some(g => g.checked);
            }
            return !!field.checked;
        }

        // text inputs y otros: no vacío
        const val = (field.value || '').toString().trim();
        return val.length > 0;
    }

    setSubmitEnabled(enabled) {
        this.submitButtons.forEach(btn => {
            btn.disabled = !enabled;
            btn.setAttribute('aria-disabled', (!enabled).toString());
            if (enabled) {
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    }

    // para limpiar listeners y observers si es necesario
    destroy() {
        this._mutationObserver.disconnect();
        this.fields().forEach(f => {
            f.removeEventListener('input', this._onChange);
            f.removeEventListener('change', this._onChange);
        });
    }
}

// Inicializar mediadores en formularios con data-mediator="true"
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form[data-mediator="true"]');
    forms.forEach(form => {
        // permitir pasar opciones a través de JSON en data-mediator-options (opcional)
        let opts = {};
        try {
            const raw = form.getAttribute('data-mediator-options');
            if (raw) opts = JSON.parse(raw);
        } catch (e) { /* ignore */ }
        // almacenar la instancia del mediador en el elemento del formulario para acceso futuro
        form._mediator = new FormMediator(form, opts);
    });
});
