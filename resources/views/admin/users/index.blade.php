<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-white leading-tight">User Management</h2>
                <p class="mt-1 text-sm text-slate-400">Create, manage, and delete users.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 px-5 py-2.5 text-sm font-medium text-white hover:from-indigo-400 hover:to-purple-500 transition-all duration-300">
                    Create User
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        selectedUsers: [],
        bulkDeleteModalOpen: false,
        allUserIds: [
            @foreach ($users as $user)
                @if ($user->id !== auth()->id())
                    {{ $user->id }},
                @endif
            @endforeach
        ],
        toggleAllUsers() {
            if (this.selectedUsers.length === this.allUserIds.length) {
                this.selectedUsers = [];
            } else {
                this.selectedUsers = [...this.allUserIds];
            }
        },
        toggleUser(userId) {
            const index = this.selectedUsers.indexOf(userId);
            if (index > -1) {
                this.selectedUsers.splice(index, 1);
            } else {
                this.selectedUsers.push(userId);
            }
        },
        get areAllUsersSelected() {
            return this.selectedUsers.length === this.allUserIds.length && this.allUserIds.length > 0;
        }
    }">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-slate-900/50 backdrop-blur-sm border border-white/10 overflow-hidden">
                <div class="p-4 border-b border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="select-all-users" @click="toggleAllUsers" :checked="areAllUsersSelected" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-600 bg-slate-800 rounded focus:ring-offset-slate-900">
                        <label for="select-all-users" class="text-sm font-medium text-slate-300">Select All</label>
                    </div>
                    <button type="button" @click="bulkDeleteModalOpen = true" :disabled="selectedUsers.length === 0" class="inline-flex items-center gap-2 rounded-xl bg-red-500/20 border border-red-500/30 px-4 py-2 text-sm font-medium text-red-400 hover:bg-red-500/30 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Selected
                        <span class="text-xs bg-red-500/30 rounded-full px-2 py-0.5" x-text="selectedUsers.length" x-show="selectedUsers.length > 0"></span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-slate-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 w-12">Select</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">User</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Current role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 bg-transparent">
                            @foreach ($users as $user)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        @if ($user->id !== auth()->id())
                                            <input type="checkbox" value="{{ $user->id }}" @change="toggleUser({{ $user->id }})" :checked="selectedUsers.includes({{ $user->id }})" class="user-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-600 bg-slate-800 rounded focus:ring-offset-slate-900">
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-white">{{ $user->name }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-400">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-400">
                                        {{ $user->getRoleNames()->join(', ') ?: 'No role assigned' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                            <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="flex items-center gap-3">
                                                @csrf
                                                @method('PUT')

                                                <select
                                                    name="role"
                                                    class="rounded-xl bg-slate-800 border border-slate-700 text-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}" @selected($user->hasRole($role->name))>
                                                            {{ ucfirst($role->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <button type="submit" class="inline-flex rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition-colors">
                                                    Save
                                                </button>
                                            </form>

                                            @if ($user->id !== auth()->id())
                                                <div x-data="{ open: false }">
                                                    <button type="button" @click="open = true" class="inline-flex rounded-xl bg-red-500/20 border border-red-500/30 px-4 py-2 text-sm font-medium text-red-400 hover:bg-red-500/30 transition-all duration-300">
                                                        Delete
                                                    </button>

                                                    <div x-show="open" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
                                                        <div x-show="open" class="fixed inset-0 transform transition-all" @click="open = false">
                                                            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
                                                        </div>

                                                        <div x-show="open" class="mb-6 bg-slate-900 rounded-2xl overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto border border-white/10">
                                                            <div class="p-6">
                                                                <div class="flex items-center justify-between mb-6">
                                                                    <h2 class="text-lg font-medium text-white">Delete User</h2>
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
                                                                            <p class="font-medium text-white">Are you sure you want to delete {{ $user->name }}?</p>
                                                                            <p class="text-sm text-slate-400 mt-1">Once deleted, this user's data and resources will be permanently removed.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <form method="post" action="{{ route('admin.users.destroy', $user) }}" class="flex justify-end gap-3">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="button" @click="open = false" class="px-5 py-2.5 rounded-xl border border-white/10 text-sm font-medium text-slate-300 hover:bg-white/5 transition-all duration-300">
                                                                        Cancel
                                                                    </button>
                                                                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 text-sm font-medium text-white hover:bg-red-400 transition-all duration-300">
                                                                        Delete User
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bulk Delete Users Modal -->
        <div x-show="bulkDeleteModalOpen" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
            <div x-show="bulkDeleteModalOpen" class="fixed inset-0 transform transition-all" @click="bulkDeleteModalOpen = false">
                <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
            </div>

            <div x-show="bulkDeleteModalOpen" class="mb-6 bg-slate-900 rounded-2xl overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto border border-white/10">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-medium text-white">Delete Selected Users</h2>
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
                                <p class="font-medium text-white">Are you sure you want to delete <span x-text="selectedUsers.length" class="text-red-400"></span> selected users?</p>
                                <p class="text-sm text-slate-400 mt-1">Once deleted, all their data and resources will be permanently removed.</p>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="{{ route('admin.users.bulk-destroy') }}" class="flex justify-end gap-3">
                        @csrf
                        @method('delete')
                        <template x-for="userId in selectedUsers" :key="userId">
                            <input type="hidden" name="user_ids[]" :value="userId">
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