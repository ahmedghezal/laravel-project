<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Posts</h2>
                <p class="mt-1 text-sm text-gray-500">Browse the latest posts and jump into discussions.</p>
            </div>
            @can('create posts')
                <a href="{{ route('posts.create') }}" class="inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                    New Post
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($posts as $post)
                    <div class="flex h-full flex-col rounded-xl bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $post->title }}</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    By {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                {{ $post->comments->count() }} comments
                            </span>
                        </div>

                        <p class="mt-4 flex-1 text-sm leading-6 text-gray-600">
                            {{ \Illuminate\Support\Str::limit($post->body, 180) }}
                        </p>

                        <div class="mt-6 flex items-center justify-between gap-3">
                            <a href="{{ route('posts.show', $post) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Read post
                            </a>

                            @if ((auth()->id() === $post->user_id && (auth()->user()->can('edit own posts') || auth()->user()->can('delete own posts'))) || auth()->user()->can('edit any posts') || auth()->user()->can('delete any posts'))
                                <div class="flex items-center gap-3">
                                    @if ((auth()->id() === $post->user_id && auth()->user()->can('edit own posts')) || auth()->user()->can('edit any posts'))
                                        <a href="{{ route('posts.edit', $post) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                            Edit
                                        </a>
                                    @endif

                                    @if ((auth()->id() === $post->user_id && auth()->user()->can('delete own posts')) || auth()->user()->can('delete any posts'))
                                        <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 xl:col-span-3">
                        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">No posts yet</h3>
                            <p class="mt-2 text-sm text-gray-500">Publish the first post to start the conversation.</p>
                            @can('create posts')
                                <a href="{{ route('posts.create') }}" class="mt-6 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                                    Create the first post
                                </a>
                            @endcan
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
