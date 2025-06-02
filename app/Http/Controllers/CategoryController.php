<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::withCount('posts')->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load('posts');

        return Inertia::render('Categories/Show', [
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return Inertia::render('Categories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }

    /**
     * Display posts for the specified category.
     */
    public function posts(Category $category)
    {
        $posts = $category->posts()->with(['user', 'categories'])->paginate(10);

        return Inertia::render('Categories/Posts', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    /**
     * Display statistics for the specified category.
     */
    public function statistics(Category $category)
    {
        $statistics = [
            'total_posts' => $category->posts()->count(),
            'posts_by_status' => $category->posts()
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
            'posts_by_priority' => $category->posts()
                ->selectRaw('priority, count(*) as count')
                ->groupBy('priority')
                ->get(),
        ];

        return Inertia::render('Categories/Statistics', [
            'category' => $category,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Display global statistics for all categories.
     */
    public function globalStatistics()
    {
        $statistics = [
            'total_categories' => Category::count(),
            'categories_with_posts' => Category::has('posts')->count(),
            'posts_by_category' => Category::withCount('posts')
                ->orderByDesc('posts_count')
                ->take(10)
                ->get(),
        ];

        return Inertia::render('Categories/GlobalStatistics', [
            'statistics' => $statistics,
        ]);
    }
}
