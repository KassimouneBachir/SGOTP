<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails objet - CampusLost</title>
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
        

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
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
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Détails de l'objet</h1>
                            <p class="text-gray-600">Informations complètes sur l'objet</p>
                        </div>
                        <a href="{{ route('objets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            ← Retour à la liste
                        </a>
                    </div>

                    <!-- Card principale -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
                        <!-- En-tête avec statut -->
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-800">{{ $objet->nom }}</h2>
                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                  {{ $objet->statut === 'perdu' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($objet->statut) }}
                            </span>
                        </div>

                        <!-- Contenu -->
                        <div class="md:flex">
                            <!-- Photo -->
                            <div class="md:w-1/3 p-6 flex items-center justify-center bg-gray-50">
                                <img src="{{ $objet->photo_url }}" alt="{{ $objet->nom }}" class="max-h-96 w-full object-contain rounded">
                            </div>
                            
                            <!-- Détails -->
                            <div class="md:w-2/3 p-6">
                                <div class="space-y-6">
                                    <!-- Informations de base -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-500">Lieu</h3>
                                            <p class="mt-1 text-sm text-gray-900">{{ $objet->lieu }}</p>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-500">Date de perte (ou Trouver)</h3>
                                            <p class="mt-1 text-sm text-gray-900">{{ $objet->date_perte->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-500">Déposé par</h3>
                                            <p class="mt-1 text-sm text-gray-900">{{ $objet->user->name }}</p>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-500">Déposé le</h3>
                                            <p class="mt-1 text-sm text-gray-900">{{ $objet->created_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Description</h3>
                                        <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded">
                                            {{ nl2br(e($objet->description)) }}
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <!-- Ajoutez ce bouton dans la section des actions -->
                                    @if($objet->statut === 'trouvé' && $objet->user_id !== auth()->id())
                                        <a href="{{ route('chat.start', ['user_id' => $objet->user_id]) }}" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                        Contacter la personne
                                        </a>
                                    @endif
                                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                        @can('update', $objet)
                                        <a href="{{ route('objets.edit', $objet) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600">
                                            Modifier
                                        </a>
                                        @endcan

                                        @can('delete', $objet)
                                        <form action="{{ route('objets.destroy', $objet) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700"
                                                    onclick="return confirm('Confirmer la suppression ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($objet->statut === 'trouvé' && auth()->id() !== $objet->user_id)
<div class="mt-8 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
    <h3 class="text-lg font-medium mb-4">Cet objet m'appartient</h3>
    <form action="{{ route('objets.claim', $objet) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @if(!empty($objet->details_specifiques))
        <div class="mb-6">
            <h4 class="font-medium mb-3">Veuillez répondre à ces questions :</h4>
            @foreach($objet->details_specifiques as $index => $detail)
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $detail }}</label>
                <input type="text" name="answers[{{ $index }}]" required class="w-full rounded-md border-gray-300 shadow-sm">
            </div>
            @endforeach
        </div>
        @endif

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description détaillée *</label>
            <textarea name="description" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Preuve de propriété</label>
            <input type="file" name="proof" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Soumettre la réclamation</button>
    </form>
</div>
@endif
            </main>
        </div>
    </div>

    <script>
        // Toggle mobile menu
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });
    </script>
</body>
</html>