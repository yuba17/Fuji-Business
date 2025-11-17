<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Str;

class TagService
{
    /**
     * Crear o encontrar un tag
     */
    public function findOrCreate(string $name, ?string $color = null, ?string $category = null): Tag
    {
        $slug = Str::slug($name);
        
        $tag = Tag::where('slug', $slug)->first();
        
        if (!$tag) {
            $tag = Tag::create([
                'name' => $name,
                'slug' => $slug,
                'color' => $color ?? $this->generateColor(),
                'category' => $category,
                'is_predefined' => false,
                'usage_count' => 0,
            ]);
        }
        
        return $tag;
    }

    /**
     * Asociar tags a un modelo
     */
    public function attachTags($model, array $tagIds): void
    {
        $model->tags()->sync($tagIds);
        $this->updateUsageCounts($tagIds);
    }

    /**
     * Desasociar tags de un modelo
     */
    public function detachTags($model, array $tagIds): void
    {
        $model->tags()->detach($tagIds);
        $this->updateUsageCounts($tagIds);
    }

    /**
     * Actualizar contador de uso de tags
     */
    protected function updateUsageCounts(array $tagIds): void
    {
        foreach ($tagIds as $tagId) {
            $tag = Tag::find($tagId);
            if ($tag) {
                $tag->update([
                    'usage_count' => $tag->plans()->count() + 
                                    $tag->tasks()->count() + 
                                    $tag->risks()->count() + 
                                    $tag->decisions()->count()
                ]);
            }
        }
    }

    /**
     * Generar color aleatorio para tag
     */
    protected function generateColor(): string
    {
        $colors = [
            '#EF4444', '#F97316', '#F59E0B', '#EAB308',
            '#84CC16', '#22C55E', '#10B981', '#14B8A6',
            '#06B6D4', '#0EA5E9', '#3B82F6', '#6366F1',
            '#8B5CF6', '#A855F7', '#D946EF', '#EC4899',
        ];
        
        return $colors[array_rand($colors)];
    }

    /**
     * Obtener tags más usados
     */
    public function getPopularTags(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Tag::orderBy('usage_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtener tags por categoría
     */
    public function getTagsByCategory(?string $category = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Tag::query();
        
        if ($category) {
            $query->where('category', $category);
        }
        
        return $query->orderBy('name')->get();
    }

    /**
     * Buscar tags por nombre
     */
    public function searchTags(string $query, int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        return Tag::where('name', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->orderBy('usage_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
