<?php

namespace App\Providers;

use App\Models\Objet;
use App\Policies\ObjetPolicy;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         Objet::class => ObjetPolicy::class,
         Claim::class => ClaimPolicy::class, // Ajouté
         Conversation::class => ConversationPolicy::class,
    Message::class => MessagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
