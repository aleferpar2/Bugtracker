<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Solution;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Si el usuario está autenticado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Si no está autenticado, mostrar página de landing
        return $this->showLanding();
    }

    private function showLanding()
    {
        // Estadísticas públicas básicas
        $publicStats = [
            'total_posts' => Post::count(),
            'total_users' => User::count(),
            'total_solutions' => Solution::count(),
            'resolved_posts' => Post::where('status', 'resolved')->count(),
        ];

        return Inertia::render('Landing', [
            'stats' => $publicStats,
        ]);
    }

    public function dashboard()
    {
        // Página de inicio para usuarios autenticados (anterior funcionalidad)
        $stats = [
            'total_posts' => Post::count(),
            'total_users' => User::count(),
            'total_solutions' => Solution::count(),
            'resolved_posts' => Post::where('status', 'resolved')->count(),
        ];

        // Posts más recientes (últimos 6)
        $latestPosts = Post::with(['user', 'categories'])
            ->latest()
            ->take(6)
            ->get();

        // Posts más comentados (últimos 7 días)
        $mostCommentedPosts = Post::with(['user', 'categories'])
            ->withCount('comments')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('comments_count', 'desc')
            ->take(3)
            ->get();

        // Categorías más populares
        $popularCategories = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(4)
            ->get();

        // Actividad reciente (comentarios y soluciones)
        $recentActivity = collect()
            ->merge(
                Comment::with(['post', 'user'])
                    ->latest()
                    ->take(3)
                    ->get()
                    ->map(function ($comment) {
                        return [
                            'type' => 'comment',
                            'user' => $comment->user,
                            'post' => $comment->post,
                            'content' => $comment->content,
                            'created_at' => $comment->created_at,
                        ];
                    })
            )
            ->merge(
                Solution::with(['post', 'user'])
                    ->latest()
                    ->take(3)
                    ->get()
                    ->map(function ($solution) {
                        return [
                            'type' => 'solution',
                            'user' => $solution->user,
                            'post' => $solution->post,
                            'content' => $solution->description,
                            'created_at' => $solution->created_at,
                        ];
                    })
            )
            ->sortByDesc('created_at')
            ->take(4);

        return Inertia::render('Home', [
            'stats' => $stats,
            'latestPosts' => $latestPosts,
            'mostCommentedPosts' => $mostCommentedPosts,
            'popularCategories' => $popularCategories,
            'recentActivity' => $recentActivity->values(),
        ]);
    }
}
