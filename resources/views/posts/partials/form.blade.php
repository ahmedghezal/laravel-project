@csrf

<div>
    <x-input-label for="title" :value="__('Title')" class="text-slate-300" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full bg-slate-800 border-slate-700 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500" :value="old('title', $post->title ?? '')" required autofocus />
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>

<div class="mt-4">
    <x-input-label for="photo" :value="__('Photo')" class="text-slate-300" />
    <input
        id="photo"
        name="photo"
        type="file"
        accept="image/*"
        class="mt-1 block w-full text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-500"
    />
    @if (isset($post) && $post->photo)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $post->photo) }}" alt="Post photo" class="max-w-xs rounded-lg border border-white/10 shadow-lg">
        </div>
    @endif
    <x-input-error class="mt-2" :messages="$errors->get('photo')" />
</div>

<div class="mt-4">
    <x-input-label for="body" :value="__('Body')" class="text-slate-300" />
    <textarea
        id="body"
        name="body"
        rows="8"
        class="mt-1 block w-full rounded-xl bg-slate-800 border-slate-700 text-white placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        required
    >{{ old('body', $post->body ?? '') }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get('body')" />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
    <a href="{{ isset($post) ? route('posts.show', $post) : route('posts.index') }}" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">
        Cancel
    </a>
</div>
