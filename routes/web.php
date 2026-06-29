<?php

use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard', [
        'postCount' => Post::count(),
        'userCount' => User::count(),
        'latestPosts' => Post::with('user')->latest()->take(5)->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('can:manage roles')->group(function () {
        Route::get('/admin/users', [UserRoleController::class, 'index'])->name('admin.users.index');
        Route::put('/admin/users/{user}/role', [UserRoleController::class, 'update'])->name('admin.users.update-role');
    });
});

require __DIR__.'/auth.php';
