// resources/js/app.js
import './bootstrap';
import './chat'; // ← assure-toi que cette ligne est présente

class ChatManager {
    constructor() {
        this.typingTimeout = null;
        this.isTyping = false;
        this.lastTypingTime = 0;
        this.currentConversationId = document.querySelector('#message-form')?.dataset.conversationId;
        this.channelSubscription = null;
        this.init();
    }

    init() {
        console.log('ChatManager initialized with conversation ID:', this.currentConversationId);
        this.initializeEventListeners();
        this.initializeWebSockets();
    }

    initializeEventListeners() {
        document.addEventListener('submit', this.handleFormSubmit.bind(this));
        document.addEventListener('input', this.handleTyping.bind(this));
        document.addEventListener('click', this.handleMessageActions.bind(this));
    }

    initializeWebSockets() {
        if (!this.currentConversationId) {
            console.log('No conversation ID found');
            return;
        }

        console.log('Setting up WebSocket connection for conversation:', this.currentConversationId);

        try {
            if (!window.Echo) {
                console.error('Laravel Echo is not initialized');
                return;
            }

            // Désinscription du canal précédent s'il existe
            if (this.channelSubscription) {
                this.channelSubscription.unsubscribe();
            }

            // Souscription au nouveau canal
            this.channelSubscription = window.Echo.private(`conversation.${this.currentConversationId}`);
            
            console.log('Subscribing to channel:', `conversation.${this.currentConversationId}`);
            
            this.channelSubscription
                .listen('.message.sent', (e) => {
                    console.log('MessageSent event received:', e);
                    if (e.message.user_id !== window.authUserId) {
                        this.appendMessage(e.message);
                        if (this.isAtBottom()) {
                            this.scrollToBottom();
                        }
                    }
                })
                .listen('.user.typing', (e) => {
                    console.log('UserTyping event received:', e);
                    this.handleUserTyping(e);
                })
                .listen('.message.read', (e) => {
                    console.log('MessageRead event received:', e);
                    this.handleMessageRead(e);
                });

            this.channelSubscription.error((error) => {
                console.error('Channel error:', error);
            });

            console.log('WebSocket connection established successfully');
        } catch (error) {
            console.error('Error setting up WebSocket connection:', error);
        }
    }

    handleFormSubmit(e) {
        if (!e.target.matches('#message-form')) return;
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const input = form.querySelector('#message-input');
        const conversationId = form.dataset.conversationId;

        if (!input.value.trim() && !formData.get('attachment')) {
            return;
        }

        console.log('Sending message to conversation:', conversationId);

        fetch(`/chat/${conversationId}/send`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Message sent successfully:', data);
            input.value = '';
            this.appendMessage(data.message);
            this.scrollToBottom();
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Erreur lors de l\'envoi du message. Veuillez réessayer.');
        });
    }

    handleTyping(e) {
        if (!e.target.matches('#message-input')) return;

        const now = Date.now();
        const conversationId = e.target.closest('form').dataset.conversationId;

        if (!this.isTyping) {
            this.isTyping = true;
            this.sendTypingStatus(conversationId, true);
        }

        this.lastTypingTime = now;
        clearTimeout(this.typingTimeout);

        this.typingTimeout = setTimeout(() => {
            if (now - this.lastTypingTime >= 2000 && this.isTyping) {
                this.isTyping = false;
                this.sendTypingStatus(conversationId, false);
            }
        }, 2000);
    }

    handleMessageActions(e) {
        if (e.target.closest('.reaction-button')) {
            this.handleReactionClick(e);
        } else if (e.target.closest('.delete-message')) {
            this.handleDeleteMessage(e);
        }
    }

    handleUserTyping(e) {
        const indicator = document.getElementById(`typing-indicator-${this.currentConversationId}`);
        if (!indicator) return;

        if (e.is_typing && e.user.id !== window.authUserId) {
            indicator.textContent = `${e.user.name} est en train d'écrire...`;
            indicator.classList.remove('hidden');
            
            clearTimeout(this.typingTimeout);
            this.typingTimeout = setTimeout(() => {
                indicator.classList.add('hidden');
            }, 3000);
        } else {
            indicator.classList.add('hidden');
        }
    }

    handleMessageRead(e) {
        const message = document.querySelector(`[data-message-id="${e.messageId}"]`);
        if (!message) return;

        const readStatus = message.querySelector('.read-status');
        if (readStatus) {
            readStatus.textContent = 'Lu';
        }
    }

    handleReactionClick(e) {
        const button = e.target.closest('.reaction-button');
        const messageId = button.closest('[data-message-id]').dataset.messageId;
        const reaction = button.dataset.reaction;

        fetch(`/chat/message/${messageId}/reaction`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reaction })
        })
        .catch(error => console.error('Error:', error));
    }

    handleDeleteMessage(e) {
        if (!confirm('Voulez-vous vraiment supprimer ce message ?')) return;

        const message = e.target.closest('[data-message-id]');
        const messageId = message.dataset.messageId;

        fetch(`/chat/message/${messageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(() => message.remove())
        .catch(error => console.error('Error:', error));
    }

    sendTypingStatus(conversationId, isTyping) {
        fetch(`/chat/${conversationId}/typing`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ is_typing: isTyping })
        }).catch(error => console.error('Error:', error));
    }

  
    appendMessage(message) {
        const container = document.getElementById('messages-container');
        if (!container) return;

        if (document.querySelector(`[data-message-id="${message.id}"]`)) {
            return;
        }

        const messageElement = this.createMessageElement(message);
        container.insertBefore(messageElement, container.firstChild); // Ajoute en haut
        
        // Fait défiler vers le nouveau message (en haut)
        container.scrollTop = 0;
    }

    createMessageElement(message) {
        const div = document.createElement('div');
        const isCurrentUser = message.user_id === window.authUserId;
        
        div.className = `message flex ${isCurrentUser ? 'justify-end' : 'justify-start'} mb-4`;
        div.dataset.messageId = message.id;
        
        div.innerHTML = `
            <div class="max-w-xs lg:max-w-md ${isCurrentUser ? 'bg-blue-100' : 'bg-white border border-gray-200'} rounded-lg p-3">
                <div class="flex justify-between items-baseline mb-1">
                    <span class="text-sm font-medium text-gray-900">
                        ${isCurrentUser ? 'Vous' : message.user.name}
                    </span>
                    <span class="text-xs text-gray-500 ml-2">
                        ${this.formatTime(message.created_at)}
                    </span>
                </div>
                ${this.renderMessageContent(message)}
            </div>
        `;
        
        return div;
    }

    renderMessageContent(message) {
        if (message.type === 'text') {
            return `<p class="text-gray-800 break-words">${this.escapeHtml(message.body)}</p>`;
        }
        
        if (message.type === 'image') {
            return `<img src="${message.attachment_path}" alt="Image" class="max-w-full rounded-lg cursor-pointer hover:opacity-90">`;
        }
        
        return `<a href="${message.attachment_path}" target="_blank" class="text-blue-600 hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
            </svg>
            Pièce jointe
        </a>`;
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    formatTime(timestamp) {
        return new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    isAtBottom() {
        const container = document.getElementById('messages-container');
        if (!container) return false;
        return container.scrollHeight - container.clientHeight <= container.scrollTop + 100;
    }

    scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
}

// Créer une instance globale du ChatManager
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing ChatManager');
    window.chatManager = new ChatManager();
});