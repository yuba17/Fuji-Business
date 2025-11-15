<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskAttachment extends Model
{
    protected $fillable = [
        'task_id',
        'file_name',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'uploaded_by',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    /**
     * Tarea del adjunto
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Usuario que subió el archivo
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Obtener el tamaño formateado del archivo
     */
    public function getFormattedSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'Desconocido';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Obtener el icono según el tipo de archivo
     */
    public function getFileIconAttribute(): string
    {
        if (!$this->mime_type) {
            return '📄';
        }

        if (str_starts_with($this->mime_type, 'image/')) {
            return '🖼️';
        } elseif (str_starts_with($this->mime_type, 'video/')) {
            return '🎥';
        } elseif (str_starts_with($this->mime_type, 'audio/')) {
            return '🎵';
        } elseif ($this->mime_type === 'application/pdf') {
            return '📕';
        } elseif (in_array($this->mime_type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            return '📘';
        } elseif (in_array($this->mime_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return '📗';
        } elseif (in_array($this->mime_type, ['application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])) {
            return '📙';
        } elseif (str_starts_with($this->mime_type, 'text/')) {
            return '📝';
        } elseif (str_contains($this->mime_type, 'zip') || str_contains($this->mime_type, 'archive')) {
            return '📦';
        }

        return '📄';
    }
}
