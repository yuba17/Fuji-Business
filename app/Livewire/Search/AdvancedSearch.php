<?php

namespace App\Livewire\Search;

use App\Models\Tag;
use App\Models\Area;
use App\Services\SearchService;
use Livewire\Component;

class AdvancedSearch extends Component
{
    public $query = '';
    public $selectedTypes = ['plans', 'tasks', 'risks', 'decisions'];
    public $selectedTags = [];
    public $status = '';
    public $category = '';
    public $areaId = '';
    public $planId = '';

    public $results = [];
    public $isSearching = false;

    protected $listeners = ['performSearch'];

    public function performSearch()
    {
        $this->isSearching = true;
        
        $filters = [];
        
        if ($this->status) {
            $filters['status'] = $this->status;
        }
        
        if ($this->category) {
            $filters['category'] = $this->category;
        }
        
        if ($this->areaId) {
            $filters['area_id'] = $this->areaId;
        }
        
        if ($this->planId) {
            $filters['plan_id'] = $this->planId;
        }
        
        if (!empty($this->selectedTags)) {
            $filters['tags'] = $this->selectedTags;
        }

        $searchService = app(SearchService::class);
        $allResults = $searchService->globalSearch($this->query, $filters);

        // Filtrar por tipos seleccionados
        $this->results = [];
        foreach ($this->selectedTypes as $type) {
            if (isset($allResults[$type])) {
                $this->results[$type] = $allResults[$type];
            }
        }

        $this->isSearching = false;
    }

    public function clearFilters()
    {
        $this->reset(['query', 'selectedTags', 'status', 'category', 'areaId', 'planId', 'results']);
        $this->selectedTypes = ['plans', 'tasks', 'risks', 'decisions'];
    }

    public function render()
    {
        $tags = Tag::orderBy('name')->get();
        $areas = Area::orderBy('name')->get();

        return view('livewire.search.advanced-search', compact('tags', 'areas'));
    }
}
