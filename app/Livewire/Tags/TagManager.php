<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use App\Services\TagService;
use Livewire\Component;
use Livewire\WithPagination;

class TagManager extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $showCreateModal = false;
    public $editingTag = null;
    
    public $name = '';
    public $color = '';
    public $tagCategory = '';

    protected $queryString = ['search', 'category'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->reset(['name', 'color', 'tagCategory', 'editingTag']);
        $this->color = $this->generateRandomColor();
        $this->showCreateModal = true;
    }

    public function openEditModal(Tag $tag)
    {
        $this->editingTag = $tag;
        $this->name = $tag->name;
        $this->color = $tag->color ?? $this->generateRandomColor();
        $this->tagCategory = $tag->category ?? '';
        $this->showCreateModal = true;
    }

    public function saveTag(TagService $tagService)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'tagCategory' => 'nullable|in:domain,priority,status,type',
        ]);

        if ($this->editingTag) {
            $this->editingTag->update([
                'name' => $this->name,
                'color' => $this->color,
                'category' => $this->tagCategory,
            ]);
            session()->flash('success', 'Tag actualizado correctamente.');
        } else {
            $tagService->findOrCreate($this->name, $this->color, $this->tagCategory);
            session()->flash('success', 'Tag creado correctamente.');
        }

        $this->showCreateModal = false;
        $this->reset(['name', 'color', 'tagCategory', 'editingTag']);
    }

    public function deleteTag(Tag $tag)
    {
        $tag->delete();
        session()->flash('success', 'Tag eliminado correctamente.');
    }

    protected function generateRandomColor(): string
    {
        $colors = [
            '#EF4444', '#F97316', '#F59E0B', '#EAB308',
            '#84CC16', '#22C55E', '#10B981', '#14B8A6',
            '#06B6D4', '#0EA5E9', '#3B82F6', '#6366F1',
            '#8B5CF6', '#A855F7', '#D946EF', '#EC4899',
        ];
        
        return $colors[array_rand($colors)];
    }

    public function render()
    {
        $query = Tag::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        $tags = $query->orderBy('usage_count', 'desc')
            ->orderBy('name')
            ->paginate(20);

        return view('livewire.tags.tag-manager', compact('tags'));
    }
}
