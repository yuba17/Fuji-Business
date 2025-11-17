<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use Livewire\Component;

class TagFilter extends Component
{
    public $selectedTags = [];
    public $category = '';
    public $showAll = false;

    protected $listeners = ['tagSelected', 'tagDeselected'];

    public function mount($selectedTags = [], $category = '')
    {
        $this->selectedTags = $selectedTags;
        $this->category = $category;
    }

    public function toggleTag($tagId)
    {
        if (in_array($tagId, $this->selectedTags)) {
            $this->selectedTags = array_values(array_diff($this->selectedTags, [$tagId]));
            $this->dispatch('tagDeselected', $tagId);
        } else {
            $this->selectedTags[] = $tagId;
            $this->dispatch('tagSelected', $tagId);
        }

        $this->dispatch('tagsUpdated', $this->selectedTags);
    }

    public function clearTags()
    {
        $this->selectedTags = [];
        $this->dispatch('tagsUpdated', []);
    }

    public function tagSelected($tagId)
    {
        if (!in_array($tagId, $this->selectedTags)) {
            $this->selectedTags[] = $tagId;
        }
    }

    public function tagDeselected($tagId)
    {
        $this->selectedTags = array_values(array_diff($this->selectedTags, [$tagId]));
    }

    public function render()
    {
        $query = Tag::query();

        if ($this->category) {
            $query->where('category', $this->category);
        }

        $tags = $query->orderBy('usage_count', 'desc')
            ->orderBy('name')
            ->limit($this->showAll ? 100 : 20)
            ->get();

        return view('livewire.tags.tag-filter', compact('tags'));
    }
}
