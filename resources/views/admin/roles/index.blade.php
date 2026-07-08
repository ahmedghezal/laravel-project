<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-white leading-tight">Role Management</h2>
                <p class="mt-1 text-sm text-slate-400">Create, manage, and delete roles and their permissions.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 px-5 py-2.5 text-sm font-medium text-white hover:from-indigo-400 hover:to-purple-500 transition-all duration-300">
                    Create Role
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        selectedRoles: [],
        bulkDeleteModalOpen: false,
        allRoleIds: [
            @foreach ($roles as $role)
                @if (!in_array($role->name, ['admin', 'member']))
                    {{ $role->id }},
                @endif
            @endforeach
        ],
        toggleAllRoles() {
            if (this.selectedRoles.length === this.allRoleIds.length) {
                this.selectedRoles = [];
            } else {
                this.selectedRoles = [...this.allRoleIds];
            }
        },
        toggleRole(roleId) {
            const index = this.selectedRoles.indexOf(roleId);
            if (index > -1) {
                this.selectedRoles.splice(index, 1);
            } else {
                this.selectedRoles.push(roleId);
            }
        },
        get areAllRolesSelected() {
            return this.selectedRoles.length === this.allRoleIds.length && this.allRoleIds.length > 0;
        }
    }">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if($roles->whereNotIn('name', ['admin', 'member'])->count() > 0)
                <div class="p-4 mb-6 bg-slate-900/50 backdrop-blur-sm rounded-2xl border border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="select-all-roles" @click="toggleAllRoles" :checked="areAllRolesSelected" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-600 bg-slate-800 rounded focus:ring-offset-slate-900">
                        <label for="select-all-roles" class="text-sm font-medium text-slate-300">Select All</label>
                        <span class="text-sm text-slate-500" x-show="selectedRoles.length > 0" x-text="`(${selectedRoles.length} selected)`"></span>
                    </div>
                    <button type="button" @click="bulkDeleteModalOpen = true" :disabled="selectedRoles.length === 0" class="inline-flex items-center gap-2 rounded-xl bg-red-500/20 border border-red-500/30 px-4 py-2 text-sm font-medium text-red-400 hover:bg-red-500/30 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Selected
                    </button>
                </div>
            @endif

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($roles as $role)
                    <div class="rounded-2xl bg-slate-900/50 backdrop-blur-sm p-6 border border-white/10 hover:border-indigo-500/30 transition-all duration-300">
                        <div class="flex items-start gap-3 mb-4">
                            @if (!in_array($role->name, ['admin', 'member']))
                                <input type="checkbox" value="{{ $role->id }}" @change="toggleRole({{ $role->id }})" :checked="selectedRoles.includes({{ $role->id }})" class="role-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-600 bg-slate-800 rounded focus:ring-offset-slate-900 mt-1">
                            @endif
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-white">{{ ucfirst($role->name) }}</h3>
                                <p class="mt-1 text-sm text-slate-400">
                                    {{ $role->permissions->count() }} permissions assigned
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach ($role->permissions as $permission)
                                <span class="rounded-full bg-indigo-500/20 border border-indigo-500/30 px-3 py-1 text-xs font-medium text-indigo-300">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-800 border border-white/10 px-4 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700 transition-colors">
                                Edit
                            </a>

                            @if (!in_array($role->name, ['admin', 'member']))
                                <div x-data="{ open: false }">
                                    <button type="button" @click="open = true" class="inline-flex items-center gap-2 rounded-xl bg-red-500/20 border border-red-500/30 px-4 py-2 text-sm font-medium text-red-400 hover:bg-red-500/30 transition-all duration-300">
                                        Delete
                                    </button>

                                    <div x-show="open" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
                                        <div x-show="open" class="fixed inset-0 transform transition-all" @click="open = false">
                                            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
                                        </div>

                                        <div x-show="open" class="mb-6 bg-slate-900 rounded-2xl overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto border border-white/10">
                                            <div class="p-6">
                                                <div class="flex items-center justify-between mb-6">
                                                    <h2 class="text-lg font-medium text-white">Delete Role</h2>
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
                                                            <p class="font-medium text-white">Are you sure you want to delete {{ ucfirst($role->name) }}?</p>
                                                            <p class="text-sm text-slate-400 mt-1">Once deleted, this role will be removed from all users.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <form method="post" action="{{ route('admin.roles.destroy', $role) }}" class="flex justify-end gap-3">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" @click="open = false" class="px-5 py-2.5 rounded-xl border border-white/10 text-sm font-medium text-slate-300 hover:bg-white/5 transition-all duration-300">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 text-sm font-medium text-white hover:bg-red-400 transition-all duration-300">
                                                        Delete Role
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Bulk Delete Roles Modal -->
        <div x-show="bulkDeleteModalOpen" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
            <div x-show="bulkDeleteModalOpen" class="fixed inset-0 transform transition-all" @click="bulkDeleteModalOpen = false">
                <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
            </div>

            <div x-show="bulkDeleteModalOpen" class="mb-6 bg-slate-900 rounded-2xl overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto border border-white/10">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-medium text-white">Delete Selected Roles</h2>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77-1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-white">Are you sure you want to delete <span x-text="selectedRoles.length" class="text-red-400"></span> selected roles?</p>
                                <p class="text-sm text-slate-400 mt-1">Once deleted, these roles will be removed from all users.</p>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="{{ route('admin.roles.bulk-destroy') }}" class="flex justify-end gap-3">
                        @csrf
                        @method('delete')
                        <template x-for="roleId in selectedRoles" :key="roleId">
                            <input type="hidden" name="role_ids[]" :value="roleId">
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