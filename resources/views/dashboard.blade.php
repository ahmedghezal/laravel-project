<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-white leading-tight">Dashboard</h2>
                <p class="text-slate-400 mt-1">Welcome back, {{ Auth::user()->name }}!</p>
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

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3 mb-8">
                <div class="rounded-2xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 p-6 border border-indigo-500/30 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Published posts</p>
                            <p class="text-4xl font-bold text-white mt-2">{{ $postCount }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 mt-4">Create, edit, and discuss posts from the posts section.</p>
                </div>

                <div class="rounded-2xl bg-gradient-to-br from-cyan-500/20 to-blue-500/20 p-6 border border-cyan-500/30 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Registered users</p>
                            <p class="text-4xl font-bold text-white mt-2">{{ $userCount }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 mt-4">Admins can manage user roles from the roles screen.</p>
                </div>

                <div class="rounded-2xl bg-gradient-to-br from-slate-900/80 to-slate-800/80 p-6 border border-white/10 backdrop-blur-sm md:col-span-2 xl:col-span-1">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500 to-orange-500 flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-white">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-slate-400">{{ Auth::user()->email }}</p>
                            <p class="text-sm text-indigo-400 mt-1">Roles: {{ Auth::user()->getRoleNames()->join(', ') ?: 'No role assigned' }}</p>
                        </div>
                    </div>
                    @can('create posts')
                        <a href="{{ route('posts.create') }}" class="mt-6 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-medium text-slate-900 hover:bg-slate-100 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Write a new post
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Latest Posts -->
            <div class="rounded-2xl bg-slate-900/50 backdrop-blur-sm p-6 border border-white/10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-semibold text-white">Latest posts</h3>
                        <p class="text-sm text-slate-400 mt-1">Quick access to the newest published content.</p>
                    </div>
                    <a href="{{ route('posts.index') }}" class="text-sm font-medium text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-1">
                        View all posts
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse ($latestPosts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="block rounded-xl border border-white/10 p-5 transition-all duration-300 hover:border-indigo-500/30 hover:bg-white/5 group">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-white group-hover:text-indigo-400 transition-colors">{{ $post->title }}</h4>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-sm text-slate-400 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $post->user->name }}
                                        </span>
                                        <span class="text-slate-600">•</span>
                                        <span class="text-sm text-slate-400 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $post->created_at->format('M d, Y') }}
                                        </span>
                                        <span class="text-slate-600">•</span>
                                        <span class="text-sm text-slate-400 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            {{ $post->comments->count() }} comments
                                        </span>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-500 group-hover:text-indigo-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-xl border border-dashed border-white/20 p-10 text-center">
                            <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white">No posts have been published yet.</h3>
                            @can('create posts')
                                <a href="{{ route('posts.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 px-5 py-2.5 text-sm font-medium text-white hover:from-indigo-400 hover:to-purple-500 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create the first post
                                </a>
                            @endcan
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
