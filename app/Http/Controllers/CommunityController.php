<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Topic::with(['author', 'category'])
            ->withCount('replies')
            ->latest();

        // Aplicar filtros si existen
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Aplicar ordenamiento
        switch ($request->get('sort', 'recent')) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'responses':
                $query->orderBy('replies_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $topics = $query->paginate(10);
        $topics->appends($request->all());

        $categories = Category::all();

        return Inertia::render('Community/Index', [
            'topics' => $topics,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'sort']),
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Community/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $topic = $request->user()->topics()->create($validated);

        return redirect()->route('community.show', $topic);
    }

    public function show(Topic $topic)
    {
        $topic->load(['author', 'category']);
        $topic->increment('views_count');

        $replies = $topic->replies()
            ->with('author')
            ->latest()
            ->paginate(10);

        return Inertia::render('Community/Show', [
            'topic' => $topic,
            'replies' => $replies
        ]);
    }

    public function storeReply(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3'
        ]);

        $reply = $topic->replies()->create([
            'content' => $validated['content'],
            'user_id' => $request->user()->id
        ]);

        return redirect()->route('community.show', $topic)
            ->with('success', 'Respuesta publicada exitosamente.');
    }
} 