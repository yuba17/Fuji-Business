<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(
        protected SearchService $searchService
    ) {
        $this->middleware('auth');
    }

    /**
     * Búsqueda global
     */
    public function index(Request $request): View
    {
        $query = $request->get('q', '');
        $filters = $request->only(['status', 'category', 'area_id', 'plan_id', 'tags']);

        $results = [];

        if ($query) {
            $results = $this->searchService->globalSearch($query, $filters);
        }

        return view('search.index', compact('results', 'query', 'filters'));
    }

    /**
     * Búsqueda por tags
     */
    public function byTags(Request $request): View
    {
        $tagIds = $request->get('tags', []);
        $type = $request->get('type');

        $results = [];

        if (!empty($tagIds)) {
            $results = $this->searchService->searchByTags($tagIds, $type);
        }

        return view('search.by-tags', compact('results', 'tagIds', 'type'));
    }
}
