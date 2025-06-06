<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'objet - CampusLost</title>
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
        <!-- Sidebar (identique à vos autres vues) -->
        <div class="sidebar w-64 bg-white shadow-sm hidden md:block">
            <!-- Votre sidebar existante -->
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar (identique) -->
            <header class="bg-white shadow-sm">
                <!-- Votre header existant -->
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Modifier l'objet</h1>
                            <p class="text-gray-600">Modifiez les détails de l'objet {{ $objet->nom }}</p>
                        </div>
                        <a href="{{ route('objets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            ← Retour à la liste
                        </a>
                    </div>

                    <!-- Formulaire d'édition -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
                        <form method="POST" action="{{ route('objets.update', $objet->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="p-6 space-y-6">
                                <!-- Champ Nom -->
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                                    <input type="text" name="nom" id="nom" value="{{ old('nom', $objet->nom) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Champ Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $objet->description) }}</textarea>
                                </div>

                                <!-- Champ Statut -->
                                <div>
                                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                                    <select name="statut" id="statut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="perdu" {{ $objet->statut === 'perdu' ? 'selected' : '' }}>Perdu</option>
                                        <option value="trouvé" {{ $objet->statut === 'trouvé' ? 'selected' : '' }}>Trouvé</option>
                                    </select>
                                </div>

                                <!-- Champ Lieu -->
                                <div>
                                    <label for="lieu" class="block text-sm font-medium text-gray-700">Lieu</label>
                                    <input type="text" name="lieu" id="lieu" value="{{ old('lieu', $objet->lieu) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Champ Date -->
                                <div>
                                    <label for="date_perte" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" name="date_perte" id="date_perte" value="{{ old('date_perte', $objet->date_perte->format('Y-m-d')) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Champ Photo -->
                                <div>
                                    <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
                                    <input type="file" name="photo" id="photo"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @if($objet->photo)
                                        <div class="mt-2">
                                            <span class="text-sm text-gray-500">Photo actuelle :</span>
                                            <img src="{{ $objet->photo_url }}" alt="Photo actuelle" class="h-20 mt-1">
                                        </div>
                                    @endif
                                </div>

                                <!-- Boutons -->
                                <div class="flex justify-end pt-6 border-t border-gray-200 space-x-3">
                                    <a href="{{ route('objets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Annuler
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                        Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle mobile menu (identique à vos autres vues)
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });
    </script>
</body>
</html>