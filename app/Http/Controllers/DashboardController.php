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

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Estadísticas generales
        $stats = [
            'total_posts' => Post::count(),
            'total_users' => User::count(),
            'total_comments' => Comment::count(),
            'total_solutions' => Solution::count(),
            'total_topics' => Topic::count(),
        ];

        // Estadísticas de posts por estado
        $postsByStatus = Post::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Estadísticas de posts por prioridad
        $postsByPriority = Post::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority')
            ->toArray();

        // Posts del usuario actual
        $myPosts = Post::where('user_id', $user->id)
            ->with(['categories'])
            ->latest()
            ->take(5)
            ->get();

        // Posts asignados al usuario
        $assignedPosts = collect(); // Por ahora vacío hasta que implementemos asignación

        // Actividad reciente (últimos comentarios y soluciones)
        $recentComments = Comment::with(['post', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $recentSolutions = Solution::with(['post', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Notificaciones no leídas
        $unreadNotifications = $user->unreadNotifications
            ->take(5);

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'postsByStatus' => $postsByStatus,
            'postsByPriority' => $postsByPriority,
            'myPosts' => $myPosts,
            'assignedPosts' => $assignedPosts,
            'recentComments' => $recentComments,
            'recentSolutions' => $recentSolutions,
            'unreadNotifications' => $unreadNotifications,
            'user' => $user,
        ]);
    }
}
