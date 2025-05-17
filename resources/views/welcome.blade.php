<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusLost - Objets perdus/trouvés</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'));
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-800">CampusLost</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600">Connexion</a>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Inscription</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-black py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-6">Retrouvez vos objets perdus sur le campus</h1>
            <p class="text-xl mb-8">La solidarité numérique du campus</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#" class="px-6 py-3 bg-blue-600 rounded-md font-medium hover:bg-blue-700 transition">Signaler un objet</a>
                <a href="#" class="px-6 py-3 bg-white text-gray-800 rounded-md font-medium hover:bg-gray-100 transition">Objets trouvés</a>
            </div>
        </div>
    </section>

    <!-- Fonctionnalités -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Comment ça marche ?</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Étape 1 -->
                <div class="text-center p-6 rounded-lg">
                    <div class="bg-blue-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                        <span class="text-blue-600 text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Signalez</h3>
                    <p class="text-gray-600">Déclarez un objet perdu ou trouvé en quelques clics</p>
                </div>
                
                <!-- Étape 2 -->
                <div class="text-center p-6 rounded-lg">
                    <div class="bg-blue-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                        <span class="text-blue-600 text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Recherchez</h3>
                    <p class="text-gray-600">Trouvez des correspondances avec notre système</p>
                </div>
                
                <!-- Étape 3 -->
                <div class="text-center p-6 rounded-lg">
                    <div class="bg-blue-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                        <span class="text-blue-600 text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Contactez</h3>
                    <p class="text-gray-600">Échangez en toute sécurité avec les utilisateurs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Objets récents -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Objets récemment trouvés</h2>
            
            <div class="grid sm:grid-cols-2 gap-6">
                <!-- Objet 1 -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">Image de l'objet</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg">Portefeuille noir</h3>
                        <p class="text-gray-600">Trouvé près de la bibliothèque</p>
                        <p class="text-sm text-gray-500 mt-2">Il y a 2 jours</p>
                    </div>
                </div>
                
                <!-- Objet 2 -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">Image de l'objet</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg">Clés USB</h3>
                        <p class="text-gray-600">Trouvé en salle B204</p>
                        <p class="text-sm text-gray-500 mt-2">Il y a 5 jours</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-blue-600 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Prêt à retrouver vos affaires ?</h2>
            <p class="text-xl mb-8">Rejoignez la communauté solidaire du campus</p>
            <a href="#" class="inline-block px-8 py-3 bg-white text-blue-600 rounded-lg font-medium hover:bg-gray-100 transition">S'inscrire gratuitement</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <span class="text-xl font-bold">CampusLost</span>
                    <p class="text-gray-400 mt-1">La solidarité numérique du campus</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-blue-300">Mentions légales</a>
                    <a href="#" class="hover:text-blue-300">Contact</a>
                    <a href="#" class="hover:text-blue-300">FAQ</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>