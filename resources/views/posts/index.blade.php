<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-white leading-tight">Posts</h2>
                <p class="text-slate-400 mt-1">Browse the latest posts and jump into discussions.</p>
            </div>
            @can('create posts')
                <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 px-5 py-2.5 text-sm font-medium text-white hover:from-indigo-400 hover:to-purple-500 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Post
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        selectedPosts: [],
        bulkDeleteModalOpen: false,
        allPostIds: [
            @foreach ($posts as $post)
                @if ((auth()->id() === $post->user_id && auth()->user()->can('delete own posts')) || auth()->user()->can('delete any posts'))
                    {{ $post->id }},
                @endif
            @endforeach
        ],
        toggleAllPosts() {
            if (this.selectedPosts.length === this.allPostIds.length) {
                this.selectedPosts = [];
            } else {
                this.selectedPosts = [...this.allPostIds];
            }
        },
        togglePost(postId) {
            const index = this.selectedPosts.indexOf(postId);
            if (index > -1) {
                this.selectedPosts.splice(index, 1);
            } else {
                this.selectedPosts.push(postId);
            }
        },
        get areAllPostsSelected() {
            return this.selectedPosts.length === this.allPostIds.length && this.allPostIds.length > 0;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($posts->isNotEmpty())
                <div class="p-4 mb-6 bg-slate-900/50 backdrop-blur-sm rounded-2xl border border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="select-all-posts" @click="toggleAllPosts" :checked="areAllPostsSelected" class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-900">
                        <label for="select-all-posts" class="text-sm font-medium text-slate-300">Select All</label>
                        <span class="text-sm text-slate-500" x-show="selectedPosts.length > 0" x-text="`(${selectedPosts.length} selected)`"></span>
                    </div>
                    <button type="button" @click="bulkDeleteModalOpen = true" :disabled="selectedPosts.length === 0" class="inline-flex items-center gap-2 rounded-xl bg-red-500/20 border border-red-500/30 px-4 py-2 text-sm font-medium text-red-400 hover:bg-red-500/30 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Selected
                    </button>
                </div>
            @endif

            <div class="space-y-8">
                @if ($posts->isNotEmpty())
                    @foreach ($groupedPosts as $group => $groupPosts)
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $group }}
                            </h3>
                            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($groupPosts as $post)
                                    <div class="flex h-full flex-col rounded-2xl bg-slate-900/50 backdrop-blur-sm p-6 border border-white/10 hover:border-indigo-500/30 transition-all duration-300">
                                        <div class="flex items-start justify-between gap-3 mb-4">
                                            <div class="flex items-start gap-3">
                                                @if ((auth()->id() === $post->user_id && auth()->user()->can('delete own posts')) || auth()->user()->can('delete any posts'))
                                                    <input type="checkbox" value="{{ $post->id }}" @change="togglePost({{ $post->id }})" :checked="selectedPosts.includes({{ $post->id }})" class="h-4 w-4 mt-1 rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-900">
                                                @endif
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-white">{{ $post->title }}</h3>
                                                    <p class="mt-2 flex items-center gap-2 text-sm text-slate-400">
                                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xs font-bold">
                                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                                        </div>
                                                        <span>{{ $post->user->name }}</span>
                                                        <span class="text-slate-600">•</span>
                                                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="flex items-center gap-1.5 rounded-full bg-slate-800 px-3 py-1 text-xs font-medium text-slate-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                {{ $post->comments->count() }}
                                            </span>
                                        </div>

                                        <p class="mt-2 flex-1 text-sm leading-6 text-slate-400">
                                            {{ \Illuminate\Support\Str::limit($post->body, 180) }}
                                        </p>

                                        <div class="mt-6 flex items-center justify-between gap-3">
                                            <a href="{{ route('posts.show', $post) }}" class="text-sm font-medium text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-1">
                                                Read post
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>

                                            @if ((auth()->id() === $post->user_id && (auth()->user()->can('edit own posts') || auth()->user()->can('delete own posts'))) || auth()->user()->can('edit any posts') || auth()->user()->can('delete any posts'))
                                                <div class="flex items-center gap-2">
                                                    @if ((auth()->id() === $post->user_id && auth()->user()->can('edit own posts')) || auth()->user()->can('edit any posts'))
                                                        <a href="{{ route('posts.edit', $post) }}" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">
                                                            Edit
                                                        </a>
                                                    @endif

                                                    @if ((auth()->id() === $post->user_id && auth()->user()->can('delete own posts')) || auth()->user()->can('delete any posts'))
                                                        <div x-data="{ open: false }">
                                                            <button type="button" @click="open = true" class="text-sm font-medium text-red-400 hover:text-red-300 transition-colors">
                                                                Delete
                                                            </button>

                                                            <div x-show="open" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
                                                                <div x-show="open" class="fixed inset-0 transform transition-all" @click="open = false">
                                                                    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
                                                                </div>

                                                                <div x-show="open" class="mb-6 bg-slate-900 rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto border border-white/10">
                                                                    <div class="p-6">
                                                                        <div class="flex items-center justify-between mb-6">
                                                                            <h2 class="text-xl font-semibold text-white">Delete Post</h2>
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
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="md:col-span-2 xl:col-span-3">
                        <div class="rounded-2xl border border-dashed border-white/20 bg-slate-900/30 p-12 text-center">
                            <div class="w-20 h-20 rounded-full bg-slate-800 flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white">No posts yet</h3>
                            <p class="mt-2 text-slate-400">Publish the first post to start the conversation.</p>
                            @can('create posts')
                                <a href="{{ route('posts.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-3 text-sm font-medium text-white hover:from-indigo-400 hover:to-purple-500 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create the first post
                                </a>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>

            @if ($posts->hasPages())
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>

        <!-- Bulk Delete Posts Modal -->
        <div x-show="bulkDeleteModalOpen" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
            <div x-show="bulkDeleteModalOpen" class="fixed inset-0 transform transition-all" @click="bulkDeleteModalOpen = false">
                <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
            </div>

            <div x-show="bulkDeleteModalOpen" class="mb-6 bg-slate-900 rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto border border-white/10">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-white">Delete Selected Posts</h2>
                        <button type="button" @click="bulkDeleteModalOpen = false" class="text-slate-400 hover:text-white transition-colors">
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
                                <p class="font-medium text-white">Are you sure you want to delete <span x-text="selectedPosts.length" class="text-red-400"></span> selected posts?</p>
                                <p class="text-sm text-slate-400 mt-1">Once deleted, these posts and all their comments will be permanently removed.</p>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="{{ route('posts.bulk-destroy') }}" class="flex justify-end gap-3">
                        @csrf
                        @method('delete')
                        <template x-for="postId in selectedPosts" :key="postId">
                            <input type="hidden" name="post_ids[]" :value="postId">
                        </template>
                        <button type="button" @click="bulkDeleteModalOpen = false" class="px-5 py-2.5 rounded-xl border border-white/10 text-sm font-medium text-slate-300 hover:bg-white/5 transition-all duration-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 text-sm font-medium text-white hover:bg-red-400 transition-all duration-300">
                            Delete Selected
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
