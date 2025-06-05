<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SolutionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\Api\CommunityController as ApiCommunityController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Notifications\DatabaseNotification;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rutas públicas
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas que requieren autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    // Home para usuarios autenticados
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('home.dashboard');
    
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Posts
    Route::resource('posts', PostController::class);
    Route::get('posts/{post}/comments', [PostController::class, 'comments'])->name('posts.comments');
    Route::get('posts/{post}/solutions', [PostController::class, 'solutions'])->name('posts.solutions');
    Route::get('posts/{post}/statistics', [PostController::class, 'statistics'])->name('posts.statistics');

    // Comentarios
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::put('posts/{post}/comments/{comment}', [CommentController::class, 'update'])->name('posts.comments.update');
    Route::delete('posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('posts.comments.destroy');

    // Soluciones
    Route::post('posts/{post}/solutions', [SolutionController::class, 'store'])->name('posts.solutions.store');
    Route::put('posts/{post}/solutions/{solution}', [SolutionController::class, 'update'])->name('posts.solutions.update');
    Route::patch('posts/{post}/solutions/{solution}/status', [SolutionController::class, 'updateStatus'])->name('posts.solutions.update-status');
    Route::delete('posts/{post}/solutions/{solution}', [SolutionController::class, 'destroy'])->name('posts.solutions.destroy');

    // Categorías
    Route::resource('categories', CategoryController::class);
    Route::get('categories/{category}/posts', [CategoryController::class, 'posts'])->name('categories.posts');
    Route::get('categories/{category}/statistics', [CategoryController::class, 'statistics'])->name('categories.statistics');

    // Notificaciones
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead')->whereUuid('notification');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy')->whereUuid('notification');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroyAll');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');

    // Rutas de administración (solo para administradores)
    Route::middleware(['role:admin'])->group(function () {
        // Gestión de usuarios
        Route::resource('users', UserController::class);
        Route::get('users/{user}/statistics', [UserController::class, 'statistics'])->name('users.statistics');
        Route::get('users/{user}/posts', [UserController::class, 'posts'])->name('users.posts');
        Route::get('users/{user}/comments', [UserController::class, 'comments'])->name('users.comments');
        Route::get('users/{user}/solutions', [UserController::class, 'solutions'])->name('users.solutions');

        // Estadísticas generales
        Route::prefix('statistics')->name('statistics.')->group(function () {
            Route::get('/', [UserController::class, 'globalStatistics'])->name('global');
            Route::get('/posts', [PostController::class, 'globalStatistics'])->name('posts');
            Route::get('/categories', [CategoryController::class, 'globalStatistics'])->name('categories');
            Route::get('/users', [UserController::class, 'userStatistics'])->name('users');
            Route::get('/comments', [CommentController::class, 'globalStatistics'])->name('comments');
            Route::get('/solutions', [SolutionController::class, 'globalStatistics'])->name('solutions');
            Route::get('/activity', [UserController::class, 'activityStatistics'])->name('activity');
            Route::get('/performance', [UserController::class, 'performanceStatistics'])->name('performance');
        });
    });

    // Rutas de búsqueda avanzada
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/posts', [PostController::class, 'search'])->name('posts');
        Route::get('/users', [UserController::class, 'search'])->name('users');
        Route::get('/categories', [CategoryController::class, 'search'])->name('categories');
        Route::get('/comments', [CommentController::class, 'search'])->name('comments');
        Route::get('/solutions', [SolutionController::class, 'search'])->name('solutions');
        Route::get('/advanced', [PostController::class, 'advancedSearch'])->name('advanced');
    });

    // Comunidad
    Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
    Route::get('/community/create', [CommunityController::class, 'create'])->name('community.create');
    Route::post('/community', [CommunityController::class, 'store'])->name('community.store');
    Route::get('/community/{topic}', [CommunityController::class, 'show'])->name('community.show');
    Route::post('/community/{topic}/replies', [CommunityController::class, 'storeReply'])->name('community.replies.store');

    Route::get('/bugs/advanced', function () {
        return Inertia::render('Posts/AdvancedSearch');
    })->name('posts.advanced');

    Route::post('/posts/advanced-search-stackoverflow', [App\Http\Controllers\PostController::class, 'advancedSearchStackOverflow'])->name('posts.advanced.stackoverflow');

    Route::get('/stackoverflow/question/{question_id}', [App\Http\Controllers\PostController::class, 'stackOverflowDetail'])->name('stackoverflow.detail');

    Route::get('/stackoverflow/result/{questionId}', function ($questionId) {
        return Inertia::render('Posts/StackOverflowResult', ['questionId' => $questionId]);
    })->name('posts.stackoverflow.result');
    
    // Rutas de API
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/community/topics', [ApiCommunityController::class, 'topics'])->name('community.topics');
        Route::get('/community/categories', [ApiCommunityController::class, 'categories'])->name('community.categories');
        Route::get('/community/{topic}/replies', [ApiCommunityController::class, 'replies'])->name('community.replies');
    });
});

// Rutas de autenticación
require __DIR__.'/auth.php';
