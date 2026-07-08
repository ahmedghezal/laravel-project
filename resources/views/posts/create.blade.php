<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-white leading-tight">Create Post</h2>
                <p class="text-slate-400 mt-1">Share your thoughts and ideas with the community.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-slate-900/50 backdrop-blur-sm p-6 sm:p-8 border border-white/10">
                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                    @include('posts.partials.form', ['submitLabel' => 'Publish post'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
