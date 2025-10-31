@php
    $types = [
        'success' => session('success'),
        'error' => session('error'),
        'warning' => session('warning'),
        'info' => session('info') ?? session('status'),
    ];
@endphp

@if(collect($types)->filter()->isNotEmpty() || $errors->any())
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const messages = @json(array_filter($types));
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Object.entries(messages).forEach(([type, text]) => {
                    const map = { success: 'success', error: 'error', warning: 'warning', info: 'info' };
                    Toast.fire({ icon: map[type] ?? 'info', title: text });
                });

                // Validation errors modal (if any)
                const hasErrors = @json($errors->any());
                if (hasErrors) {
                    const errorList = @json($errors->all());
                    Swal.fire({
                        title: 'Revisa los campos',
                        html: `<ul class="text-left list-disc ml-6">${errorList.map(e => `<li>${e}</li>`).join('')}</ul>`,
                        icon: 'error',
                        confirmButtonText: 'Entendido',
                    });
                }
            });
        </script>
    @endpush
@endif
