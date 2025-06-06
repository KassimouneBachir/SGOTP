<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusLost - Retrouvez vos objets perdus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-pattern {
            background-color: #1a365d;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%232d4ed8' fill-opacity='0.1'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full z-50" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900">CampusLost</span>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button @click="mobileMenu = !mobileMenu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <!-- Desktop menu -->
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Inscription
                    </a>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenu" class="sm:hidden" x-cloak>
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Connexion</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Inscription</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                    <span class="block">Retrouvez vos objets perdus</span>
                    <span class="block text-blue-400">sur le campus</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Une plateforme simple et efficace pour retrouver vos objets perdus ou aider les autres à retrouver les leurs.
                </p>
                <div class="mt-10 flex justify-center space-x-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-150">
                        Commencer
                        <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#fonctionnement" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 transition-colors duration-150">
                        En savoir plus
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <dl class="rounded-lg bg-white shadow-lg sm:grid sm:grid-cols-3">
                <div class="flex flex-col border-b border-gray-100 p-6 text-center sm:border-0 sm:border-r">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">Objets retrouvés</dt>
                    <dd class="order-1 text-5xl font-extrabold text-blue-600">92%</dd>
                </div>
                <div class="flex flex-col border-t border-b border-gray-100 p-6 text-center sm:border-0 sm:border-l sm:border-r">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">Utilisateurs actifs</dt>
                    <dd class="order-1 text-5xl font-extrabold text-blue-600">2.5k</dd>
                </div>
                <div class="flex flex-col border-t border-gray-100 p-6 text-center sm:border-0 sm:border-l">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">Temps moyen</dt>
                    <dd class="order-1 text-5xl font-extrabold text-blue-600">24h</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Comment ça marche -->
    <section id="fonctionnement" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Comment ça marche ?
                </h2>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                    Un processus simple en trois étapes pour retrouver vos objets
                </p>
            </div>

            <div class="mt-20">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="h-px w-full bg-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <div class="bg-blue-600 rounded-full h-16 w-16 flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-8 text-center">
                            <h3 class="text-xl font-medium text-gray-900">Déclarez</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Signalez rapidement un objet perdu ou trouvé avec une description détaillée et des photos
                            </p>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="h-px w-full bg-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <div class="bg-blue-600 rounded-full h-16 w-16 flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-8 text-center">
                            <h3 class="text-xl font-medium text-gray-900">Recherchez</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Notre système intelligent trouve automatiquement les correspondances potentielles
                            </p>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="relative flex justify-center">
                            <div class="bg-blue-600 rounded-full h-16 w-16 flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-8 text-center">
                            <h3 class="text-xl font-medium text-gray-900">Contactez</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Échangez en toute sécurité via notre messagerie intégrée
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Témoignages -->
    <section class="bg-gray-50 py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Ce qu'en disent nos utilisateurs
                </h2>
            </div>
            <div class="mt-16">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-blue-600">S</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900">Sarah M.</h4>
                                <p class="text-gray-500">Étudiante en L3</p>
                            </div>
                        </div>
                        <p class="mt-6 text-gray-600">
                            "J'ai retrouvé mon ordinateur portable en moins de 24h grâce à CampusLost. Le système est vraiment efficace !"
                        </p>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-blue-600">T</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900">Thomas L.</h4>
                                <p class="text-gray-500">Enseignant</p>
                            </div>
                        </div>
                        <p class="mt-6 text-gray-600">
                            "Une plateforme indispensable pour notre campus. Simple à utiliser et très réactive."
                        </p>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-blue-600">L</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900">Lisa K.</h4>
                                <p class="text-gray-500">Étudiante en M1</p>
                            </div>
                        </div>
                        <p class="mt-6 text-gray-600">
                            "Grâce à CampusLost, j'ai pu rendre plusieurs objets trouvés à leurs propriétaires. C'est gratifiant !"
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-blue-600">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="bg-blue-700 rounded-lg shadow-xl overflow-hidden">
                <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-16 lg:py-16 lg:pr-0 xl:py-20 xl:px-20">
                    <div class="lg:self-center">
                        <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                            <span class="block">Prêt à retrouver</span>
                            <span class="block">vos objets perdus ?</span>
                        </h2>
                        <p class="mt-4 text-lg leading-6 text-blue-200">
                            Rejoignez la communauté CampusLost et bénéficiez de notre système de recherche intelligent.
                        </p>
                        <a href="{{ route('register') }}" class="mt-8 bg-white border border-transparent rounded-md shadow px-6 py-3 inline-flex items-center text-base font-medium text-blue-600 hover:bg-gray-50 transition-colors duration-150">
                            Créer un compte gratuit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-white">CampusLost</span>
                    </div>
                    <p class="mt-4 text-base text-gray-400">
                        La solution simple et efficace pour retrouver vos objets perdus sur le campus.
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
                        Liens utiles
                    </h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">
                                À propos
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">
                                Comment ça marche
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">
                                FAQ
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">
                        Légal
                    </h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">
                                Confidentialité
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">
                                Conditions d'utilisation
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-300 hover:text-white">
                                Mentions légales
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8">
                <p class="text-base text-gray-400 text-center">
                    &copy; {{ date('Y') }} CampusLost. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>