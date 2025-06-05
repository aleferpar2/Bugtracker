<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios de prueba
        $admin = User::updateOrCreate(
            ['email' => 'admin@bugtracker.com'],
            [
                'name' => 'Admin Usuario',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@bugtracker.com'],
            [
                'name' => 'Usuario Normal',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Crear categorías
        $bugCategory = Category::create([
            'name' => 'Bug Critical',
            'description' => 'Errores críticos del sistema',
            'slug' => 'bug-critical',
        ]);

        $featureCategory = Category::create([
            'name' => 'Nueva Funcionalidad',
            'description' => 'Solicitudes de nuevas características',
            'slug' => 'nueva-funcionalidad',
        ]);

        $discussionCategory = Category::create([
            'name' => 'Discusión General',
            'description' => 'Debates y discusiones generales',
            'slug' => 'discusion-general',
        ]);

        // Crear post de prueba
        $post = Post::create([
            'title' => 'Error en el login del sistema',
            'description' => 'Los usuarios no pueden iniciar sesión correctamente. El error aparece después de introducir credenciales válidas.',
            'status' => 'open',
            'priority' => 'critical',
            'user_id' => $user->id,
            'technology' => 'Laravel',
            'application' => 'Web App',
            'version' => '1.0.0',
            'year' => 2025,
        ]);

        // Asociar categoría al post
        $post->categories()->attach($bugCategory->id);

        // Crear topic de comunidad
        $topic = Topic::create([
            'title' => '¿Cómo optimizar consultas en Laravel?',
            'content' => 'Estoy trabajando en una aplicación que hace muchas consultas a la base de datos. ¿Cuáles son las mejores prácticas para optimizar el rendimiento?',
            'user_id' => $user->id,
            'category_id' => $discussionCategory->id,
            'views_count' => 15,
        ]);
    }
}
