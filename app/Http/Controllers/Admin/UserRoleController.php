<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index(): View
    {
        abort_unless(request()->user()->can('manage roles'), 403);

        $users = User::with('roles')->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('roles', 'users'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()->can('manage roles'), 403);

        $validated = $request->validate([
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user->syncRoles([$validated['role']]);

        return back()->with('status', 'User role updated successfully.');
    }
}
