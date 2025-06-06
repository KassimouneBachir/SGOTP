@extends('layouts.app')

@section('page_title', 'Objets perdus et trouvés')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Objets perdus et trouvés</h1>
        <a href="{{ route('objets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Déclarer un objet
        </a>
    </div>

    <!-- Filtres et recherche -->
    <div class="mt-8 bg-white shadow rounded-lg p-6">
        <form action="{{ route('objets.index') }}" method="GET" class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-4">
            <div class="flex-1">
                <label for="search" class="sr-only">Rechercher</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}"
                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                           placeholder="Rechercher un objet...">
                </div>
            </div>

            <div class="sm:w-40">
                <label for="statut" class="sr-only">Statut</label>
                <select name="statut" 
                        id="statut" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Tous les statuts</option>
                    <option value="perdu" {{ request('statut') === 'perdu' ? 'selected' : '' }}>Perdus</option>
                    <option value="trouvé" {{ request('statut') === 'trouvé' ? 'selected' : '' }}>Trouvés</option>
                    <option value="rendu" {{ request('statut') === 'rendu' ? 'selected' : '' }}>Rendus</option>
                </select>
            </div>

            <button type="submit" 
                    class="w-full sm:w-auto flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Filtrer
            </button>

            @if(request('search') || request('statut'))
                <a href="{{ route('objets.index') }}" 
                   class="w-full sm:w-auto flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    <!-- Liste des objets -->
    <div class="mt-8 bg-white shadow-sm sm:rounded-lg">
        @if($objets->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun objet trouvé</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request('search') || request('statut'))
                        Aucun résultat ne correspond à vos critères de recherche.
                    @else
                        Commencez par déclarer un objet perdu ou trouvé.
                    @endif
                </p>
            </div>
        @else
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($objets as $objet)
                    <div class="relative bg-white border rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <span class="absolute top-2 right-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $objet->statut === 'perdu' ? 'bg-red-100 text-red-800' : ($objet->statut === 'trouvé' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
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
                </div>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $objets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection