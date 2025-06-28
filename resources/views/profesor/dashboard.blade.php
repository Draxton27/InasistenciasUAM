@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] dark:text-[#009CA9] tracking-tight">
            Panel del Profesor
        </h2>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Gestiona las justificaciones de tus estudiantes
        </p>
    </div>

    <!-- Lista de Justificaciones -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                Justificaciones de tus estudiantes
            </h3>
        </div>

        @forelse ($justificaciones as $j)
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white">
                                {{ $j->user->name }}
                            </h4>
                            <span class="text-xs font-semibold uppercase tracking-wide px-3 py-1 rounded-full 
                                {{ $j->estado === 'aceptada' ? 'bg-green-100 text-green-800' : 
                                   ($j->estado === 'rechazada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($j->estado) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                            {{ $j->claseProfesor->clase->name ?? 'Clase desconocida' }} 
                            (Grupo {{ $j->claseProfesor->grupo ?? '-' }})
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($j->fecha)->translatedFormat('d F, Y') }} &nbsp;|&nbsp; 
                            {{ ucfirst($j->tipo_constancia) }}
                        </p>
                    </div>
                </div>

                @if ($j->archivo)
                    <div class="mt-4">
                        <a href="{{ asset('storage/' . $j->archivo) }}" target="_blank"
                           class="text-sm text-[#009CA9] dark:text-[#009CA9] hover:underline flex items-center gap-1">
                            <i class="fas fa-paperclip"></i> Ver archivo adjunto
                        </a>
                    </div>
                @endif

                @if ($j->notas_adicionales)
                    <p class="mt-4 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $j->notas_adicionales }}
                    </p>
                @endif

                @if ($j->reprogramacion)
                    <div class="mt-4 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-[#009CA9]"></i>
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>Reprogramación:</strong>
                                {{ \Carbon\Carbon::parse($j->reprogramacion->fecha_reprogramada)->translatedFormat('d F, Y h:i A') }}
                            </span>
                        </div>
                        @if($j->reprogramacion->aula)
                        <div class="flex items-center gap-2">
                            <i class="fas fa-door-open text-[#009CA9]"></i>
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>Aula:</strong> {{ $j->reprogramacion->aula }}
                            </span>
                        </div>
                        @endif
                        <a href="{{ route('reprogramaciones.edit', $j->reprogramacion->id) }}"
                           class="inline-flex items-center justify-center px-3 py-1 bg-[#009CA9] text-white text-xs font-semibold rounded-md shadow hover:bg-[#007c8b] focus:outline-none focus:ring-2 focus:ring-[#009CA9] focus:ring-offset-2 transition sm:ml-2 w-full sm:w-auto">
                            <i class="fas fa-edit mr-1"></i> Editar
                        </a>
                    </div>
                @endif

                @if ($j->estado === 'aceptada' && !$j->reprogramacion)
                    <div class="mt-4">
                        <a href="{{ route('reprogramaciones.create', $j->id) }}"
                           class="inline-flex items-center justify-center px-4 py-2 bg-[#009CA9] text-white text-xs font-semibold rounded-md shadow hover:bg-[#007c8b] focus:outline-none focus:ring-2 focus:ring-[#009CA9] focus:ring-offset-2 transition w-full sm:w-auto">
                            <i class="fas fa-calendar-plus mr-2"></i> Agregar reprogramación
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="p-6 text-center">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">No hay justificaciones para mostrar.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
