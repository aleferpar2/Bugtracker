<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(CommentRequest $request, Post $post)
    {
        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        // Notificar al autor del post
        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new NewCommentNotification($comment));
        }

        return redirect()->back()->with('success', 'Comentario creado exitosamente.');
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(CommentRequest $request, Post $post, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->update([
            'content' => $request->content,
        ]);

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