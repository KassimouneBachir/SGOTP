<x-admin-layout title="Gestion des utilisateurs">

    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Gestion des Utilisateurs</h1>
        <form method="GET" class="flex">
            <input 
                type="text" 
                name="search" 
                placeholder="Rechercher..." 
                class="px-4 py-2 border rounded-l"
                value="{{ request('search') }}"
            >
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r">
                🔍
            </button>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Rôle</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @include('admin.users.partials.user-row', ['user' => $user])
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('admin.users.export') }}" class="bg-green-500 text-white px-4 py-2 rounded inline-block mb-4">
        📤 Exporter en CSV
    </a>
    {{ $users->links() }} {{-- Pagination --}}

</x-admin-layout>
