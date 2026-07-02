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
    Route::delete('/posts/bulk-destroy', [PostController::class, 'bulkDestroy'])->name('posts.bulk-destroy');
    Route::resource('posts', PostController::class);
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('can:manage roles')->group(function () {
        Route::delete('/admin/users/bulk-destroy', [UserRoleController::class, 'bulkDestroy'])->name('admin.users.bulk-destroy');
        Route::delete('/admin/roles/bulk-destroy', [\App\Http\Controllers\Admin\RoleController::class, 'bulkDestroy'])->name('admin.roles.bulk-destroy');
        Route::resource('/admin/users', UserRoleController::class)->names('admin.users')->only(['index', 'create', 'store', 'destroy']);
        Route::put('/admin/users/{user}/role', [UserRoleController::class, 'update'])->name('admin.users.update-role');
        Route::resource('/admin/roles', \App\Http\Controllers\Admin\RoleController::class)->names('admin.roles');
    });
});

require __DIR__.'/auth.php';
