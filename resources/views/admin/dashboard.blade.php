@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-[#009CA9] tracking-tight">
            Panel de Administración
        </h2>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Gestiona las justificaciones y el sistema
        </p>
    </div>

    <!-- Tarjetas de Resumen -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Justificaciones Pendientes -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Justificaciones Pendientes
                </h3>
                <p class="text-2xl font-semibold text-gray-700 dark:text-gray-300">
                    {{ $conteo['pendiente'] ?? 0 }}
                </p>
            </div>
        </div>
    </div>

    <!-- Justificaciones Aceptadas -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                    <i class="fas fa-check text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Justificaciones Aceptadas
                </h3>
                <p class="text-2xl font-semibold text-gray-700 dark:text-gray-300">
                    {{ $conteo['aceptada'] ?? 0 }}
                </p>
            </div>
        </div>
    </div>

    <!-- Justificaciones Rechazadas -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                    <i class="fas fa-times text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Justificaciones Rechazadas
                </h3>
                <p class="text-2xl font-semibold text-gray-700 dark:text-gray-300">
                    {{ $conteo['rechazada'] ?? 0 }}
                </p>
            </div>
        </div>
    </div>
</div>


    <!-- Lista de Justificaciones -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
        Justificaciones Recientes
    </h3> 

    <form method="GET" action="{{ route('admin.reporte.justificaciones') }}" class="flex flex-wrap items-center gap-2">
        <select name="clase_id" class="rounded-xl border-gray-300 text-sm">
            <option value="">Todas las clases</option>
            @foreach($clases as $clase)
                <option value="{{ $clase->id }}" {{ request('clase_id') == $clase->id ? 'selected' : '' }}>
                {{ $clase->name }}
                </option>
            @endforeach
        </select>


        <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="rounded-xl border-gray-300 text-sm">
        <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="rounded-xl border-gray-300 text-sm">

        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-[#009CA9] hover:bg-[#007c8b] text-white text-sm font-medium rounded-xl shadow transition">
            Generar Reporte
        </button>
    </form>
</div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Estudiante
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Clase
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <form method="GET" action="{{ url()->current() }}">
                                <label for="estado" class="sr-only">Filtrar estado</label>
                                <select name="estado" id="estado" onchange="this.form.submit()"
                                class="w-full text-xs uppercase bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 focus:outline-none focus:ring-[#009CA9] focus:border-[#009CA9] text-gray-700 dark:text-white">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="aceptada" {{ request('estado') == 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                                <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                            </select>
                            </form>
                        </th>

                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($justificaciones as $j)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-[#009CA9] flex items-center justify-center text-white">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $j->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $j->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $j->claseProfesor->clase->name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Grupo {{ $j->claseProfesor->grupo }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($j->fecha)->translatedFormat('d F, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $j->tipo_constancia}}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($j->estado === 'aceptada')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <i class="fas fa-check mr-1"></i> Aceptada
                                    </span>
                                @elseif($j->estado === 'rechazada')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <i class="fas fa-times mr-1"></i> Rechazada
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <i class="fas fa-clock mr-1"></i> Pendiente
                                    </span>
                                @endif
                            </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-normal">
                                <div class="flex gap-2 justify-end items-center" x-data="{ editando: false }">
                                    @if ($j->archivo)
                                    <a href="{{ asset('storage/' . $j->archivo) }}" target="_blank"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs text-[#009CA9] 
                                    hover:text-[#007a85] dark:text-[#009CA9] dark:hover:text-[#007a85] rounded-md 
                                    hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Ver archivo adjunto">
                                    <i class="fas fa-paperclip"></i>
                                    <span>Ver archivo adjunto</span>
                                </a>
                                @endif
                                
                                @if (in_array($j->estado, ['aceptada', 'rechazada']))
                                
                            <button
                                x-show="!editando"
                                @click="editando = true"
                                class="px-3 py-1 text-sm font-medium text-yellow-700 bg-yellow-100 hover:bg-yellow-200 rounded transition-colors">
                                Editar
                            </button>
                            @endif
                            
                            
                            @if ($j->estado === 'pendiente' || in_array($j->estado, ['aceptada', 'rechazada']))
                            <template x-if="editando || '{{ $j->estado }}' === 'pendiente'">
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.justificaciones.aprobar', $j->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                            Aprobar
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.justificaciones.show-rechazar', $j->id) }}" 
                                       class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                        Rechazar
                                    </a>
                                </div>
                            </template>
                            @endif
                        </div>
                    </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                                    <p class="text-lg">No hay justificaciones registradas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection