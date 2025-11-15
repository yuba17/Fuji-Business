<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskComment extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
        'mentioned_user_ids',
        'parent_comment_id',
    ];

    protected function casts(): array
    {
        return [
            'mentioned_user_ids' => 'array',
        ];
    }

    /**
     * Tarea del comentario
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Usuario que hizo el comentario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Comentario padre (si es respuesta)
     */
    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(TaskComment::class, 'parent_comment_id');
    }

    /**
     * Respuestas al comentario
     */
    public function replies(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'parent_comment_id');
    }

    /**
     * Usuarios mencionados
     */
    public function mentionedUsers()
    {
        if (!$this->mentioned_user_ids || empty($this->mentioned_user_ids)) {
            return collect();
        }

        return User::whereIn('id', $this->mentioned_user_ids)->get();
    }

    /**
     * Procesar el texto del comentario para extraer menciones
     */
    public static function extractMentions(string $comment): array
    {
        preg_match_all('/@(\w+)/', $comment, $matches);
        return $matches[1] ?? [];
    }

    /**
     * Formatear el comentario con enlaces a menciones
     */
    public function getFormattedCommentAttribute(): string
    {
        $comment = $this->comment;
        $mentionedUsers = $this->mentionedUsers();

        foreach ($mentionedUsers as $user) {
            $comment = str_replace(
                '@' . $user->name,
                '<span class="font-semibold text-blue-600">@' . $user->name . '</span>',
                $comment
            );
        }

        return nl2br(e($comment));
    }
}
