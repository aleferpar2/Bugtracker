<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrador del sistema con acceso total',
                'permissions' => [
                    'manage-users', 'view-users', 'assign-roles',
                    'manage-posts', 'view-posts', 'assign-posts',
                    'manage-categories', 'view-categories',
                    'manage-comments', 'view-comments',
                    'manage-solutions', 'view-solutions', 'approve-solutions',
                    'manage-notifications', 'view-notifications',
                    'manage-roles', 'view-roles',
                    'manage-permissions', 'view-permissions',
                    'view-statistics',
                ],
            ],
            [
                'name' => 'moderator',
                'description' => 'Moderador con acceso a gestión de contenido',
                'permissions' => [
                    'view-users',
                    'manage-posts', 'view-posts', 'assign-posts',
                    'view-categories',
                    'manage-comments', 'view-comments',
                    'manage-solutions', 'view-solutions', 'approve-solutions',
                    'view-notifications',
                    'view-roles',
                    'view-permissions',
                    'view-statistics',
                ],
            ],
            [
                'name' => 'user',
                'description' => 'Usuario regular con acceso básico',
                'permissions' => [
                    'view-posts',
                    'view-categories',
                    'manage-comments', 'view-comments',
                    'manage-solutions', 'view-solutions',
                    'view-notifications',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::create($roleData);
            $role->givePermissionTo($permissions);
        }
    }
} 