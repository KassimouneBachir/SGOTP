@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">Réclamations</h2>
        </div>

        <!-- Onglets -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('claims.index', ['tab' => 'received']) }}" 
                   class="{{ request('tab', 'received') === 'received' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Reçues
                    @if($pendingReceivedCount > 0)
                        <span class="ml-2 bg-blue-100 text-blue-600 py-1 px-2 rounded-full text-xs">
                            {{ $pendingReceivedCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('claims.index', ['tab' => 'sent']) }}"
                   class="{{ request('tab') === 'sent' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Envoyées
                </a>
            </nav>
        </div>

        <!-- Liste des réclamations -->
        <div class="bg-white shadow-sm rounded-lg divide-y divide-gray-200">
            @forelse($claims as $claim)
                <div class="p-6 {{ request()->has('highlight') && request('highlight') == $claim->id ? 'bg-yellow-50' : '' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">
                                Réclamation pour {{ $claim->objet->nom }}
                            </h3>
                            <div class="mt-1 text-sm text-gray-500">
                                @if(request('tab', 'received') === 'received')
                                    Par : {{ $claim->user->name }}
                @else
                                    À : {{ $claim->objet->user->name }}
                                @endif
                            </div>
                            
                            <!-- Description de la réclamation -->
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Description de la réclamation :</h4>
                                <p class="text-gray-800 text-sm whitespace-pre-line">{{ $claim->description }}</p>
                            </div>

                            <!-- Affichage des réponses aux questions spécifiques -->
                            @if($claim->answers)
                                <div class="mt-4 bg-gray-50 rounded-md p-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Réponses aux questions :</h4>
                                    <div class="space-y-2">
                                        @foreach($claim->answers as $index => $answer)
                                            <div class="text-sm">
                                                <span class="font-medium text-gray-600">Question {{ $index + 1 }} :</span>
                                               <span class="text-gray-800">
                                                    @isset($claim->objet->details_specifiques[$index])
                                                        {{ $claim->objet->details_specifiques[$index] }}
                                                    @else
                                                        Question non disponible
                                                    @endisset
                                                </span>
                                                <br>
                                                <span class="font-medium text-gray-600">Réponse :</span>
                                                <span class="text-gray-800">{{ $answer }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Pièce jointe -->
                            @if($claim->proof_url)
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Pièce jointe :</h4>
                                    <a href="{{ $claim->proof_url }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        Voir la pièce jointe
                                    </a>
                                        </div>
                                        @endif

                            <div class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $claim->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($claim->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ $claim->status === 'approved' ? 'Approuvée' : 
                                       ($claim->status === 'rejected' ? 'Rejetée' : 'En attente') }}
                                </span>
                            </div>
                            
                            @if($claim->status === 'rejected' && $claim->rejection_reason)
                                <div class="mt-2 text-sm text-red-600">
                                    Motif du rejet : {{ $claim->rejection_reason }}
                                </div>
                            @endif
                        </div>

                        <div class="ml-4">
                            @if($claim->status === 'pending' && request('tab', 'received') === 'received' && $claim->canBeProcessedBy(auth()->user()))
                                <div class="flex space-x-2">
                                    <form action="{{ route('claims.approve', $claim) }}" method="POST" class="inline">
                                    @csrf
                                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                        Approuver
                                    </button>
                                </form>

                                    <button type="button" onclick="openRejectModal('{{ $claim->id }}')" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    Rejeter
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    Aucune réclamation {{ request('tab') === 'sent' ? 'envoyée' : 'reçue' }}.
                </div>
            @endforelse
        </div>

        @if($claims->hasPages())
                    <div class="mt-4">
                {{ $claims->appends(['tab' => request('tab')])->links() }}
                    </div>
                    @endif
                </div>
            </div>

<!-- Modal de rejet -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" role="dialog">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Rejeter la réclamation</h3>
            <form id="rejectForm" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Motif du rejet</label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required minlength="10" placeholder="Veuillez expliquer pourquoi vous rejetez cette réclamation..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">
                        Annuler
                    </button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Confirmer le rejet
                    </button>
                </div>
            </form>
       </div>
    </div>
</div>

@push('scripts')
    <script>
function openRejectModal(claimId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/claims/${claimId}/reject`;
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
}

// Fermer le modal en cliquant en dehors
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
        });
    </script>
@endpush
@endsection    