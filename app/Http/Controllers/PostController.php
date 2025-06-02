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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'categories']);

        // Aplicar filtros si existen
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Aplicar ordenamiento
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'most_voted':
                $query->orderBy('votes_count', 'desc');
                break;
            case 'most_commented':
                $query->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $posts = $query->paginate(10);
        $posts->appends($request->all());

        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'categories' => Category::all(),
            'filters' => $request->only(['search', 'category', 'status', 'sort']),
            'can' => [
                'create' => true, // Por ahora todos pueden crear
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Simular la creación de un bug
        return redirect()->route('posts.index')->with('success', 'Bug creado exitosamente (simulado).');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'categories', 'comments.user', 'solutions.user']);
        
        // Agregar datos adicionales que espera el componente
        $postData = $post->toArray();
        $postData['category'] = $post->categories->first(); // El componente espera un objeto category
        $postData['has_voted'] = false; // Por defecto no ha votado
        $postData['votes_count'] = $post->votes_count ?? 0;
        $postData['solutions'] = $post->solutions ?? [];
        $postData['comments'] = $post->comments ?? [];

        return Inertia::render('Posts/Show', [
            'post' => $postData,
            'can' => [
                'update' => true, // Por ahora todos pueden actualizar
                'delete' => true, // Por ahora todos pueden eliminar  
                'comment' => true, // Por ahora todos pueden comentar
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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
