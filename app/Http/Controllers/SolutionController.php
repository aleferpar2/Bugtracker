<?php

namespace App\Http\Controllers;

use App\Models\Solution;
use App\Models\Post;
use App\Notifications\NewSolutionNotification;
use App\Http\Requests\SolutionRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SolutionController extends Controller
{
    /**
     * Store a newly created solution in storage.
     */
    public function store(SolutionRequest $request, Post $post)
    {
        $solution = $post->solutions()->create([
            'description' => $request->description,
            'status' => $request->status,
            'code_snippet' => $request->code_snippet,
            'steps' => $request->steps,
            'user_id' => auth()->id(),
        ]);

        // Notificar al autor del post
        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new NewSolutionNotification($solution));
        }

        return redirect()->back()->with('success', 'Solución propuesta exitosamente.');
    }

    /**
     * Update the specified solution in storage.
     */
    public function update(SolutionRequest $request, Post $post, Solution $solution)
    {
        $this->authorize('update', $solution);

        $solution->update([
            'description' => $request->description,
            'status' => $request->status,
            'code_snippet' => $request->code_snippet,
            'steps' => $request->steps,
        ]);

        return redirect()->back()->with('success', 'Solución actualizada exitosamente.');
    }

    /**
     * Update the status of the specified solution.
     */
    public function updateStatus(Request $request, Post $post, Solution $solution)
    {
        $this->authorize('updateStatus', $solution);

        $request->validate([
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
        ]);

        $solution->update([
            'status' => $request->status,
        ]);

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