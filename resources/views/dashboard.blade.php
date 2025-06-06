@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Notifications -->
        @if($notifications->isNotEmpty())
        <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Notifications</h2>
                    @if($unreadNotifications->isNotEmpty())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $unreadNotifications->count() }} nouvelle(s)
                        </span>
                                            @endif
                </div>
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                    <div class="flex items-center justify-between {{ !$notification->read_at ? 'bg-blue-50' : 'bg-gray-50' }} p-4 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm {{ !$notification->read_at ? 'text-blue-900' : 'text-gray-900' }}">
                                {{ $notification->data['message'] }}
                            </p>
                            <span class="text-xs {{ !$notification->read_at ? 'text-blue-700' : 'text-gray-700' }}">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                                    </div>
                        <div class="flex items-center space-x-2">
                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-800">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                           
                                    </div>
                                </div>
                    @endforeach
                </div>
                @if(auth()->user()->notifications()->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Voir toutes les notifications
                        </a>
                            </div>
                @endif
                                        </div>
                                    </div>
        @endif

        <!-- Derniers objets ajoutés -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Derniers objets</h2>
                    <a href="{{ route('objets.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Déclarer un objet
                                    </a>
                                </div>

                @if($recentObjects->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun objet</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Commencez par déclarer un objet perdu ou trouvé.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach(['trouvé', 'perdu'] as $statut)
                            @if($recentObjects->has($statut))
                                @foreach($recentObjects[$statut] as $objet)
                                <div class="relative bg-white border rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                                    <span class="absolute top-2 right-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $objet->statut === 'perdu' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($objet->statut) }}
                                    </span>
                                    
                                    <img src="{{ $objet->photo_url }}" 
                                         alt="{{ $objet->nom }}" 
                                         class="w-full h-48 object-cover">
                                         
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $objet->nom }}</h3>
                                        <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $objet->description }}</p>
                                        
                                        <div class="mt-4 flex items-center justify-between">
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                                {{ $objet->lieu }}
                                            </div>
                                            <a href="{{ route('objets.show', $objet) }}" 
                                               class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Voir détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        @endforeach
            </div>
                @endif
            </div>
                                    </div>

        <!-- Mes objets -->
        @if($userObjects->isNotEmpty())
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Mes objets</h2>
                <div class="space-y-4">
                    @foreach($userObjects as $objet)
                    <div class="flex items-center justify-between border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $objet->photo_url }}" 
                                 alt="{{ $objet->nom }}" 
                                 class="w-16 h-16 object-cover rounded">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">{{ $objet->nom }}</h3>
                                <p class="text-sm text-gray-500">{{ ucfirst($objet->statut) }}</p>
                                @if($objet->claims->isNotEmpty())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $objet->claims->count() }} réclamation(s) en attente
                                    </span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('objets.show', $objet) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Gérer
                        </a>
                    </div>
                    @endforeach
            </div>
            </div>
        </div>
        @endif

        <!-- Réclamations en attente -->
        @if($pendingClaims->isNotEmpty())
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Réclamations en attente</h2>
            <div class="space-y-4">
                    @foreach($pendingClaims as $claim)
                    <div class="flex items-center justify-between border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $claim->objet->photo_url }}" 
                                 alt="{{ $claim->objet->nom }}" 
                                 class="w-16 h-16 object-cover rounded">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">{{ $claim->objet->nom }}</h3>
                                <p class="text-sm text-gray-500">Réclamé par {{ $claim->user->name }}</p>
                                <span class="text-xs text-gray-400">{{ $claim->created_at->diffForHumans() }}</span>
                            </div>
                    </div>
                        <a href="{{ route('objets.show', $claim->objet) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Examiner
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
                            </div>
                        </div>
@endsection