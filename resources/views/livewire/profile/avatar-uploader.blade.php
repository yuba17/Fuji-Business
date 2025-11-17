<div class="space-y-4" wire:key="avatar-uploader-{{ $user->id }}">
    <!-- Avatar actual -->
    <div class="flex items-center gap-6">
        <div class="relative">
            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center text-3xl font-bold text-white border-4 border-white shadow-lg overflow-hidden">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}?v={{ time() }}" alt="{{ $user->name }}" 
                         class="w-full h-full object-cover" 
                         id="current-avatar">
                @else
                    {{ $user->initials() }}
                @endif
            </div>
        </div>
        <div class="flex-1">
            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $user->name }}</h3>
            <p class="text-sm text-gray-600 mb-4">{{ $user->email }}</p>
            <div class="flex items-center gap-3">
                <input type="file" 
                       wire:model="image" 
                       accept="image/*" 
                       id="avatar-upload-{{ $user->id }}"
                       class="hidden">
                <label for="avatar-upload-{{ $user->id }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl cursor-pointer">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span wire:loading.remove wire:target="image">
                        @if($user->avatar_url)
                            Cambiar foto
                        @else
                            Subir foto
                        @endif
                    </span>
                    <span wire:loading wire:target="image" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Subiendo...
                    </span>
                </label>
                @if($user->avatar_url)
                <button wire:click="deleteAvatar" 
                        onclick="return confirm('¿Eliminar foto de perfil?')"
                        class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Eliminar
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    @if(session('message'))
    <div class="bg-green-50 border-l-4 border-green-400 rounded-r-lg p-4">
        <p class="text-sm text-green-800">{{ session('message') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-400 rounded-r-lg p-4">
        <p class="text-sm text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Modal de Cropper -->
    @if($showCropper && $image)
    <div x-data="avatarCropperModal()" 
         x-show="true"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="closeModal()"
         class="fixed inset-0 z-50 bg-gray-900/60 flex items-center justify-center p-4"
         style="display: block;"
         wire:ignore>
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="closeModal()"
             class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            
            <!-- Header del Modal -->
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">Recorta tu imagen</h3>
                <button @click="closeModal()" 
                        class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Ajusta el recuadro para encuadrar tu avatar perfectamente.</p>
                
                <div class="mb-6" style="max-height: 500px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                    <img id="cropper-image-{{ $user->id }}" 
                         src="{{ $image->temporaryUrl() }}" 
                         alt="Imagen a recortar"
                         style="max-width: 100%; max-height: 500px; display: block;">
                </div>
            </div>

            <!-- Footer del Modal -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                <button @click="closeModal()" 
                        wire:click="cancelCrop"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                    Cancelar
                </button>
                <button @click="cropAndSave()" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg">
                    Guardar avatar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
function avatarCropperModal() {
    return {
        cropper: null,
        
        init() {
            // Esperar a que la imagen esté cargada
            this.$nextTick(() => {
                setTimeout(() => {
                    this.initCropper();
                }, 300);
            });
        },
        
        initCropper() {
            const imageId = 'cropper-image-{{ $user->id }}';
            const image = document.getElementById(imageId);
            
            if (image && !this.cropper) {
                // Destruir cropper anterior si existe
                if (this.cropper) {
                    this.cropper.destroy();
                }
                
                this.cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            }
        },
        
        destroyCropper() {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },
        
        cropAndSave() {
            if (!this.cropper) {
                alert('El cropper no está inicializado. Por favor espera un momento.');
                return;
            }
            
            const canvas = this.cropper.getCroppedCanvas({
                width: 400,
                height: 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            
            const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
            
            @this.cropImage(croppedDataUrl);
            
            // Esperar un momento y luego guardar
            setTimeout(() => {
                @this.saveCroppedImage();
                this.closeModal();
            }, 100);
        },
        
        closeModal() {
            this.destroyCropper();
            @this.cancelCrop();
        }
    }
}
</script>
@endpush
