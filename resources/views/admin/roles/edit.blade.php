<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-white leading-tight">Edit Role</h2>
                <p class="mt-1 text-sm text-slate-400">Update role details and permissions.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-slate-900/50 backdrop-blur-sm p-6 sm:p-8 border border-white/10">
                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <x-input-label for="name" :value="__('Role Name')" class="text-slate-300" />
                        <x-text-input id="name" class="mt-1 block w-full bg-slate-800 border-slate-700 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name', $role->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label :value="__('Permissions')" class="text-slate-300" />
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($permissions as $permission)
                                <label class="flex items-center gap-3 rounded-xl bg-slate-800/50 border border-white/10 p-3 hover:bg-slate-800 transition-colors">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked($role->hasPermissionTo($permission) || in_array($permission->name, old('permissions', []))) class="rounded border-slate-600 bg-slate-800 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900">
                                    <span class="text-sm text-slate-300">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Update') }}</x-primary-button>
                        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-white/10 rounded-xl font-semibold text-xs text-slate-300 uppercase tracking-widest hover:bg-slate-700 active:bg-slate-600 focus:outline-none focus:border-indigo-500 focus:ring ring-indigo-500 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>