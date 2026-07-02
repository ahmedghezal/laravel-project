<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        abort_unless(request()->user()->can('manage roles'), 403);

        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function create(): View
    {
        abort_unless(request()->user()->can('manage roles'), 403);

        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->can('manage roles'), 403);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('status', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        abort_unless(request()->user()->can('manage roles'), 403);

        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_unless($request->user()->can('manage roles'), 403);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->id],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('status', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_unless(request()->user()->can('manage roles'), 403);

        abort_if(in_array($role->name, ['admin', 'member']), 403, 'Cannot delete default roles.');

        $role->delete();

        return back()->with('status', 'Role deleted successfully.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        abort_unless($request->user()->can('manage roles'), 403);

        $request->validate([
            'role_ids' => ['required', 'array'],
        ]);

        $roles = Role::whereIn('id', $request->role_ids)->whereNotIn('name', ['admin', 'member'])->get();

        foreach ($roles as $role) {
            $role->delete();
        }

        return back()->with('status', 'Roles deleted successfully.');
    }
}