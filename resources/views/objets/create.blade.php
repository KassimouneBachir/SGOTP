@extends('layouts.app')

@section('page_title', 'Déclarer un objet')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Déclarer un objet
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('objets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>

        <div class="mt-8">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Informations de l'objet</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Veuillez fournir les détails de l'objet que vous souhaitez déclarer.
                        </p>
                    </div>
                </div>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form action="{{ route('objets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                <!-- Statut -->
                        <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Type de déclaration
                                    </label>
                                    <div class="mt-2 space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
                                        <div class="flex items-center">
                                            <input type="radio" name="statut" value="perdu" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" {{ old('statut') === 'perdu' ? 'checked' : '' }} required>
                                            <label class="ml-3 block text-sm font-medium text-gray-700">
                                                Objet perdu
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" name="statut" value="trouvé" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" {{ old('statut') === 'trouvé' ? 'checked' : '' }}>
                                            <label class="ml-3 block text-sm font-medium text-gray-700">
                                                Objet trouvé
                                            </label>
                        </div>
                    </div>
                                    @error('statut')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                            </div>

                            <!-- Nom -->
                            <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700">
                                        Nom de l'objet
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" 
                                               name="nom" 
                                               id="nom" 
                                               value="{{ old('nom') }}"
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               required>
                                    </div>
                                    @error('nom')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">
                                        Description détaillée
                                    </label>
                                    <div class="mt-1">
                                        <textarea name="description" 
                                                  id="description" 
                                                  rows="4"
                                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                  required>{{ old('description') }}</textarea>
                                    </div>
                                    @error('description')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                            </div>

                            <!-- Lieu -->
                            <div>
                                    <label for="lieu" class="block text-sm font-medium text-gray-700">
                                        Lieu
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" 
                                               name="lieu" 
                                               id="lieu" 
                                               value="{{ old('lieu') }}"
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               required>
                                    </div>
                                    @error('lieu')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                            </div>

                            <!-- Date -->
                            <div>
                                    <label for="date_perte" class="block text-sm font-medium text-gray-700">
                                        Date
                                    </label>
                                    <div class="mt-1">
                                        <input type="date" 
                                               name="date_perte" 
                                               id="date_perte" 
                                               value="{{ old('date_perte') }}"
                                               max="{{ date('Y-m-d') }}"
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               required>
                            </div>
                                    @error('date_perte')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                            </div>

                            <!-- Photo -->
                            <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Photo de l'objet
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Télécharger une photo</span>
                                                    <input id="photo" name="photo" type="file" class="hidden" accept="image/*" onchange="previewImage(this)">
                                                </label>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                PNG, JPG, GIF jusqu'à 2MB
                                            </p>
                                            <div id="image-preview" class="mt-2 hidden">
                                                <img src="#" alt="Aperçu" class="mx-auto h-24 w-24 object-cover rounded-md">
                                            </div>
                                        </div>
                                    </div>
                                    @error('photo')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                            </div>

                            <script>
                                function previewImage(input) {
                                    const preview = document.getElementById('image-preview');
                                    const previewImg = preview.querySelector('img');
                                    
                                    if (input.files && input.files[0]) {
                                        const reader = new FileReader();
                                        
                                        reader.onload = function(e) {
                                            previewImg.src = e.target.result;
                                            preview.classList.remove('hidden');
                                        }
                                        
                                        reader.readAsDataURL(input.files[0]);
                                    } else {
                                        previewImg.src = '#';
                                        preview.classList.add('hidden');
                                    }
                                }
                            </script>

                            <!-- Détails spécifiques -->
                            <div x-data="{ details: [] }">
                                <label class="block text-sm font-medium text-gray-700">
                                    Détails spécifiques (optionnel)
                                </label>
                                <p class="mt-1 text-sm text-gray-500">
                                    Ajoutez des détails spécifiques qui permettront d'identifier l'objet.
                                </p>
                                <div class="mt-2 space-y-2">
                                    <template x-for="(detail, index) in details" :key="index">
                                        <div class="flex items-center space-x-2">
                                            <input type="text" 
                                                   :name="'specific_details[' + index + ']'"
                                                   x-model="detail.value"
                                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                   placeholder="Ex: numéro de série, marque, couleur...">
                                            <button type="button" 
                                                    @click="details.splice(index, 1)"
                                                    class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button"
                                            @click="details.push({value: ''})"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Ajouter un détail
                                    </button>
                                </div>
                            </div>
                            </div>

                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Enregistrer
                                </button>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
        </div>
    </div>

@endsection