<div class="sidebar fixed h-full w-64 bg-white shadow-sm hidden md:block overflow-y-auto">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="text-xl font-bold">CampusLost</span>
                </div>
            </div>
    
            <nav class="p-4">
        <div class="space-y-2">
            <!-- Tableau de bord -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'active-nav' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                        Tableau de bord
                    </a>

            <!-- Objets perdus/trouvés -->
            <a href="{{ route('objets.index') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('objets.*') ? 'active-nav' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                        Objets perdus/trouvés
                    </a>

            <!-- Messagerie -->
            <a href="{{ route('chat.index') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('chat.*') ? 'active-nav' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                        Messagerie
                        @if(auth()->user()->unreadMessagesCount() > 0)
                    <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ auth()->user()->unreadMessagesCount() }}
                            </span>
                        @endif
                    </a>

            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('notifications.*') ? 'active-nav' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifications
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>

            <!-- Réclamations -->
            <a href="{{ route('claims.index') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('claims.*') ? 'active-nav' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Réclamations
                @php
                    $pendingClaims = auth()->user()->claims()->where('status', 'pending')->count();
                    $pendingReceivedClaims = auth()->user()->receivedClaims()->where('status', 'pending')->count();
                    $totalPendingClaims = $pendingClaims + $pendingReceivedClaims;
                @endphp
                @if($totalPendingClaims > 0)
                    <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $totalPendingClaims }}
                    </span>
                @endif
            </a>

           <!-- Admin section -->
            @if(auth()->user()->role === 'admin')
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Administration
                    </h3>
                    
                    <!-- Statistiques -->
                    <a href="{{ route('admin.statistics') }}" 
                    class="flex items-center px-4 py-2 mt-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.statistics') ? 'active-nav' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Statistiques
                    </a>

                    <!-- Gestion utilisateurs (existant) -->
                    <a href="{{ route('admin.users.index') }}" 
                    class="flex items-center px-4 py-2 mt-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'active-nav' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Gestion utilisateurs
                    </a>
                </div>
            @endif

            <!-- User section -->
            <div class="pt-4 mt-4 border-t border-gray-200" x-data="{ settingsOpen: false }">
                <div class="space-y-1">
                    <button @click="settingsOpen = !settingsOpen" 
                            class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-md focus:outline-none"
                            :aria-expanded="settingsOpen">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                            Paramètres
                        <svg class="ml-auto h-4 w-4 transform transition-transform" 
                             :class="{ 'rotate-180': settingsOpen }"
                             viewBox="0 0 20 20" 
                             fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="settingsOpen"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="mt-2 space-y-1">
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </nav>
        </div>