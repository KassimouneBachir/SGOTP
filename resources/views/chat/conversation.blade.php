@extends('layouts.chat')

@section('content')
<div class="h-screen flex flex-col bg-gray-50">
    <!-- Header simple avec flèche de retour -->
    

    <div class="flex-1 overflow-hidden">
        <!-- Le reste du contenu de votre vue conversation.blade.php actuelle -->
        <div class="flex flex-col h-full">
            <!-- En-tête de la conversation -->
            <!-- ... le reste du code existant ... -->
    <!-- En-tête de la conversation -->
    <div class="border-b border-gray-200 p-3 flex items-center bg-white">
        <div class="flex items-center flex-1">
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                <span class="text-blue-600 font-medium">
                    {{ strtoupper(substr($conversation->participants->firstWhere('user_id', '!=', auth()->id())->user->name, 0, 1)) }}
                </span>
            </div>
            <div class="ml-3">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ $conversation->participants->firstWhere('user_id', '!=', auth()->id())->user->name }}
                </h2>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div id="messages-container" class="flex-1 overflow-y-auto p-4 flex flex-col-reverse bg-gray-50">
        @foreach($messages as $message)
            @include('chat.message', ['message' => $message])
        @endforeach
    </div>

    <!-- Zone de saisie -->
    <div class="border-t border-gray-200 p-3 bg-white">
        <form id="message-form" class="flex items-center space-x-2" data-conversation-id="{{ $conversation->id }}">
            @csrf
            <div class="flex-1 relative">
                <input type="text" 
                       id="message-input"
                       name="body" 
                       class="w-full rounded-full border-gray-300 pl-4 pr-12 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       placeholder="Écrivez votre message..."
                       autocomplete="off">
                <label for="attachment" class="absolute right-3 top-2 text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    <input type="file" id="attachment" name="attachment" class="hidden">
                </label>
            </div>
            <button type="submit" class="rounded-full p-2 bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </form>
    </div>
</div>
</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.authUserId = {{ auth()->id() }};
    // Initialiser le chat manager si nécessaire
    if (window.chatManager) {
        window.chatManager.currentConversationId = {{ $conversation->id }};
        window.chatManager.initializeWebSockets();
    }
</script>
@endpush
