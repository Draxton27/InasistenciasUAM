@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight">
            Rechazar Justificación
        </h2>
        <a href="{{ route('admin.dashboard') }}" class="text-[#009CA9] hover:underline">&larr; Volver al panel</a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Información de la Justificación -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            Información de la Justificación
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Estudiante
                </label>
                <p class="text-gray-900 dark:text-white">{{ $justificacion->user->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $justificacion->user->email }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Clase
                </label>
                <p class="text-gray-900 dark:text-white">{{ $justificacion->claseProfesor->clase->name ?? 'No especificada' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Grupo {{ $justificacion->claseProfesor->grupo ?? 'No especificado' }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Fecha de Inasistencia
                </label>
                <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($justificacion->fecha)->translatedFormat('d F, Y') }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Estado Actual
                </label>
                @if($justificacion->estado === 'aceptada')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        <i class="fas fa-check mr-1"></i> Aceptada
                    </span>
                @elseif($justificacion->estado === 'rechazada')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                        <i class="fas fa-times mr-1"></i> Rechazada
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        <i class="fas fa-clock mr-1"></i> Pendiente
                    </span>
                @endif
            </div>
        </div>

        @if($justificacion->notas_adicionales)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Notas Adicionales del Estudiante
                </label>
                <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                    {{ $justificacion->notas_adicionales }}
                </p>
            </div>
        @endif

        @if($justificacion->archivo)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Archivo Adjunto
                </label>
                     <a href="{{ route('justificaciones.file', $justificacion->id) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-3 py-2 text-sm text-[#009CA9] bg-[#009CA9]/10 rounded-lg hover:bg-[#009CA9]/20 transition-colors">
                    <i class="fas fa-paperclip"></i>
                    <span>Ver archivo adjunto</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Formulario de Rechazo -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            Motivo del Rechazo
        </h3>
        
    <form action="{{ route('admin.justificaciones.reject', $justificacion->id) }}" method="POST" class="js-confirm" data-confirm="¿Rechazar esta justificación? Se registrará el comentario y se notificará al estudiante." data-confirm-text="Sí, rechazar" data-cancel-text="Cancelar">
            @csrf
            @method('PATCH')
            
            <div class="mb-6">
                <label for="comentario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Comentario de Rechazo <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="comentario"
                    name="comentario"
                    rows="6"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#009CA9] focus:border-[#009CA9] dark:bg-gray-700 dark:text-white"
                    placeholder="Explica detalladamente el motivo del rechazo de esta justificación..."
                    required>{{ old('comentario') }}</textarea>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Este comentario será registrado como parte del rechazo y será visible para el estudiante.
                </p>
            </div>
            
            <div class="flex gap-4 justify-end">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-6 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Rechazar Justificación
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 