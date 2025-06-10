@php
    $profesor = Auth::user()->load('profesor')->profesor;
@endphp

@if ($profesor)
    <form method="POST" action="{{ route('profesor.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', $profesor->nombre) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="email" name="email" value="{{ old('email', $profesor->email) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto de perfil</label>

            @if ($profesor->foto)
                <input type="hidden" name="eliminar_foto" id="eliminar_foto" value="0">
                <div id="foto-preview" class="relative inline-block mb-2">
                    <img src="{{ asset('storage/' . $profesor->foto) }}" alt="Foto actual"
                         class="h-20 w-20 object-cover rounded-md border border-gray-300 dark:border-gray-600">
                    <button type="button" onclick="eliminarFoto()"
                            class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-700">
                        &times;
                    </button>
                </div>
            @endif

            <div id="upload-foto" style="{{ $profesor->foto ? 'display: none;' : '' }}">
                <input type="file" name="foto"
                       class="block mt-1 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md
                              file:border-0 file:text-sm file:font-semibold
                              file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200" />
            </div>
        </div>

        <div class="mt-6">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md">
                Guardar cambios
            </button>
        </div>
    </form>

    <script>
        function eliminarFoto() {
            const preview = document.getElementById('foto-preview');
            if (preview) preview.remove();

            document.getElementById('eliminar_foto').value = 1;
            document.getElementById('upload-foto').style.display = 'block';
        }
    </script>
@else
    <p class="text-sm text-gray-500">No se encontró un registro de profesor vinculado a este usuario.</p>
@endif
