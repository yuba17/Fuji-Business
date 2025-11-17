<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TagController extends Controller
{
    public function __construct(
        protected TagService $tagService
    ) {
        $this->middleware('auth');
    }

    /**
     * Listar todos los tags
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Tag::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $tags = $query->orderBy('usage_count', 'desc')
            ->orderBy('name')
            ->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($tags);
        }

        return view('tags.index', compact('tags'));
    }

    /**
     * Buscar tags (API)
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 20);

        $tags = $this->tagService->searchTags($query, $limit);

        return response()->json($tags);
    }

    /**
     * Crear nuevo tag
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'category' => 'nullable|in:domain,priority,status,type',
        ]);

        $tag = $this->tagService->findOrCreate(
            $validated['name'],
            $validated['color'] ?? null,
            $validated['category'] ?? null
        );

        return response()->json($tag, 201);
    }

    /**
     * Actualizar tag
     */
    public function update(Request $request, Tag $tag): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'color' => 'nullable|string|max:7',
            'category' => 'nullable|in:domain,priority,status,type',
        ]);

        $tag->update($validated);

        return response()->json($tag);
    }

    /**
     * Eliminar tag
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json(['message' => 'Tag eliminado correctamente']);
    }

    /**
     * Obtener tags populares
     */
    public function popular(): JsonResponse
    {
        $tags = $this->tagService->getPopularTags(10);

        return response()->json($tags);
    }
}
