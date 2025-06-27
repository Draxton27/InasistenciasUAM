@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] dark:text-[#009CA9] tracking-tight">
            Mis Justificaciones
        </h2>
        <a href="{{ route('justificaciones.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-[#009CA9] hover:bg-[#007c8b] text-white text-sm font-medium rounded-lg shadow-md transition">
            <i class="fas fa-plus"></i> Nueva Justificación
        </a>
    </div>

    @forelse ($justificaciones as $j)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md hover:shadow-xl p-6 mb-6 transition">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                        {{ $j->claseProfesor->clase->name ?? 'Clase eliminada' }}
                        — {{ $j->claseProfesor->profesor->nombre ?? 'Profesor eliminado' }}
                        (Grupo {{ $j->claseProfesor->grupo ?? '-' }})
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                        {{ \Carbon\Carbon::parse($j->fecha)->translatedFormat('d F, Y') }} &nbsp;|&nbsp; {{ ucfirst($j->tipo_constancia) }}
                    </p>
                </div>

                <span class="text-xs font-semibold uppercase tracking-wide px-3 py-1 rounded-full 
                    {{ $j->estado === 'aceptada' ? 'bg-green-100 text-green-800' : 
                       ($j->estado === 'rechazada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst($j->estado) }}
                </span>
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
                <div class="mt-4 flex items-center gap-4">
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
