<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::withCount(['posts', 'comments', 'solutions'])->paginate(10);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['posts', 'comments', 'solutions']);

        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Display statistics for the specified user.
     */
    public function statistics(User $user)
    {
        $statistics = [
            'total_posts' => $user->posts()->count(),
            'total_comments' => $user->comments()->count(),
            'total_solutions' => $user->solutions()->count(),
            'posts_by_status' => $user->posts()
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
            'solutions_by_status' => $user->solutions()
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
        ];

        return Inertia::render('Users/Statistics', [
            'user' => $user,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Display posts for the specified user.
     */
    public function posts(User $user)
    {
        $posts = $user->posts()->with(['categories', 'user'])->paginate(10);

        return Inertia::render('Users/Posts', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    /**
     * Display comments for the specified user.
     */
    public function comments(User $user)
    {
        $comments = $user->comments()->with(['post', 'user'])->paginate(10);

        return Inertia::render('Users/Comments', [
            'user' => $user,
            'comments' => $comments,
        ]);
    }

    /**
     * Display solutions for the specified user.
     */
    public function solutions(User $user)
    {
        $solutions = $user->solutions()->with(['post', 'user'])->paginate(10);

        return Inertia::render('Users/Solutions', [
            'user' => $user,
            'solutions' => $solutions,
        ]);
    }

    /**
     * Display global statistics for all users.
     */
    public function globalStatistics()
    {
        $statistics = [
            'total_users' => User::count(),
            'users_by_role' => User::selectRaw('role, count(*) as count')
                ->groupBy('role')
                ->get(),
            'active_users' => User::whereHas('posts')
                ->orWhereHas('comments')
                ->orWhereHas('solutions')
                ->count(),
            'top_contributors' => User::withCount(['posts', 'comments', 'solutions'])
                ->orderByDesc('posts_count')
                ->orderByDesc('comments_count')
                ->orderByDesc('solutions_count')
                ->take(10)
                ->get(),
        ];

        return Inertia::render('Users/GlobalStatistics', [
            'statistics' => $statistics,
        ]);
    }
} 