<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Category;
use App\Models\Reply;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function topics(Request $request)
    {
        $query = Topic::with(['author', 'category'])
            ->withCount('replies')
            ->latest();

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->sort === 'popular') {
            $query->orderBy('views_count', 'desc');
        } elseif ($request->sort === 'responses') {
            $query->orderBy('replies_count', 'desc');
        }

        return $query->paginate(10);
    }

    public function categories()
    {
        return Category::all();
    }

    public function replies(Topic $topic)
    {
        return $topic->replies()
            ->with('author')
            ->latest()
            ->paginate(10);
    }

    public function storeReply(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $reply = $topic->replies()->create([
            'content' => $validated['content'],
            'user_id' => $request->user()->id
        ]);

        return response()->json($reply->load('author'));
    }
} 