@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400 tracking-tight mb-8">
        Panel del Profesor
    </h2>

    <h3 class="text-lg font-bold mb-6 text-gray-700 dark:text-gray-100">Justificaciones de tus estudiantes</h3>

    @forelse ($justificaciones as $j)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md hover:shadow-xl p-6 mb-6 transition">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white">
                            {{ $j->user->name }} — {{ $j->claseProfesor->clase->name ?? 'Clase desconocida' }} (Grupo {{ $j->claseProfesor->grupo ?? '-' }})
                        </h4>
                    </h4>
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
                       class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        <i class="fas fa-paperclip"></i> Ver archivo adjunto
                    </a>
                </div>
            @endif

            @if ($j->notas_adicionales)
                <p class="mt-4 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    {{ $j->notas_adicionales }}
                </p>
            @endif
        </div>
    @empty
        <div class="text-center py-16 text-gray-500 dark:text-gray-400">
            <i class="fas fa-inbox text-3xl mb-4"></i>
            <p>No hay justificaciones para mostrar.</p>
        </div>
    @endforelse
</div>
@endsection
