<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-white leading-tight">Create User</h2>
                <p class="mt-1 text-sm text-slate-400">Add a new user to the system.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-slate-900/50 backdrop-blur-sm p-6 sm:p-8 border border-white/10">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="mb-6">
                        <x-input-label for="name" :value="__('Name')" class="text-slate-300" />
                        <x-text-input id="name" class="mt-1 block w-full bg-slate-800 border-slate-700 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="email" :value="__('Email')" class="text-slate-300" />
                        <x-text-input id="email" class="mt-1 block w-full bg-slate-800 border-slate-700 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="password" :value="__('Password')" class="text-slate-300" />
                        <x-text-input id="password" class="mt-1 block w-full bg-slate-800 border-slate-700 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-300" />
                        <x-text-input id="password_confirmation" class="mt-1 block w-full bg-slate-800 border-slate-700 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="role" :value="__('Role')" class="text-slate-300" />
                        <select
                            id="role"
                            name="role"
                            class="mt-1 block w-full rounded-xl bg-slate-800 border border-slate-700 text-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Create') }}</x-primary-button>
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-white/10 rounded-xl font-semibold text-xs text-slate-300 uppercase tracking-widest hover:bg-slate-700 active:bg-slate-600 focus:outline-none focus:border-indigo-500 focus:ring ring-indigo-500 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>