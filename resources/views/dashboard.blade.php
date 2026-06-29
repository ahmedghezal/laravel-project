<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Published posts</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $postCount }}</p>
                    <p class="mt-2 text-sm text-gray-600">Create, edit, and discuss posts from the posts section.</p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Registered users</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $userCount }}</p>
                    <p class="mt-2 text-sm text-gray-600">Admins can manage user roles from the roles screen.</p>
                </div>

                <div class="rounded-xl bg-slate-900 p-6 text-white shadow-sm">
                    <p class="text-sm font-medium text-slate-300">Your account</p>
                    <p class="mt-3 text-2xl font-semibold">{{ auth()->user()->name }}</p>
                    <p class="mt-2 text-sm text-slate-300">Roles: {{ auth()->user()->getRoleNames()->join(', ') ?: 'No role assigned' }}</p>
                    @can('create posts')
                        <a href="{{ route('posts.create') }}" class="mt-4 inline-flex rounded-lg bg-white px-4 py-2 text-sm font-medium text-slate-900">
                            Write a new post
                        </a>
                    @endcan
                </div>
            </div>

            <div class="mt-6 rounded-xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Latest posts</h3>
                        <p class="mt-1 text-sm text-gray-600">Quick access to the newest published content.</p>
                    </div>
                    <a href="{{ route('posts.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        View all posts
                    </a>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($latestPosts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="block rounded-lg border border-gray-200 p-4 transition hover:border-indigo-300 hover:bg-indigo-50/40">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-base font-semibold text-gray-900">{{ $post->title }}</p>
                                    <p class="text-sm text-gray-500">By {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}</p>
                                </div>
                                <span class="text-sm font-medium text-indigo-600">Open</span>
                            </div>
                        </a>
                    @empty
                        <p class="rounded-lg border border-dashed border-gray-300 p-6 text-sm text-gray-500">
                            No posts have been published yet.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
