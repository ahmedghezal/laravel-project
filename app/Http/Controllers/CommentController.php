<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        abort_unless($request->user()->can('comment on posts'), 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:3'],
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return to_route('posts.show', $post)->with('status', 'Comment added successfully.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $user = request()->user();

        abort_unless(
            $user->id === $comment->user_id || $user->can('delete any comments'),
            403,
        );

        $post = $comment->post;

        $comment->delete();

        return to_route('posts.show', $post)->with('status', 'Comment deleted successfully.');
    }
}
