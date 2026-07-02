<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-indigo-600">Post details</p>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">{{ $post->title }}</h2>
            </div>
            <a href="{{ route('posts.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                Back to posts
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto grid max-w-7xl gap-6 lg:grid-cols-[1.5fr,0.8fr] sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                    <span>By {{ $post->user->name }}</span>
                    <span>&middot;</span>
                    <span>{{ $post->created_at->format('M d, Y h:i A') }}</span>
                </div>

                <div class="mt-6 whitespace-pre-line text-base leading-7 text-gray-700">{{ $post->body }}</div>

                @if ((auth()->id() === $post->user_id && (auth()->user()->can('edit own posts') || auth()->user()->can('delete own posts'))) || auth()->user()->can('edit any posts') || auth()->user()->can('delete any posts'))
                    <div class="mt-8 flex items-center gap-4 border-t border-gray-100 pt-6">
                        @if ((auth()->id() === $post->user_id && auth()->user()->can('edit own posts')) || auth()->user()->can('edit any posts'))
                            <a href="{{ route('posts.edit', $post) }}" class="inline-flex rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                                Edit post
                            </a>
                        @endif

                        @if ((auth()->id() === $post->user_id && auth()->user()->can('delete own posts')) || auth()->user()->can('delete any posts'))
                            <div x-data="{ open: false }">
                                <button type="button" @click="open = true" class="inline-flex rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                                    Delete post
                                </button>

                                <div x-show="open" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
                                    <div x-show="open" class="fixed inset-0 transform transition-all" @click="open = false">
                                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                    </div>

                                    <div x-show="open" class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto">
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <h2 class="text-lg font-medium text-gray-900">Delete Post</h2>
                                                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <div class="mb-6">
                                                <div class="flex items-center gap-4 p-4 bg-red-50 rounded-lg mb-4">
                                                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-red-800">Are you sure you want to delete "{{ $post->title }}"?</p>
                                                        <p class="text-sm text-red-600">Once deleted, this post and all its comments will be permanently removed.</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="post" action="{{ route('posts.destroy', $post) }}" class="flex justify-end gap-3">
                                                @csrf
                                                @method('delete')
                                                <x-secondary-button @click="open = false" type="button">
                                                    Cancel
                                                </x-secondary-button>
                                                <x-danger-button>
                                                    Delete Post
                                                </x-danger-button>
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
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">Leave a comment</h3>
                    <p class="mt-1 text-sm text-gray-500">Share feedback or continue the discussion.</p>

                    @can('comment on posts')
                        <form method="POST" action="{{ route('comments.store', $post) }}" class="mt-6">
                            @csrf
                            <textarea
                                name="body"
                                rows="5"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Write your comment here..."
                                required
                            >{{ old('body') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('body')" />

                            <x-primary-button class="mt-4">Post comment</x-primary-button>
                        </form>
                    @else
                        <p class="mt-4 rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500">
                            Your current role does not have permission to comment on posts.
                        </p>
                    @endcan
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Comments</h3>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                            {{ $post->comments->count() }} total
                        </span>
                    </div>

                    <div class="mt-6 space-y-4">
                        @forelse ($post->comments as $comment)
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $comment->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $comment->created_at->format('M d, Y h:i A') }}</p>
                                    </div>

                                    @if (auth()->id() === $comment->user_id || auth()->user()->can('delete any comments'))
                                        <div x-data="{ open: false }">
                                            <button type="button" @click="open = true" class="text-sm font-medium text-red-600 hover:text-red-500">
                                                Delete
                                            </button>

                                            <div x-show="open" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
                                                <div x-show="open" class="fixed inset-0 transform transition-all" @click="open = false">
                                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                </div>

                                                <div x-show="open" class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto">
                                                    <div class="p-6">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <h2 class="text-lg font-medium text-gray-900">Delete Comment</h2>
                                                            <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        
                                                        <div class="mb-6">
                                                            <div class="flex items-center gap-4 p-4 bg-red-50 rounded-lg mb-4">
                                                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p class="font-medium text-red-800">Are you sure you want to delete this comment?</p>
                                                                    <p class="text-sm text-red-600">Once deleted, this comment will be permanently removed.</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <form method="post" action="{{ route('comments.destroy', $comment) }}" class="flex justify-end gap-3">
                                                            @csrf
                                                            @method('delete')
                                                            <x-secondary-button @click="open = false" type="button">
                                                                Cancel
                                                            </x-secondary-button>
                                                            <x-danger-button>
                                                                Delete Comment
                                                            </x-danger-button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $comment->body }}</p>
                            </div>
                        @empty
                            <p class="rounded-lg border border-dashed border-gray-300 p-6 text-sm text-gray-500">
                                No comments yet. Start the discussion.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
