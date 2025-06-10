<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Inertia\Inertia;
use App\Events\PostStatusChanged;
use App\Events\PostAssigned;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    // TODO: Implementar cache para optimizar queries frecuentes
    // FIXME: La paginación está rota en móvil 🤔
    
  
    public function index(Request $request)
    {
        // Consulta base con relaciones necesarias
        $query = Post::with(['user', 'categories', 'solutions']);

        // Búsqueda SQL en título y descripción
        if ($request->search) {
            $query->where(fn($q) => 
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
            );
        }

        // Filtro por categoría
        $request->category && $query->whereHas('categories', 
            fn($q) => $q->where('categories.id', $request->category)
        );

        // Filtro por estado del bug
        $request->status && $query->where('status', $request->status);

        // Ordenamiento de resultados
        match($request->sort ?? 'latest') {
            'oldest' => $query->oldest(),
            'most_voted' => $query->orderBy('votes_count', 'desc'),
            'most_commented' => $query->withCount('comments')
                                    ->orderBy('comments_count', 'desc'),
            default => $query->latest()
        };

        // Transformación de datos para el frontend
        $posts = $query->get()->map(fn($post) => [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'status' => $post->status,
            'priority' => $post->priority,
            'user' => [
                'id' => $post->user->id,
                'name' => $post->user->name
            ],
            'categories' => $post->categories->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name
            ])->values(),
            'solutions' => $post->solutions->map(fn($sol) => [
                'id' => $sol->id,
                'description' => $sol->description,
                'is_accepted' => $sol->is_accepted
            ])->values(),
            'created_at' => $post->created_at
        ]);

        // Estadísticas rápidas
        $stats = [
            'total' => Post::count(),
            'resolved' => Post::where('status', 'resolved')->count(),
            'in_progress' => Post::where('status', 'in_progress')->count(),
            'open' => Post::where('status', 'open')->count(),
        ];

        // Retorna vista con datos
        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'categories' => Category::select('id', 'name')->get(),
            'filters' => $request->only(['search', 'category', 'status', 'sort']),
            'can' => ['create' => auth()->check()],
            'stats' => $stats,
        ]);
    }

  
    public function create()
    {
        return Inertia::render('Posts/Create', [
            'categories' => Category::all(),
            'priorities' => Post::PRIORITIES,
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255|unique:posts',
            'description' => 'required|string|min:10',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'required|in:low,medium,high,critical',
            'technology' => 'nullable|string|max:100',
            'application' => 'nullable|string|max:100',
            'version' => 'nullable|string|max:50',
            'year' => 'nullable|string|max:4'
        ]);

        $post = Post::create([
            ...$data,
            'status' => 'open',
            'user_id' => auth()->id(),
        ]);

        if (isset($data['category_id'])) {
            $post->categories()->attach($data['category_id']);
        }

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Bug reportado correctamente');
    }


    public function show(Post $post)
    {
        $post->load(['user', 'categories', 'comments.user', 'solutions.user']);
        
        return Inertia::render('Posts/Show', [
            'post' => [
                ...$post->toArray(),
                'category' => $post->categories->first(),
                'has_voted' => false,
                'votes_count' => $post->votes_count ?? 0,
                'solutions' => $post->solutions ?? [],
                'comments' => $post->comments ?? [],
            ],
            'can' => [
                'update' => auth()->id() === $post->user_id,
                'delete' => auth()->id() === $post->user_id,
                'comment' => auth()->check(),
            ],
        ]);
    }


    public function edit(string $id)
    {
        //
    }

  
    public function update(PostRequest $request, string $id)
    {
        $post = Post::findOrFail($id);
        $oldStatus = $post->status;
        $oldAssignee = $post->assignedTo;

        $validated = $request->validated();
        $post->update($validated);
        $post->categories()->sync($validated['categories']);

        // Disparar evento de cambio de estado si cambió
        if ($oldStatus !== $post->status) {
            event(new PostStatusChanged($post, $oldStatus, $post->status));
        }

        // Disparar evento de cambio de asignación si cambió
        if ($oldAssignee?->id !== $post->assigned_to) {
            event(new PostAssigned($post, $oldAssignee, $post->assignedTo));
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post actualizado exitosamente.');
    }
    //terminar mas tarde sistema de borrado
    public function destroy(string $id)
    {
        //
    }

    /**
     * Realiza una búsqueda en StackOverflow usando la API pública.
     */
    public function advancedSearchStackOverflow(Request $request)
    {
        try {
            $query = $request->input('search');
            $url = "https://api.stackexchange.com/2.3/search/advanced?order=desc&sort=relevance&q=" . urlencode($query) . "&site=stackoverflow";
            
            Log::info('Intentando búsqueda en StackOverflow', ['url' => $url]);
            
            $response = Http::withHeaders([
                'User-Agent' => 'BugTracker/1.0'
            ])->timeout(10)->get($url);
            
            Log::info('Respuesta de StackOverflow', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json($data);
            } else {
                Log::error('Error en respuesta de StackOverflow', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['error' => 'Error en la respuesta de StackOverflow: ' . $response->status()], 500);
            }
        } catch (\Exception $e) {
            Log::error('Excepción en búsqueda StackOverflow', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error detallado: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Devuelve el detalle de una pregunta de StackOverflow y sus respuestas principales.
     */
    public function stackOverflowDetail($question_id)
    {
        $url = 'https://api.stackexchange.com/2.3/questions/' . $question_id . '?order=desc&sort=activity&site=stackoverflow&filter=!6VvPDzQ)xwQG9';
        $response = Http::timeout(10)->get($url);
        if (!$response->ok()) {
            return response()->json(['error' => 'No se pudo obtener el detalle de la pregunta.'], 500);
        }
        $data = $response->json();
        $question = $data['items'][0] ?? null;
        if (!$question) {
            return response()->json(['error' => 'Pregunta no encontrada.'], 404);
        }
        // Obtener respuestas principales
        $answersUrl = 'https://api.stackexchange.com/2.3/questions/' . $question_id . '/answers?order=desc&sort=votes&site=stackoverflow&filter=!b1MME6xJY5';
        $answersResponse = Http::timeout(10)->get($answersUrl);
        $answers = $answersResponse->ok() ? $answersResponse->json()['items'] ?? [] : [];
        return response()->json([
            'question' => $question,
            'answers' => $answers,
        ]);
    }
}

