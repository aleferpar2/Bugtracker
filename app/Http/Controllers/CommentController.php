<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000'
        ]);

        $comment = $post->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        // Temporarily disable notifications
        // if ($post->user_id !== auth()->id()) {
        //     $post->user->notify(new NewCommentNotification($comment));
        // }

        return redirect()->back()->with('success', 'Comentario creado exitosamente.');
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Post $post, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000'
        ]);

        $comment->update($validated);

        return redirect()->back()->with('success', 'Comentario actualizado exitosamente.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Post $post, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->back()->with('success', 'Comentario eliminado exitosamente.');
    }
} 