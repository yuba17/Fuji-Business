<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
function certificationCropperModal() {
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
            const imageId = 'cropper-certification-image';
            const image = document.getElementById(imageId);
            
            if (image && !this.cropper) {
                // Destruir cropper anterior si existe
                if (this.cropper) {
                    this.cropper.destroy();
                }
                
                this.cropper = new Cropper(image, {
                    aspectRatio: 1, // Cuadrado para logos
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
                    minCropBoxWidth: 100,
                    minCropBoxHeight: 100,
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
                width: 512,
                height: 512,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            
            const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
            
            @this.cropCertificationImage(croppedDataUrl);
            
            // Esperar un momento y luego guardar
            setTimeout(() => {
                @this.saveCroppedCertificationImage();
                this.closeModal();
            }, 100);
        },
        
        closeModal() {
            this.destroyCropper();
            @this.cancelCertificationCrop();
        }
    }
}
</script>


