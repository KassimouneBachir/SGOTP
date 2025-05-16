<tr class="border-b hover:bg-gray-50">
    <td class="px-6 py-4">{{ $user->name }}</td>
    <td class="px-6 py-4">{{ $user->email }}</td>
    <td class="px-6 py-4">
        <span class="px-2 py-1 bg-{{ $user->role === 'admin' ? 'blue' : 'green' }}-100 text-{{ $user->role === 'admin' ? 'blue' : 'green' }}-800 rounded-full text-xs">
            {{ $user->role }}
        </span>
    </td>

    <td class="px-6 py-4">
        <form action="{{ route('admin.users.change-role', $user) }}" method="POST">
            @csrf
            <button type="submit" class="text-blue-600 hover:text-blue-900 mr-4">
                {{ $user->role === 'admin' ? 'Rétrograder' : 'Promouvoir Admin' }}
            </button>
        </form>
    </td>

    <td class="px-6 py-4">
        <form action="{{ route('admin.users.delete', $user) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Confirmer la suppression ?')">
                Supprimer
            </button>
        </form>
    </td>
    
</tr>