<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - CampusLost</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            transition: all 0.3s;
        }
        .active-nav {
            background-color: #EFF6FF;
            color: #1D4ED8;
            border-left: 4px solid #1D4ED8;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar identique au dashboard -->
        <div class="sidebar w-64 bg-white shadow-sm hidden md:block">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="text-xl font-bold">CampusLost</span>
                </div>
            </div>
            <nav class="p-4">
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-md">
                        Tableau de bord
                    </a>
                    <div>
                        <a href="#" id="settings-toggle" class="block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-md flex justify-between items-center">
                            Paramètres
                            <svg class="h-4 w-4 transform transition-transform" id="settings-arrow" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <div id="settings-menu" class="profile-section ml-4 pl-2 border-l-2 border-gray-200">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar identique au dashboard -->
            <header class="bg-white shadow-sm">
                <div class="px-4 py-4 flex items-center justify-between">
                    <div class="flex items-center md:hidden">
                        <button id="menu-toggle" class="text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative group">
                            <div class="flex items-center space-x-2 cursor-pointer">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                </div>
                                <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                                <svg class="h-4 w-4 text-gray-500 group-hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Contenu spécifique au profil -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto space-y-6">
                    <!-- Section Informations Profil -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-800">Informations du profil</h2>
                            @if (session('status') === 'profile-updated')
                            <div class="text-green-600 text-sm">
                                {{ __('Saved.') }}
                            </div>
                            @endif
                        </div>
                        
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                            @csrf
                            @method('patch')

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2 text-sm text-gray-600">
                                    {{ __('Your email address is unverified.') }}
                                    <form method="post" action="{{ route('verification.send') }}">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-500">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </form>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-1 text-green-600">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Sauvegarder
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Section Mot de passe -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Modifier le mot de passe</h2>
                        
                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')

                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                                <input id="current_password" name="current_password" type="password" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('current_password', 'updatePassword')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                                <input id="password" name="password" type="password" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('password', 'updatePassword')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Sauvegarder
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Section Suppression de compte -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Supprimer le compte</h2>
                        <p class="text-gray-600 mb-4">
                            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.
                        </p>

                        <button x-data @click="$dispatch('open-modal', 'confirm-deletion')" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Supprimer le compte
                        </button>

                        <!-- Modal -->
                        <div x-data="{ open: false }" x-show="open" @open-modal.window="open = true" @close-modal.window="open = false" 
                             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" style="display: none;">
                            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                                <h3 class="text-lg font-bold mb-4">Confirmer la suppression</h3>
                                <p class="mb-4">Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
                                
                                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                                    @csrf
                                    @method('delete')

                                    <div>
                                        <label for="delete_password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                        <input id="delete_password" name="password" type="password" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('password', 'userDeletion')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex justify-end space-x-3">
                                        <button type="button" @click="$dispatch('close-modal')" 
                                                class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                            Annuler
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                            Supprimer définitivement
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Toggle mobile menu
        document.getElementById('menu-toggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });
    </script>
</body>
</html>