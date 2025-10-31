// Global confirmation handler using SweetAlert2
// Attach to forms with class="js-confirm" or elements with [data-confirm]

document.addEventListener('DOMContentLoaded', () => {
  // Intercept any form with class .js-confirm
  document.querySelectorAll('form.js-confirm').forEach((form) => {
    form.addEventListener('submit', async (e) => {
      if (form.dataset.confirmed === 'true') return;
      e.preventDefault();
      const message = form.getAttribute('data-confirm') || '¿Estás seguro de continuar? Esta acción no se puede deshacer.';
      const confirmButtonText = form.getAttribute('data-confirm-text') || 'Sí, continuar';
      const cancelButtonText = form.getAttribute('data-cancel-text') || 'Cancelar';

      const result = await window.Swal.fire({
        title: 'Confirmación',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb', // Tailwind blue-600
        cancelButtonColor: '#6b7280', // Tailwind gray-500
        confirmButtonText,
        cancelButtonText,
        focusCancel: true,
      });

      if (result.isConfirmed) {
        form.dataset.confirmed = 'true';
        form.submit();
      }
    });
  });

  // Click elements with [data-confirm] to confirm an action
  document.querySelectorAll('[data-confirm]:not(form)').forEach((el) => {
    el.addEventListener('click', async (e) => {
      const message = el.getAttribute('data-confirm') || '¿Estás seguro de continuar?';
      const href = el.getAttribute('href');
      const method = el.getAttribute('data-method');

      const result = await window.Swal.fire({
        title: 'Confirmación',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: el.getAttribute('data-confirm-text') || 'Sí, continuar',
        cancelButtonText: el.getAttribute('data-cancel-text') || 'Cancelar',
        focusCancel: true,
      });

      if (!result.isConfirmed) return;

      // If it's a link with href, navigate
      if (href && !method) {
        window.location.href = href;
        return;
      }

      // If a method is provided, submit via hidden form
      if (href && method) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = href;

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrf;
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = method.toUpperCase();
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
      }
    });
  });
});
