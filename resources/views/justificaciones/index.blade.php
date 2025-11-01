@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 px-2 sm:px-4 lg:px-8">
    <!-- Botones de acción como crear y filtrar -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#009CA9] dark:text-[#009CA9] tracking-tight">
            Mis Justificaciones
        </h2>
        
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <!-- Selector como filtro -->
        <form method="GET" action="{{ route('justificaciones.index') }}" class="flex items-center gap-2">
        <label for="estado" class="text-sm text-gray-700 dark:text-gray-300">Filtrar por estado:</label>
        <select name="estado" id="estado" onchange="this.form.submit()"
        class="block sm:w-auto mt-1 sm:mt-0 px-3 py-2 pr-10 bg-white border border-gray-300 dark:border-gray-600 
        rounded-md shadow-sm focus:outline-none focus:ring-[#009CA9] focus:border-[#009CA9] text-sm dark:bg-gray-800 dark:text-white">
            <option value="">Todos</option>
            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="aceptada" {{ request('estado') == 'aceptada' ? 'selected' : '' }}>Aceptada</option>
            <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
        </select>
        </form>

        <!-- Boton de nueva justificación -->
        <a href="{{ route('justificaciones.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-[#009CA9] hover:bg-[#007c8b] text-white text-sm font-medium rounded-lg shadow-md transition w-full sm:w-auto justify-center">
            <i class="fas fa-plus"></i> Nueva Justificación
        </a>
        </div>
    </div>

    @forelse ($justificaciones as $j)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md hover:shadow-xl p-4 sm:p-6 mb-6 transition flex flex-col gap-3" data-justificacion-id="{{ $j->id }}">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-2 md:gap-4">
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-white truncate">
                        {{ $j->claseProfesor->clase->name ?? 'Clase eliminada' }}
                        — {{ $j->claseProfesor->profesor->nombre ?? 'Profesor eliminado' }}
                        (Grupo {{ $j->claseProfesor->grupo ?? '-' }})
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-300 mt-1">
                        {{ \Carbon\Carbon::parse($j->fecha)->translatedFormat('d F, Y') }} &nbsp;|&nbsp; {{ ucfirst($j->tipo_constancia) }}
                    </p>
                </div>
                <span class="mt-2 md:mt-0 text-xs font-semibold uppercase tracking-wide px-3 py-1 rounded-full 
                    {{ $j->estado === 'aceptada' ? 'bg-green-100 text-green-800' : 
                       ($j->estado === 'rechazada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}" data-justificacion-status>
                    {{ ucfirst($j->estado) }}
                </span>
            </div>

            @if ($j->archivo)
                <div class="mt-2 sm:mt-4">
                        <a href="{{ route('justificaciones.file', $j->id) }}" target="_blank"
                       class="text-xs sm:text-sm text-[#009CA9] dark:text-[#009CA9] hover:underline flex items-center gap-1">
                        <i class="fas fa-paperclip"></i> Ver archivo adjunto
                    </a>
                </div>
            @endif

            @if ($j->notas_adicionales)
                <p class="mt-2 sm:mt-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    {{ $j->notas_adicionales }}
                </p>
            @endif

            @if ($j->reprogramacion)
                <div class="mt-2 sm:mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-[#009CA9]"></i>
                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                            <strong>{{ \Carbon\Carbon::parse($j->reprogramacion->fecha_reprogramada)->isPast() ? 'Reprogramado' : 'Reprogramación' }}:</strong>
                            {{ \Carbon\Carbon::parse($j->reprogramacion->fecha_reprogramada)->translatedFormat('d F, Y h:i A') }}
                            @if(\Carbon\Carbon::parse($j->reprogramacion->fecha_reprogramada)->isPast())
                                <i class="fas fa-check-circle text-green-500 ml-1" title="Ya ocurrió"></i>
                            @endif
                        </span>
                    </div>
                    @if($j->reprogramacion->aula)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-door-open text-[#009CA9]"></i>
                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                            <strong>Aula:</strong> {{ $j->reprogramacion->aula }}
                        </span>
                    </div>
                    @endif
                </div>
            @endif

            <div class="mt-2 sm:mt-4 p-3 sm:p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg {{ ($j->estado === 'rechazada' || $j->rechazo) ? '' : 'hidden' }}" data-justificacion-rechazo>
                    <div class="flex flex-col sm:flex-row items-start gap-2 sm:gap-3">
                        <i class="fas fa-times-circle text-red-500"></i>
                        <div class="flex-1">
                            <h4 class="text-xs sm:text-sm font-medium text-red-800 dark:text-red-200 mb-1 sm:mb-2">
                                Justificación Rechazada
                            </h4>
                            <p class="text-xs sm:text-sm text-red-700 dark:text-red-300 mb-2 sm:mb-3" data-justificacion-rechazo-text>
                                {{ $j->rechazo->comentario ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>

            @if ($j->estado === 'pendiente')
                <div class="flex flex-col sm:flex-row gap-2 mt-2">
                    <a href="{{ route('justificaciones.edit', $j->id) }}"
                       class="w-full sm:w-auto px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 transition-colors text-center font-semibold">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                    <form action="{{ route('justificaciones.destroy', $j->id) }}" method="POST" 
                          class="w-full sm:w-auto js-confirm" data-confirm="¿Estás seguro de que quieres eliminar esta justificación? Esta acción no se puede deshacer." data-confirm-text="Sí, eliminar" data-cancel-text="Cancelar">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full sm:w-auto px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors text-center">
                            <i class="fas fa-trash mr-1"></i> Eliminar
                        </button>
                    </form>
                </div>
            @endif
        </div>
    @empty
        <div class="text-center py-16 text-gray-500 dark:text-gray-400">
            <i class="fas fa-inbox text-3xl mb-4"></i>
            <p>No tienes justificaciones registradas.</p>
        </div>
    @endforelse
</div>
@endsection
