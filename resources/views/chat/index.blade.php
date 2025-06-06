@extends('layouts.chat')

@section('content')
<div class="h-screen flex flex-col bg-gray-50">
    <!-- Header simple avec flèche de retour -->
    <header class="bg-white shadow-sm">
        <div class="w-full px-4 py-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="text-lg font-medium">Messages</span>
            </a>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <!-- Liste des conversations -->
        <div class="w-[400px] bg-white border-r border-gray-200 overflow-y-auto">
            @forelse($conversations as $conversation)
                <button 
                    onclick="loadConversation({{ $conversation->id }})"
                    class="w-full text-left hover:bg-gray-50 transition-colors duration-150 p-4 flex items-center space-x-3 border-b border-gray-100 last:border-b-0"
                    data-conversation-id="{{ $conversation->id }}">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-medium">
                                {{ strtoupper(substr($conversation->participants->firstWhere('user_id', '!=', auth()->id())->user->name, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $conversation->participants->firstWhere('user_id', '!=', auth()->id())->user->name }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            {{ $conversation->messages->last()?->body ?? 'Aucun message' }}
                        </p>
                    </div>
                </button>
            @empty
                <div class="p-4 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="text-lg font-medium text-gray-900 mb-1">Aucune conversation</p>
                    <p class="text-gray-500">Commencez une nouvelle discussion</p>
                </div>
            @endforelse
        </div>

        <!-- Zone de conversation (chargée dynamiquement) -->
        <div id="conversation-container" class="flex-1 bg-white overflow-hidden">
            <div id="empty-state" class="h-full flex items-center justify-center">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Aucune conversation sélectionnée</h3>
                    <p class="text-gray-500">Sélectionnez une conversation pour commencer à discuter</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.authUserId = {{ auth()->id() }};

    function initializeConversation(id) {
        if (window.chatManager) {
            window.chatManager.currentConversationId = id;
            window.chatManager.initializeWebSockets();
        }
    }

    function loadConversation(id) {
        const container = document.getElementById('conversation-container');
        const emptyState = document.getElementById('empty-state');
        
        // Mettre à jour la classe active
        document.querySelectorAll('[data-conversation-id]').forEach(el => {
            el.classList.remove('bg-blue-50');
        });
        document.querySelector(`[data-conversation-id="${id}"]`).classList.add('bg-blue-50');
        
        // Charger la conversation
        fetch(`/chat/${id}`)
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                const messagesContainer = document.getElementById('messages-container');
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
                initializeConversation(id);
            });
    }
</script>
@endpush
@endsection