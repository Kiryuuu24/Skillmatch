<?php

use Illuminate\Support\Facades\Route;

// Splash screen (redirect based on auth)
Route::get('/', function () {
    return view('splash');
});

// Auth pages (no middleware - public access)
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
});

// Authenticated pages (check token via JavaScript, not Laravel middleware)
Route::get('/beranda', function () {
    return view('beranda');
});

Route::get('/cari', function () {
    return view('cari');
});

Route::get('/profile', function () {
    return view('profile');
});

// Static profile routes MUST come before /profile/{userId}
Route::get('/profile/edit', function () {
    return view('profile.edit');
});

Route::get('/profile/settings', function () {
    return view('profile.settings');
});

Route::get('/profile/{userId}', function ($userId) {
    return view('profile-view', ['userId' => $userId]);
});

Route::get('/posts/create', function () {
    return view('posts.create');
});

Route::get('/pengajuan', function () {
    return view('pengajuan');
});

Route::get('/posts/{id}', function ($id) {
    return view('posts.detail', ['postId' => $id]);
});

Route::get('/rekomendasi', function () {
    return view('rekomendasi');
});

// Admin pages (check token via JavaScript)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
    
    Route::get('/users', function () {
        return view('admin.users');
    });
    
    Route::get('/posts', function () {
        return view('admin.posts');
    });
});
