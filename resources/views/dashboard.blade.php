
                @if(auth()->user()->role === 'admin')
                <x-app-layout>
                    <x-slot name="header">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Tableau de bord administrateur
                        </h2>
                    </x-slot>
                
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <!-- Section Statut -->
                            <div class="bg-white shadow-sm sm:rounded-lg mb-6 p-6 border-b border-gray-200">
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <div>
                                        <p class="text-gray-800 font-medium">Connecté en tant qu'administrateur</p>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Section Gestion Utilisateurs -->
                            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Gérer vos utilisateurs</h3>
                                
                                <div class="mt-5">
                                    <a href="{{ route('admin.users.index') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-medium text-white hover:bg-gray-700 focus:outline-none transition">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                        Accéder au panneau de gestion
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-app-layout>  
            @endif

            
            @if(auth()->user()->role === 'etudiant')
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
                                 <div class="p-4 bg-blue-100">
                                    <p>Vous êtes étudiant</p>
                                       <!-- Vos éléments admin ici -->
                                 </div>
                      </div>
                  </div>
             </div>
</x-app-layout>
@endif