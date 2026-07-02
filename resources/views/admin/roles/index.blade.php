<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Role Management</h2>
                <p class="mt-1 text-sm text-gray-500">Create, manage, and delete roles and their permissions.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.roles.create') }}" class="inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
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
            <div class="p-4 mb-6 bg-white rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="select-all-roles" @click="toggleAllRoles" :checked="areAllRolesSelected" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="select-all-roles" class="text-sm font-medium text-gray-700">Select All</label>
                </div>
                <button type="button" @click="bulkDeleteModalOpen = true" :disabled="selectedRoles.length === 0" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Delete Selected
                    <span class="text-xs bg-red-700 rounded-full px-2 py-0.5" x-text="selectedRoles.length" x-show="selectedRoles.length > 0"></span>
                </button>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($roles as $role)
                    <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-200">
                        <div class="flex items-start gap-3 mb-4">
                            @if (!in_array($role->name, ['admin', 'member']))
                                <input type="checkbox" value="{{ $role->id }}" @change="toggleRole({{ $role->id }})" :checked="selectedRoles.includes({{ $role->id }})" class="role-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            @endif
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst($role->name) }}</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $role->permissions->count() }} permissions assigned
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach ($role->permissions as $permission)
                                <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-800">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                                Edit
                            </a>

                            @if (!in_array($role->name, ['admin', 'member']))
                                <div x-data="{ open: false }">
                                    <button type="button" @click="open = true" class="inline-flex rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">
                                        Delete
                                    </button>

                                    <div x-show="open" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" x-cloak>
                                        <div x-show="open" class="fixed inset-0 transform transition-all" @click="open = false">
                                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                        </div>

                                        <div x-show="open" class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto">
                                            <div class="p-6">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h2 class="text-lg font-medium text-gray-900">Delete Role</h2>
                                                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                
                                                <div class="mb-6">
                                                    <div class="flex items-center gap-4 p-4 bg-red-50 rounded-lg mb-4">
                                                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-red-800">Are you sure you want to delete {{ ucfirst($role->name) }}?</p>
                                                            <p class="text-sm text-red-600">Once deleted, this role will be removed from all users.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <form method="post" action="{{ route('admin.roles.destroy', $role) }}" class="flex justify-end gap-3">
                                                    @csrf
                                                    @method('delete')
                                                    <x-secondary-button @click="open = false" type="button">
                                                        Cancel
                                                    </x-secondary-button>
                                                    <x-danger-button>
                                                        Delete Role
                                                    </x-danger-button>
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
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div x-show="bulkDeleteModalOpen" class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-medium text-gray-900">Delete Selected Roles</h2>
                        <button type="button" @click="bulkDeleteModalOpen = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center gap-4 p-4 bg-red-50 rounded-lg mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-red-800">Are you sure you want to delete <span x-text="selectedRoles.length"></span> selected roles?</p>
                                <p class="text-sm text-red-600">Once deleted, these roles will be removed from all users.</p>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="{{ route('admin.roles.bulk-destroy') }}" class="flex justify-end gap-3">
                        @csrf
                        @method('delete')
                        <template x-for="roleId in selectedRoles" :key="roleId">
                            <input type="hidden" name="role_ids[]" :value="roleId">
                        </template>
                        <x-secondary-button @click="bulkDeleteModalOpen = false" type="button">
                            Cancel
                        </x-secondary-button>
                        <x-danger-button type="submit">
                            Delete Selected
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
