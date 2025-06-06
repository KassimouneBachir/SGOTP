<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - CampusLost</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .form-card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md mx-auto">
        <!-- En-tête -->
        <div class="text-center mb-8">
            <div class="flex justify-center">
                <svg class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mt-2">CampusLost</h1>
            <p class="mt-2 text-gray-600">Connectez-vous à votre compte</p>
            
            <!-- Message de statut -->
            @if(session('status'))
                <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-md">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <!-- Carte du formulaire -->
        <div class="bg-white form-card rounded-lg p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                    <input id="email" name="email" type="email" required autofocus
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input id="password" name="password" type="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    @error('password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Se souvenir de moi -->
                <div class="flex items-center justify-between">
                    <label for="remember" class="flex items-center">
                        <input id="remember" type="checkbox" name="remember" 
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <!-- Bouton de connexion -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Se connecter
                    </button>
                </div>

                <!-- Lien vers l'inscription -->
                <div class="text-center text-sm text-gray-600 pt-2">
                    Pas encore de compte ? 
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                        S'inscrire
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>