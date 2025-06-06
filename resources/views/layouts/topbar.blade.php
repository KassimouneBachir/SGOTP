<header class="bg-white shadow-sm">
    <div class="px-4 sm:px-6 py-4 flex items-center justify-between">
        <!-- Menu mobile -->
        <div class="flex items-center md:hidden">
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="text-gray-500 hover:text-gray-600 focus:outline-none" 
                    aria-label="Toggle menu">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Titre de la page -->
        <div class="hidden md:block">
            <h1 class="text-xl font-semibold text-gray-900">@yield('page_title', 'Tableau de bord')</h1>
        </div>

        <!-- Actions à droite -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}" 
               class="relative p-2 text-gray-500 hover:text-gray-600 focus:outline-none">
                <span class="sr-only">Notifications</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                @endif
            </a>

            <!-- Profil dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        @click.away="open = false"
                        class="flex items-center space-x-3 focus:outline-none" 
                        aria-expanded="false">
                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    <svg class="hidden sm:block h-4 w-4 text-gray-500" 
                         :class="{ 'rotate-180': open }"
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                     x-cloak>
                    <a href="{{ route('profile.edit') }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

            