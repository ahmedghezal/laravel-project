<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage roles',
            'create posts',
            'edit own posts',
            'edit any posts',
            'delete own posts',
            'delete any posts',
            'comment on posts',
            'delete any comments',
        ];

        $createdPermissions = collect($permissions)
            ->map(fn (string $permission) => Permission::findOrCreate($permission, 'web'));

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::findOrCreate('admin', 'web');
        $memberRole = Role::findOrCreate('member', 'web');

        $adminRole->syncPermissions($createdPermissions);
        $memberRole->syncPermissions(
            $createdPermissions->whereIn('name', [
                'create posts',
                'edit own posts',
                'delete own posts',
                'comment on posts',
            ]),
        );

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $admin->assignRole($adminRole);

        $member = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $member->assignRole($memberRole);

        $welcomePost = Post::create([
            'user_id' => $admin->id,
            'title' => 'Welcome to the blog',
            'body' => 'This starter project includes authentication, post publishing, comments, and admin role management.',
        ]);

        $welcomePost->comments()->create([
            'user_id' => $member->id,
            'body' => 'The initial setup is ready for testing.',
        ]);
    }
}
