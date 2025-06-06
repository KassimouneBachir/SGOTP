<div class="message flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}"
     data-message-id="{{ $message->id }}"
     x-data="{ showActions: false }"
     @mouseenter="showActions = true"
     @mouseleave="showActions = false">
     
    <div class="max-w-xs lg:max-w-md rounded-lg p-4 relative
         {{ $message->user_id === auth()->id() ? 'bg-blue-100' : 'bg-white border border-gray-200' }}"
         x-transition:enter="message-enter"
         x-transition:enter-active="message-enter-active">
         
        <!-- Message Header -->
        <div class="flex justify-between items-center mb-2">
            <span class="text-xs font-medium text-gray-700">
                @if($message->user_id === auth()->id())
                    Vous
                @else
                    {{ $message->user->name }}
                @endif
            </span>
            <span class="text-xs text-gray-500 ml-2">
                {{ $message->created_at->format('H:i') }}
            </span>
        </div>

        <!-- Message Content -->
        @if($message->type === 'text')
            <p class="text-gray-800 whitespace-pre-wrap">{{ $message->body }}</p>
        @else
            <div class="mb-2">
                @if($message->type === 'image')
                    <img src="{{ asset('storage/' . $message->attachment_path) }}" 
                         alt="Image jointe" 
                         class="max-w-full h-auto rounded-lg shadow-sm cursor-pointer hover:shadow-md transition"
                         @click="window.open('{{ asset('storage/' . $message->attachment_path) }}')">
                @elseif($message->type === 'video')
                    <video controls class="max-w-full rounded-lg shadow-sm">
                        <source src="{{ asset('storage/' . $message->attachment_path) }}" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                @else
                    <a href="{{ asset('storage/' . $message->attachment_path) }}" 
                       target="_blank" 
                       class="inline-flex items-center text-blue-600 hover:underline">
                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Fichier joint
                    </a>
                @endif
            </div>
        @endif

        <!-- Reactions -->
        @if($message->reactions->count() > 0)
            <div class="flex flex-wrap mt-2 -mx-1">
                @foreach($message->reactions->groupBy('reaction') as $reaction => $group)
                    <span class="text-xs bg-white rounded-full px-2 py-1 border border-gray-200 mx-1 mb-1 shadow-sm">
                        {{ $reaction }} {{ $group->count() }}
                    </span>
                @endforeach
            </div>
        @endif

        <!-- Message Actions (shown on hover) -->
        <div x-show="showActions" class="absolute -bottom-3 right-2 flex space-x-1 bg-white rounded-full shadow px-2 py-1 border border-gray-200">
            @if($message->user_id === auth()->id())
                <button class="text-xs text-gray-500 hover:text-red-500 transition delete-message"
                        data-message-id="{{ $message->id }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            @endif
            
            <div class="reaction-picker inline-flex space-x-1">
                <button class="text-xs hover:scale-125 transform transition add-reaction"
                        data-reaction="👍" data-message-id="{{ $message->id }}">
                    👍
                </button>
                <button class="text-xs hover:scale-125 transform transition add-reaction"
                        data-reaction="❤️" data-message-id="{{ $message->id }}">
                    ❤️
                </button>
                <button class="text-xs hover:scale-125 transform transition add-reaction"
                        data-reaction="😄" data-message-id="{{ $message->id }}">
                    😄
                </button>
                <button class="text-xs hover:scale-125 transform transition add-reaction"
                        data-reaction="😮" data-message-id="{{ $message->id }}">
                    😮
                </button>
            </div>
        </div>
    </div>
</div>
