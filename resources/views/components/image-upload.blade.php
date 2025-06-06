@props(['name' => 'photo', 'label' => 'Photo', 'value' => null, 'required' => false])

<div x-data="imageUpload('{{ $value }}')" class="space-y-2">
    <label class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <div class="mt-1 flex items-center space-x-4">
        <!-- Preview -->
        <div class="flex-shrink-0 h-32 w-32 bg-gray-100 rounded-lg overflow-hidden">
            <template x-if="imageUrl">
                <img :src="imageUrl" 
                     class="h-full w-full object-cover"
                     @error="handleImageError">
            </template>
            <template x-if="!imageUrl">
                <div class="h-full w-full flex items-center justify-center text-gray-400">
                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </template>
        </div>

        <!-- Upload button -->
        <div class="flex-grow">
            <input type="file" 
                   :id="'{{ $name }}'"
                   name="{{ $name }}"
                   class="hidden"
                   accept="image/*"
                   @change="handleImageSelected">
            
            <label :for="'{{ $name }}'" 
                   class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Choisir une image
            </label>

            <p class="mt-2 text-sm text-gray-500">
                PNG, JPG jusqu'à 5MB
            </p>
        </div>
    </div>

    <!-- Error message -->
    <p x-show="error" 
       x-text="error"
       class="mt-2 text-sm text-red-600"></p>
</div>

@push('scripts')
<script>
function imageUpload(initialUrl = null) {
    return {
        imageUrl: initialUrl,
        error: null,

        handleImageSelected(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validation
            if (!file.type.startsWith('image/')) {
                this.error = 'Le fichier doit être une image';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                this.error = 'L\'image ne doit pas dépasser 5MB';
                return;
            }

            this.error = null;
            this.imageUrl = URL.createObjectURL(file);
        },

        handleImageError() {
            this.imageUrl = null;
            this.error = 'Impossible de charger l\'image';
        }
    }
}
</script>
@endpush 