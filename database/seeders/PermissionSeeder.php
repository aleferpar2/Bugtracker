<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Permisos de usuarios
            [
                'name' => 'manage-users',
                'description' => 'Gestionar usuarios (crear, editar, eliminar)',
            ],
            [
                'name' => 'view-users',
                'description' => 'Ver usuarios',
            ],
            [
                'name' => 'assign-roles',
                'description' => 'Asignar roles a usuarios',
            ],

            // Permisos de posts
            [
                'name' => 'manage-posts',
                'description' => 'Gestionar posts (crear, editar, eliminar)',
            ],
            [
                'name' => 'view-posts',
                'description' => 'Ver posts',
            ],
            [
                'name' => 'assign-posts',
                'description' => 'Asignar posts a usuarios',
            ],

            // Permisos de categorías
            [
                'name' => 'manage-categories',
                'description' => 'Gestionar categorías (crear, editar, eliminar)',
            ],
            [
                'name' => 'view-categories',
                'description' => 'Ver categorías',
            ],

            // Permisos de comentarios
            [
                'name' => 'manage-comments',
                'description' => 'Gestionar comentarios (crear, editar, eliminar)',
            ],
            [
                'name' => 'view-comments',
                'description' => 'Ver comentarios',
            ],

            // Permisos de soluciones
            [
                'name' => 'manage-solutions',
                'description' => 'Gestionar soluciones (crear, editar, eliminar)',
            ],
            [
                'name' => 'view-solutions',
                'description' => 'Ver soluciones',
            ],
            [
                'name' => 'approve-solutions',
                'description' => 'Aprobar soluciones',
            ],

            // Permisos de notificaciones
            [
                'name' => 'manage-notifications',
                'description' => 'Gestionar notificaciones',
            ],
            [
                'name' => 'view-notifications',
                'description' => 'Ver notificaciones',
            ],

            // Permisos de roles
            [
                'name' => 'manage-roles',
                'description' => 'Gestionar roles (crear, editar, eliminar)',
            ],
            [
                'name' => 'view-roles',
                'description' => 'Ver roles',
            ],

            // Permisos de permisos
            [
                'name' => 'manage-permissions',
                'description' => 'Gestionar permisos',
            ],
            [
                'name' => 'view-permissions',
                'description' => 'Ver permisos',
            ],

            // Permisos de estadísticas
            [
                'name' => 'view-statistics',
                'description' => 'Ver estadísticas',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
} 