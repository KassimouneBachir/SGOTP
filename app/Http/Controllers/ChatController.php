<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Conversation; // Import manquant
use App\Models\User;         // Import également nécessaire
use App\Models\Message;
use App\Models\MessageReadStatus; // Ajoutez cette ligne
use App\Events\MessageSent;




class ChatController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Liste des conversations
public function index()
{
    $userId = auth()->id();
    $key = "user.{$userId}.conversations";

    $conversations = Cache::remember($key, now()->addMinutes(30), function() use ($userId) {
        return auth()->user()->conversations()
            ->with(['messages' => function($query) {
                $query->latest()->limit(1);
            }, 'participants.user'])
            ->get();
    });

    return view('chat.index', compact('conversations'));
}



public function show(Conversation $conversation)
{
    $this->authorize('view', $conversation);

    // Trouver l'objet associé si la conversation concerne un objet
    $objet = null;
    foreach($conversation->messages as $message) {
        if(str_contains($message->body, 'objet_id:')) {
            $objetId = str_replace('objet_id:', '', $message->body);
            $objet = Objet::find($objetId);
            break;
        }
    }

    return view('chat.conversation', [
        'conversation' => $conversation,
        'messages' => $conversation->messages()
            ->with(['user', 'readStatuses', 'reactions.user'])
            ->orderBy('created_at', 'asc') // Tri du plus ancien au plus récent
            ->paginate(20),
        'objet' => $objet
    ]);
}

    
    // Afficher une conversation








    // Envoyer un message
   public function sendMessage(Request $request, Conversation $conversation)
{
    $this->authorize('view', $conversation);

    $request->validate([
        'body' => 'nullable|string|max:2000',
        'attachment' => 'nullable|file|max:10240',
    ]);

    try {
        $message = new Message();
        $message->user_id = auth()->id();
        $message->conversation_id = $conversation->id;
        $message->body = $request->body ?? null;

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $message->attachment_path = $path;
            $message->type = $this->getFileType($request->file('attachment'));
        } else {
            $message->type = 'text';
        }

        $message->save();

        // Créer des entrées de statut de lecture pour tous les participants
        foreach ($conversation->participants as $participant) {
            if ($participant->user_id !== auth()->id()) {
                MessageReadStatus::create([
                    'message_id' => $message->id,
                    'user_id' => $participant->user_id,
                    'is_read' => false,
                ]);
            }
        }

        // Charger les relations nécessaires
        $message->load(['user', 'readStatuses', 'reactions']);

        // Diffuser le message
        try {
            broadcast(new MessageSent($message, $conversation->id))->toOthers();
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la diffusion du message : ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);

    } catch (\Exception $e) {
        \Log::error('Erreur lors de l\'envoi du message : ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Une erreur est survenue lors de l\'envoi du message'
        ], 500);
    }
}

    // Marquer les messages comme lus
    public function markAsRead(Message $message)
    {
        $this->authorize('view', $message->conversation);

        $status = MessageReadStatus::where('message_id', $message->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($status && !$status->is_read) {
            $status->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            // Diffuser l'événement de lecture
            broadcast(new MessageRead($message, auth()->user()));
        }

        return response()->json(['success' => true]);
    }

    // Ajouter une réaction
    public function addReaction(Request $request, Message $message)
    {
        $this->authorize('view', $message->conversation);

        $request->validate([
            'reaction' => 'required|string|max:10',
        ]);

        // Vérifier si l'utilisateur a déjà réagi à ce message
        $existingReaction = MessageReaction::where('message_id', $message->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReaction) {
            $existingReaction->update(['reaction' => $request->reaction]);
        } else {
            MessageReaction::create([
                'message_id' => $message->id,
                'user_id' => auth()->id(),
                'reaction' => $request->reaction,
            ]);
        }

        // Diffuser l'événement de réaction
        broadcast(new ReactionAdded($message, auth()->user(), $request->reaction));

        return response()->json(['success' => true]);
    }

    // Supprimer un message
    public function deleteMessage(Message $message)
    {
        $this->authorize('delete', $message);

        $message->delete();

        // Diffuser l'événement de suppression
        broadcast(new MessageDeleted($message));

        return response()->json(['success' => true]);
    }

    // Indicateur "typing..."
public function typing(Request $request, Conversation $conversation)
{
    $this->authorize('view', $conversation);

    $isTyping = $request->input('is_typing', true);
    
    broadcast(new UserTyping(auth()->user(), $conversation->id, $isTyping))->toOthers();

    return response()->json(['success' => true]);
}


public function startConversation($userId)
{
    $otherUser = User::findOrFail($userId);
    $currentUser = auth()->user();

    // Vérifie si une conversation existe déjà
    $conversation = Conversation::whereHas('participants', function($q) use ($currentUser) {
        $q->where('user_id', $currentUser->id);
    })->whereHas('participants', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })->first();

    // Sinon, crée une nouvelle conversation
    if(!$conversation) {
        $conversation = Conversation::create();
        
        $conversation->participants()->create([
            'user_id' => $currentUser->id
        ]);
        
        $conversation->participants()->create([
            'user_id' => $userId
        ]);
    }

    return redirect()->route('chat.show', $conversation);
}


    // Méthodes privées
    private function markMessagesAsRead($conversation, $messages)
    {
        $unreadMessages = $conversation->messages()
            ->whereDoesntHave('readStatuses', function($query) {
                $query->where('user_id', auth()->id())
                      ->where('is_read', true);
            })
            ->where('user_id', '!=', auth()->id())
            ->get();

        foreach ($unreadMessages as $message) {
            MessageReadStatus::updateOrCreate(
                ['message_id' => $message->id, 'user_id' => auth()->id()],
                ['is_read' => true, 'read_at' => now()]
            );
        }
    }

    private function getFileType($file)
    {
        $mime = $file->getMimeType();
        
        if (str_contains($mime, 'image/')) {
            return 'image';
        } elseif (str_contains($mime, 'video/')) {
            return 'video';
        } elseif (str_contains($mime, 'audio/')) {
            return 'audio';
        } elseif ($mime === 'application/pdf') {
            return 'pdf';
        } else {
            return 'file';
        }
    }
}
