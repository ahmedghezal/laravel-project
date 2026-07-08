@csrf

<div>
    <x-input-label for="title" :value="__('Title')" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $post->title ?? '')" required autofocus />
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>

<div class="mt-4">
    <x-input-label for="photo" :value="__('Photo')" />
    <input
        id="photo"
        name="photo"
        type="file"
        accept="image/*"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
    />
    @if (isset($post) && $post->photo)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $post->photo) }}" alt="Post photo" class="max-w-xs rounded-lg shadow-sm">
        </div>
    @endif
    <x-input-error class="mt-2" :messages="$errors->get('photo')" />
</div>

<div class="mt-4">
    <x-input-label for="body" :value="__('Body')" />
    <textarea
        id="body"
        name="body"
        rows="8"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        required
    >{{ old('body', $post->body ?? '') }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get('body')" />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
    <a href="{{ isset($post) ? route('posts.show', $post) : route('posts.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
        Cancel
    </a>
</div>
