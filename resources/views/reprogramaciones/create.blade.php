@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight">
            Nueva Reprogramación
        </h2>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Asigna una fecha de reprogramación para la justificación de <span class="font-semibold">{{ $justificacion->user->name }}</span>.
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('reprogramaciones.store') }}" method="POST" class="p-6" novalidate>
            @csrf
            <input type="hidden" name="justificacion_id" value="{{ $justificacion->id }}">

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Fecha de Reprogramación
                </label>
                <input type="datetime-local" name="fecha_reprogramada" required min="{{ now()->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm" />
                @error('fecha_reprogramada')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Aula (opcional)
                </label>
                <input type="text" name="aula" maxlength="100" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#009CA9] focus:ring-[#009CA9] shadow-sm" placeholder="Ejemplo: 201, Laboratorio, etc." />
                @error('aula')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-300 dark:active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:ring-offset-2 transition ease-in-out duration-150">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#009CA9] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#007c8b] focus:bg-[#007c8b] active:bg-[#007c8b] focus:outline-none focus:ring-2 focus:ring-[#009CA9] focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Guardar Reprogramación
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 