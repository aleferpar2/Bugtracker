<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommunityController extends Controller
{
    public function index()
    {
        $topics = Topic::with(['author', 'category'])
            ->withCount('replies')
            ->latest()
            ->paginate(10);

        $categories = Category::all();

        return Inertia::render('Community/Index', [
            'topics' => $topics,
            'categories' => $categories
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

        return Inertia::render('Community/Show', [
            'topic' => $topic
        ]);
    }
} 