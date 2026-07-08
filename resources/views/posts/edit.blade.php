<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Post
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">
                <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @include('posts.partials.form', ['submitLabel' => 'Save changes', 'post' => $post])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
