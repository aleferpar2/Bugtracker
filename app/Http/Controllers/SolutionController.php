<?php

namespace App\Http\Controllers;

use App\Models\Solution;
use App\Models\Post;
use App\Notifications\NewSolutionNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SolutionController extends Controller
{
    /**
     * Store a newly created solution in storage.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'description' => 'required|string|min:10|max:5000'
        ]);

        $solution = $post->solutions()->create([
            'description' => $validated['description'],
            'user_id' => auth()->id(),
            'is_accepted' => false,
        ]);

        // Temporarily disable notifications
        // if ($post->user_id !== auth()->id()) {
        //     $post->user->notify(new NewSolutionNotification($solution));
        // }

        return redirect()->back()->with('success', 'Solución propuesta exitosamente.');
    }

    /**
     * Update the specified solution in storage.
     */
    public function update(Request $request, Post $post, Solution $solution)
    {
        $this->authorize('update', $solution);

        $validated = $request->validate([
            'description' => 'required|string|min:10|max:5000',
            'is_accepted' => 'sometimes|boolean'
        ]);

        $solution->update($validated);

        return redirect()->back()->with('success', 'Solución actualizada exitosamente.');
    }

    /**
     * Update the status of the specified solution.
     */
    public function updateStatus(Request $request, Post $post, Solution $solution)
    {
        $this->authorize('update', $solution);

        $validated = $request->validate([
            'is_accepted' => 'required|boolean'
        ]);

        // Si se acepta esta solución, rechazar todas las demás
        if ($validated['is_accepted']) {
            $post->solutions()->where('id', '!=', $solution->id)->update(['is_accepted' => false]);
            $post->update(['status' => 'resolved']);
        }

        $solution->update($validated);

        return redirect()->back()->with('success', 'Estado de la solución actualizado exitosamente.');
    }

    /**
     * Remove the specified solution from storage.
     */
    public function destroy(Post $post, Solution $solution)
    {
        $this->authorize('delete', $solution);

        $solution->delete();

        return redirect()->back()->with('success', 'Solución eliminada exitosamente.');
    }
} 