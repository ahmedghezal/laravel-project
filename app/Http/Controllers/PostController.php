<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::with(['user', 'comments.user'])
            ->latest()
            ->paginate(6);

        return view('posts.index', compact('posts'));
    }

    public function create(): View
    {
        abort_unless(request()->user()->can('create posts'), 403);

        return view('posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->can('create posts'), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10'],
        ]);

        $post = $request->user()->posts()->create($validated);

        return to_route('posts.show', $post)->with('status', 'Post created successfully.');
    }

    public function show(Post $post): View
    {
        $post->load(['user', 'comments.user']);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        abort_unless(
            (request()->user()->id === $post->user_id && request()->user()->can('edit own posts'))
                || request()->user()->can('edit any posts'),
            403,
        );

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        abort_unless(
            ($request->user()->id === $post->user_id && $request->user()->can('edit own posts'))
                || $request->user()->can('edit any posts'),
            403,
        );

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10'],
        ]);

        $post->update($validated);

        return to_route('posts.show', $post)->with('status', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        abort_unless(
            (request()->user()->id === $post->user_id && request()->user()->can('delete own posts'))
                || request()->user()->can('delete any posts'),
            403,
        );

        $post->delete();

        return to_route('posts.index')->with('status', 'Post deleted successfully.');
    }
}
