<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AvatarUploader extends Component
{
    use WithFileUploads;

    public ?int $userId = null;
    public User $user;
    public $image = null;
    public $croppedImage = null;
    public $showCropper = false;
    public $cropData = null;

    protected $listeners = ['avatar-updated' => '$refresh'];

    public function mount(?int $userId = null)
    {
        $this->userId = $userId ?? Auth::id();
        $this->user = User::findOrFail($this->userId);
    }

    public function updatedImage()
    {
        try {
            $this->validate([
                'image' => 'required|image|max:5120', // 5MB max
            ], [
                'image.required' => 'Por favor selecciona una imagen.',
                'image.image' => 'El archivo debe ser una imagen.',
                'image.max' => 'La imagen no debe superar los 5MB.',
            ]);
            
            // Asegurar que el directorio existe
            if (!Storage::disk('public')->exists('avatars')) {
                Storage::disk('public')->makeDirectory('avatars');
            }
            
            if ($this->image) {
                $this->showCropper = true;
                $this->dispatch('image-uploaded');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', $e->getMessage());
            $this->reset('image');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al subir la imagen: ' . $e->getMessage());
            $this->reset('image');
        }
    }

    public function cropImage($cropData)
    {
        $this->cropData = $cropData;
    }

    public function saveCroppedImage()
    {
        if (!$this->cropData || !$this->image) {
            session()->flash('error', 'No hay imagen para guardar.');
            return;
        }

        try {
            // Decodificar los datos del crop (base64)
            $imageData = explode(',', $this->cropData);
            $imageData = base64_decode($imageData[1] ?? $imageData[0]);
            
            // Asegurar que el directorio existe
            if (!Storage::disk('public')->exists('avatars')) {
                Storage::disk('public')->makeDirectory('avatars');
            }
            
            // Generar nombre único para el archivo
            $filename = 'avatars/' . $this->user->id . '_' . time() . '.jpg';
            
            // Guardar en storage público
            Storage::disk('public')->put($filename, $imageData);
            
            // Eliminar avatar anterior si existe
            if ($this->user->avatar_url) {
                $oldPath = str_replace('/storage/', '', parse_url($this->user->avatar_url, PHP_URL_PATH));
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            // Actualizar URL del avatar en el usuario
            $this->user->update([
                'avatar_url' => asset('storage/' . $filename)
            ]);
            
            $this->reset(['image', 'croppedImage', 'showCropper', 'cropData']);
            $this->user->refresh();
            
            session()->flash('message', 'Avatar actualizado correctamente.');
            $this->dispatch('avatar-updated');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el avatar: ' . $e->getMessage());
        }
    }

    public function cancelCrop()
    {
        $this->reset(['image', 'croppedImage', 'showCropper', 'cropData']);
    }

    public function deleteAvatar()
    {
        if ($this->user->avatar_url) {
            $path = str_replace('/storage/', '', parse_url($this->user->avatar_url, PHP_URL_PATH));
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            
            $this->user->update(['avatar_url' => null]);
            $this->user->refresh();
            
            session()->flash('message', 'Avatar eliminado correctamente.');
            $this->dispatch('avatar-updated');
        }
    }

    public function render()
    {
        return view('livewire.profile.avatar-uploader');
    }
}
