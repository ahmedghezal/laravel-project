<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-indigo-400">Post details</p>
                <h2 class="font-semibold text-2xl text-white leading-tight">{{ $post->title }}</h2>
            </div>
            <a href="{{ route('posts.index') }}" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">
                Back to posts
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto grid max-w-7xl gap-6 lg:grid-cols-[1.5fr,0.8fr] sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-slate-900/50 backdrop-blur-sm p-6 sm:p-8 border border-white/10">
                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-400">
                    <span>By {{ $post->user->name }}</span>
                    <span>&middot;</span>
                    <span>{{ $post->created_at->format('M d, Y h:i A') }}</span>
                </div>

                @if ($post->photo)
                    <div class="mt-6 rounded-xl overflow-hidden border border-white/10">
                        <img src="{{ asset('storage/' . $post->photo) }}" alt="{{ $post->title }}" class="w-full max-h-96 object-cover">
                    </div>
                @endif

                <div class="mt-6 whitespace-pre-line text-base leading-7 text-slate-300">{{ $post->body }}</div>

                @if ((auth()->id() === $post->user_id && (auth()->user()->can('edit own posts') || auth()->user()->can('delete own posts'))) || auth()->user()->can('edit any posts') || auth()->user()->can('delete any posts'))
                    <div class="mt-8 flex items-center gap-4 border-t border-white/10 pt-6">
                        @if ((auth()->id() === $post->user_id && auth()->user()->can('edit own posts')) || auth()->user()->can('edit any posts'))
                            <a href="{{ route('posts.edit', $post) }}" class="inline-flex rounded-xl bg-slate-800 border border-white/10 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700 transition-colors">
                                Edit post
                            </a>
                        @endif

                        @if ((auth()->id() === $post->user_id && auth()->user()->can('delete own posts')) || auth()->user()->can('delete any posts'))
                            <div x-data="{ open: false }">
                                <button type="button" @click="open = true" class="inline-flex rounded-xl border border-red-500/30 px-4 py-2 text-sm font-medium text-red-400 hover:bg-red-500/20 transition-all duration-300">
                                    Delete post
                                </button>

                                <div x-show="open" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
                                    <div x-show="open" class="fixed inset-0 transform transition-all" @click="open = false">
                                        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
                                    </div>

                                    <div x-show="open" class="mb-6 bg-slate-900 rounded-2xl overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto border border-white/10">
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-6">
                                                <h2 class="text-lg font-medium text-white">Delete Post</h2>
                                                <button type="button" @click="open = false" class="text-slate-400 hover:text-white transition-colors">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <div class="mb-6">
                                                <div class="flex items-center gap-4 p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
                                                    <div class="flex-shrink-0 w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-white">Are you sure you want to delete "{{ $post->title }}"?</p>
                                                        <p class="text-sm text-slate-400 mt-1">Once deleted, this post and all its comments will be permanently removed.</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="post" action="{{ route('posts.destroy', $post) }}" class="flex justify-end gap-3">
                                                @csrf
                                                @method('delete')
                                                <button type="button" @click="open = false" class="px-5 py-2.5 rounded-xl border border-white/10 text-sm font-medium text-slate-300 hover:bg-white/5 transition-all duration-300">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 text-sm font-medium text-white hover:bg-red-400 transition-all duration-300">
                                                    Delete Post
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl bg-slate-900/50 backdrop-blur-sm p-6 border border-white/10">
                    <h3 class="text-lg font-semibold text-white">Leave a comment</h3>
                    <p class="mt-1 text-sm text-slate-400">Share feedback or continue the discussion.</p>

                    @can('comment on posts')
                        <form method="POST" action="{{ route('comments.store', $post) }}" class="mt-6">
                            @csrf
                            <textarea
                                name="body"
                                rows="5"
                                class="block w-full rounded-xl bg-slate-800 border border-slate-700 text-white placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Write your comment here..."
                                required
                            >{{ old('body') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('body')" />

                            <x-primary-button class="mt-4">Post comment</x-primary-button>
                        </form>
                    @else
                        <p class="mt-4 rounded-xl border border-dashed border-white/20 bg-slate-800 p-4 text-sm text-slate-400">
                            Your current role does not have permission to comment on posts.
                        </p>
                    @endcan
                </div>

                <div class="rounded-2xl bg-slate-900/50 backdrop-blur-sm p-6 border border-white/10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Comments</h3>
                        <span class="rounded-full bg-slate-800 px-3 py-1 text-xs font-medium text-slate-400">
                            {{ $post->comments->count() }} total
                        </span>
                    </div>

                    <div class="mt-6 space-y-4">
                        @forelse ($post->comments as $comment)
                            <div class="rounded-xl border border-white/10 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-medium text-white">{{ $comment->user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $comment->created_at->format('M d, Y h:i A') }}</p>
                                    </div>

                                    @if (auth()->id() === $comment->user_id || auth()->user()->can('delete any comments'))
                                        <div x-data="{ open: false }">
                                            <button type="button" @click="open = true" class="text-sm font-medium text-red-400 hover:text-red-300 transition-colors">
                                                Delete
                                            </button>

                                            <div x-show="open" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
                                                <div x-show="open" class="fixed inset-0 transform transition-all" @click="open = false">
                                                    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
                                                </div>

                                                <div x-show="open" class="mb-6 bg-slate-900 rounded-2xl overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto border border-white/10">
                                                    <div class="p-6">
                                                        <div class="flex items-center justify-between mb-6">
                                                            <h2 class="text-lg font-medium text-white">Delete Comment</h2>
                                                            <button type="button" @click="open = false" class="text-slate-400 hover:text-white transition-colors">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        
                                                        <div class="mb-6">
                                                            <div class="flex items-center gap-4 p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
                                                                <div class="flex-shrink-0 w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center">
                                                                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p class="font-medium text-white">Are you sure you want to delete this comment?</p>
                                                                    <p class="text-sm text-slate-400 mt-1">Once deleted, this comment will be permanently removed.</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <form method="post" action="{{ route('comments.destroy', $comment) }}" class="flex justify-end gap-3">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="button" @click="open = false" class="px-5 py-2.5 rounded-xl border border-white/10 text-sm font-medium text-slate-300 hover:bg-white/5 transition-all duration-300">
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 text-sm font-medium text-white hover:bg-red-400 transition-all duration-300">
                                                                Delete Comment
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-300">{{ $comment->body }}</p>
                            </div>
                        @empty
                            <p class="rounded-xl border border-dashed border-white/20 bg-slate-800 p-6 text-sm text-slate-400">
                                No comments yet. Start the discussion.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>