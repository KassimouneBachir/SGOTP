@extends('layouts.app')
@section('content')
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-100">
                        <div class="container mx-auto py-6">
                            <div class="flex justify-between items-center mb-6">
                                <h1 class="text-2xl font-bold">Mes Notifications</h1>
                                <form action="{{ route('notifications.read.all') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-800">
                                        Tout marquer comme lu
                                    </button>
                                </form>
                            </div>

                            @foreach($notifications as $date => $group)
                                <div class="mb-8">
                                    <h2 class="text-lg font-semibold mb-4">{{ \Carbon\Carbon::parse($date)->isoFormat('dddd D MMMM YYYY') }}</h2>
                                    
                                    <div class="bg-white rounded-lg shadow divide-y">
                                        @foreach($group as $notification)
                                            <div class="p-4 hover:bg-gray-50 transition {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <a href="{{ $notification->data['url'] ?? '#' }}" 
                                                           class="block notification-link"
                                                           data-notification-id="{{ $notification->id }}">
                                                            <p class="font-medium">{{ $notification->data['message'] }}</p>
                                                            <p class="text-sm text-gray-500 mt-1">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                                @isset($notification->data['sender'])
                                                                    • De: {{ $notification->data['sender'] }}
                                                                @endisset
                                                            </p>
                                                        </a>
                                                    </div>
                                                    
                                                    @if(is_null($notification->read_at))
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Nouveau
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($notifications->isEmpty())
                                <div class="bg-white rounded-lg shadow p-8 text-center">
                                    <p class="text-gray-500">Vous n'avez aucune notification</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

     <script>
        // Toggle mobile menu
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });

        // Toggle settings menu
        document.getElementById('settings-toggle').addEventListener('click', function(e) {
            e.preventDefault();
            const menu = document.getElementById('settings-menu');
            const arrow = document.getElementById('settings-arrow');
            
            menu.classList.toggle('open');
            arrow.classList.toggle('rotate-180');
        });

        // Gestion des clics sur les notifications
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.notification-link').forEach(link => {
                link.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const url = this.href;
                    const notificationId = this.dataset.notificationId;
                    
                    try {
                        // Marque comme lu en arrière-plan
                        await fetch(`/notifications/${notificationId}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        
                        // Redirection après marquage comme lu
                        window.location.href = url;
                    } catch (error) {
                        console.error('Error:', error);
                        window.location.href = url; // Redirige même en cas d'erreur
                    }
                });
            });
        });
    </script>
@endsection