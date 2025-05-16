<div class="w-64 bg-blue-800 text-white min-h-screen p-4">
    <h2 class="text-2xl font-bold mb-8">Menu Admin</h2>
    
    <nav>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="block p-2 hover:bg-blue-700 rounded">
                    📊 Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}" class="block p-2 hover:bg-blue-700 rounded">
                    👥 Gestion Utilisateurs
                </a>
            </li>
            <li>
                <a href="{{ route('admin.objects') }}" class="block p-2 hover:bg-blue-700 rounded">
                    🎒 Objets Perdus
                </a>
            </li>
            <!-- Ajoutez d'autres liens ici -->
        </ul>
    </nav>
</div>