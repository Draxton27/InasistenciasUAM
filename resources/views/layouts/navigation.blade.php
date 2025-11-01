<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @if(Auth::user() && Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @elseif(Auth::user() && Auth::user()->role === 'profesor')
                        <a href="{{ route('profesor.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @else
                        <a href="{{ route('justificaciones.index') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @endif
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::user() && Auth::user()->role === 'alumno')
                        <x-nav-link :href="route('justificaciones.index')" :active="request()->routeIs('justificaciones.*')">
                            {{ __('Justificaciones') }}
                        </x-nav-link>
                    @endif
                    @if(Auth::user() && Auth::user()->role === 'profesor')
                        <x-nav-link :href="route('profesor.dashboard')" :active="request()->routeIs('profesor.*')">
                            {{ __('Justificaciones') }}
                        </x-nav-link>
                    @endif
                    @if(Auth::user() && Auth::user()->role === 'admin')
                        <x-nav-link :href="route('profesores.index')" :active="request()->routeIs('profesores.*')">
                            {{ __('Profesores') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Admin') }}
                        </x-nav-link>
                        <x-nav-link :href="route('clases.index')" :active="request()->routeIs('clases.index')">
                            {{ __('Clases') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Notifications + Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <!-- Notifications Bell -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fa-regular fa-bell text-gray-600 dark:text-gray-300"></i>
                        @php($unread = Auth::check() ? Auth::user()->unreadNotifications()->count() : 0)
                        <span data-notifications-badge class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-medium leading-none text-white bg-red-600 rounded-full {{ $unread > 0 ? '' : 'hidden' }}">{{ $unread }}</span>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg overflow-hidden z-50">
                        <div class="px-3 py-2 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <div class="font-semibold text-sm">Notificaciones</div>
                            <a href="{{ route('notifications.index') }}" class="text-xs text-indigo-600 hover:underline">Ver todas</a>
                        </div>
                        <div class="max-h-80 overflow-y-auto" data-notifications-dropdown-list>
                            @forelse((Auth::check() ? Auth::user()->unreadNotifications()->latest()->take(5)->get() : collect()) as $n)
                                <div class="px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex gap-2">
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
                                        <div class="text-sm font-medium">{{ $n->data['title'] ?? 'Notification' }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-300">{{ $n->data['body'] ?? '' }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $n->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-3 py-4 text-sm text-gray-500" data-notifications-dropdown-empty>No hay notificaciones sin leer</div>
                            @endforelse
                        </div>
                        <div class="px-3 py-2 border-t border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf
                                <button class="w-full text-xs text-gray-600 hover:text-gray-800 js-confirm" data-confirm="¿Marcar todas como leídas?">Marcar todas como leídas</button>
                            </form>
                        </div>
                    </div>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Salir') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if (Auth::check() && Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Admin') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profesores.index')" :active="request()->routeIs('profesores.*')">
                    {{ __('Profesores') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('clases.index')" :active="request()->routeIs('clases.index')">
                    {{ __('Clases') }}
                </x-responsive-nav-link>
            @elseif (Auth::check() && Auth::user()->role === 'profesor')
                <x-responsive-nav-link :href="route('profesor.dashboard')" :active="request()->routeIs('profesor.*')">
                    {{ __('Justificaciones') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('justificaciones.index')" :active="request()->routeIs('justificaciones.*')">
                    {{ __('Justificaciones') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Salir') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
