@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Notificaciones</h2>
                    <form method="POST" action="{{ route('notifications.readAll') }}">
                        @csrf
                        <button class="px-3 py-1.5 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded js-confirm" data-confirm="¿Marcar todas las notificaciones como leídas?">
                            Marcar todas como leídas
                        </button>
                    </form>
                </div>

                @if($notifications->isEmpty())
                    <p class="text-gray-500" data-notifications-empty>Aún no hay notificaciones.</p>
                @endif
                <div class="divide-y divide-gray-200 dark:divide-gray-700" data-notifications-list>
                        @foreach($notifications as $n)
                            <div class="py-3 flex items-start gap-3 {{ is_null($n->read_at) ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }} rounded">
                                <div class="mt-1">
                                    @if(($n->data['status'] ?? '') === 'aceptada')
                                        <i class="fa-solid fa-circle-check text-green-600"></i>
                                    @elseif(($n->data['status'] ?? '') === 'rechazada')
                                        <i class="fa-solid fa-circle-xmark text-red-600"></i>
                                    @else
                                        <i class="fa-solid fa-bell text-indigo-600"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">{{ $n->data['title'] ?? 'Notificación' }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">{{ $n->data['body'] ?? '' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $n->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if(isset($n->data['url']))
                                        <a href="{{ $n->data['url'] }}" class="text-sm text-indigo-600 hover:underline">Abrir</a>
                                    @endif
                                    @if(is_null($n->read_at))
                                        <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                                            @csrf
                                            <button class="text-sm text-gray-600 hover:text-gray-800 js-confirm" data-confirm="¿Marcar como leída?">Marcar como leída</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">Leer</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                </div>

                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
