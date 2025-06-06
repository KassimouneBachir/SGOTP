<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ChatTest extends TestCase
{
    public function test_user_can_view_conversations()
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        $conversation->participants()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get('/chat');

        $response->assertStatus(200);
    }

    public function test_user_cannot_view_unauthorized_conversation()
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();

        $response = $this->actingAs($user)
            ->get('/chat/' . $conversation->id);

        $response->assertStatus(403);
    }

    public function test_user_can_send_message()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        $conversation->participants()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post('/chat/' . $conversation->id . '/send', [
                'body' => 'Hello world'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => 'Hello world'
        ]);

        // Test avec fichier joint
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->actingAs($user)
            ->post('/chat/' . $conversation->id . '/send', [
                'attachment' => $file
            ]);

        $response->assertStatus(200);
        Storage::disk('public')->assertExists('attachments/' . $file->hashName());
    }

    public function test_message_read_status()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $conversation = Conversation::factory()->create();
        $conversation->participants()->createMany([
            ['user_id' => $user1->id],
            ['user_id' => $user2->id]
        ]);

        $message = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'user_id' => $user1->id
        ]);

        $response = $this->actingAs($user2)
            ->post('/chat/message/' . $message->id . '/read');

        $response->assertStatus(200);
        $this->assertDatabaseHas('message_read_status', [
            'message_id' => $message->id,
            'user_id' => $user2->id,
            'is_read' => true
        ]);
    }


    // Dans ChatTest
public function test_message_read_event()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $conversation = Conversation::factory()->create();
    $conversation->participants()->createMany([
        ['user_id' => $user1->id],
        ['user_id' => $user2->id]
    ]);

    $message = Message::factory()->create([
        'conversation_id' => $conversation->id,
        'user_id' => $user1->id
    ]);

    $this->actingAs($user2)
        ->post('/chat/message/' . $message->id . '/read');

    Event::assertDispatched(MessageRead::class, function ($event) use ($message, $user2) {
        return $event->message->id === $message->id && 
               $event->user->id === $user2->id;
    });
}

public function test_typing_indicator()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $conversation = Conversation::factory()->create();
    $conversation->participants()->createMany([
        ['user_id' => $user1->id],
        ['user_id' => $user2->id]
    ]);

    $this->actingAs($user1)
        ->post('/chat/' . $conversation->id . '/typing', [
            'is_typing' => true
        ]);

    Event::assertDispatched(UserTyping::class, function ($event) use ($user1, $conversation) {
        return $event->user->id === $user1->id && 
               $event->conversationId === $conversation->id &&
               $event->isTyping === true;
    });
}
}