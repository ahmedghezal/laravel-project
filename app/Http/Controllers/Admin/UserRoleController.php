<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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

    public function create(): View
    {
        abort_unless(request()->user()->can('manage roles'), 403);

        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->can('manage roles'), 403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('status', 'User created successfully.');
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

    public function destroy(User $user): RedirectResponse
    {
        abort_unless(request()->user()->can('manage roles'), 403);

        abort_if($user->id === auth()->id(), 403, 'You cannot delete your own account.');

        $user->delete();

        return back()->with('status', 'User deleted successfully.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        abort_unless($request->user()->can('manage roles'), 403);

        $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id', 'not_in:' . auth()->id()],
        ]);

        User::whereIn('id', $request->user_ids)->delete();

        return back()->with('status', 'Users deleted successfully.');
    }
}
