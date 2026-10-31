<?php

use App\Http\Controllers\Api\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SmartMatchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (no authentication)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Public: Get skills catalog
Route::get('/skills', [ProfileController::class, 'getSkills']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Profile Management (KF-02, KF-02a, KF-03)
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::put('/skills', [ProfileController::class, 'updateSkills']);
        Route::put('/status', [ProfileController::class, 'updateStatus']);
        Route::put('/privacy', [ProfileController::class, 'updatePrivacy']);
        Route::get('/{userId}', [ProfileController::class, 'show']);
    });

    // Posts (KF-04, KF-05, KF-06)
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'store']);
        Route::get('/search-candidates', [PostController::class, 'searchCandidates']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::put('/{id}', [PostController::class, 'update']);
        Route::delete('/{id}', [PostController::class, 'destroy']);
    });

    // Applications (KF-07, KF-08)
    Route::prefix('applications')->group(function () {
        Route::get('/', [ApplicationController::class, 'index']);
        Route::post('/', [ApplicationController::class, 'store']);
        Route::get('/{id}', [ApplicationController::class, 'show']);
        Route::put('/{id}', [ApplicationController::class, 'update']);
        Route::delete('/{id}', [ApplicationController::class, 'destroy']);
    });

    // Smart Match / Recommendations (KF-09)
    Route::prefix('smart-match')->group(function () {
        Route::get('/recommendations', [SmartMatchController::class, 'getRecommendations']);
        Route::get('/candidates/{postId}', [SmartMatchController::class, 'getCandidateRecommendations']);
    });

    // Admin routes (KF-10, KF-11)
    Route::middleware('admin')->prefix('admin')->group(function () {
        // User Management (KF-10)
        Route::prefix('users')->group(function () {
            Route::get('/', [AdminUserController::class, 'index']);
            Route::get('/{id}', [AdminUserController::class, 'show']);
            Route::put('/{id}', [AdminUserController::class, 'update']);
            Route::post('/{id}/deactivate', [AdminUserController::class, 'deactivate']);
            Route::post('/{id}/activate', [AdminUserController::class, 'activate']);
            Route::delete('/{id}', [AdminUserController::class, 'destroy']);
        });

        // Post Management (KF-11)
        Route::prefix('posts')->group(function () {
            Route::get('/', [AdminPostController::class, 'index']);
            Route::get('/{id}', [AdminPostController::class, 'show']);
            Route::put('/{id}', [AdminPostController::class, 'update']);
            Route::post('/{id}/hide', [AdminPostController::class, 'hide']);
            Route::post('/{id}/unhide', [AdminPostController::class, 'unhide']);
            Route::delete('/{id}', [AdminPostController::class, 'destroy']);
        });
    });
});
