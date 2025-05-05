<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
                @if(auth()->user()->role === 'admin')
                <div class="p-4 bg-blue-100">
                    <p>Vous êtes administrateur</p>
                    <!-- Vos éléments admin ici -->
                </div>
            @endif
            @if(auth()->user()->role === 'etudiant')
            <div class="p-4 bg-blue-100">
                <p>Vous êtes étudiant</p>
                <!-- Vos éléments admin ici -->
            </div>
        @endif
            </div>
        </div>
    </div>
</x-app-layout>
