<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Solution;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mi usuario admin de pruebas 😎
        $admin = User::firstOrCreate(
            ['email' => 'alex@bugtracker.test'],
            [
                'name' => 'Alex',
                'password' => Hash::make('test123'), // cambiar en prod!
                'email_verified_at' => now(),
            ]
        );

        // El equipo de desarrollo (basado en personas reales)
        $devTeam = [
            'María R.' => 'maria@bugtracker.test', // frontend master
            'Carlos H.' => 'carlos@bugtracker.test', // backend guru
            'Ana M.' => 'ana@bugtracker.test', // qa expert
        ];

        $users = [];
        foreach ($devTeam as $name => $email) {
            $users[] = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('dev123'), // TODO: enviar por email
                    'email_verified_at' => now(),
                ]
            );
        }

        // Categorías principales (basadas en nuestro workflow real)
        $bugTypes = [
            'Frontend' => 'Bugs visuales y de UX',
            'Backend' => 'Issues del servidor y DB',
            'Mobile' => 'Bugs en la app iOS/Android',
            'API' => 'Problemas con endpoints',
        ];

        $categories = [];
        foreach ($bugTypes as $name => $desc) {
            $categories[] = Category::firstOrCreate([
                'name' => $name,
                'description' => $desc
            ]);
        }

        // Bugs reales que hemos tenido 😅
        $realBugs = [
            [
                'title' => 'App crashea al subir fotos HEIC',
                'description' => 'La app se cierra cuando intentas subir fotos en formato HEIC desde un iPhone 13. JPG y PNG funcionan bien.',
                'status' => 'open',
                'priority' => 'high',
                'categories' => ['Mobile', 'Frontend']
            ],
            [
                'title' => 'Race condition en /api/stats',
                'description' => 'Cuando muchos usuarios acceden al dashboard, algunos ven datos duplicados. Problema de concurrencia?',
                'status' => 'in_progress',
                'priority' => 'medium',
                'categories' => ['Backend', 'API']
            ],
        ];

        $posts = [];
        foreach ($realBugs as $bug) {
            // Asignar a dev random (como hacemos irl)
            $dev = $users[array_rand($users)];
            
            $post = Post::create([
                'title' => $bug['title'],
                'description' => $bug['description'],
                'status' => $bug['status'],
                'priority' => $bug['priority'],
                'user_id' => $dev->id,
                'created_at' => now()->subDays(rand(1, 3)), // bugs recientes
            ]);

            // Link categories
            foreach ($bug['categories'] as $catName) {
                $cat = collect($categories)->firstWhere('name', $catName);
                if ($cat) $post->categories()->attach($cat->id);
            }

            $posts[] = $post;
        }

        // Comentarios reales de los devs
        $realComments = [
            'Reproducido en mi iPhone 14 Pro',
            'En Android funciona 🤔',
            'Creo que es por el último deploy',
            'Ya tengo un fix, revisar PR #123',
        ];

        foreach ($posts as $post) {
            // 1-2 comments por bug
            $numComments = rand(1, 2);
            for ($i = 0; $i < $numComments; $i++) {
                Comment::create([
                    'content' => $realComments[array_rand($realComments)],
                    'post_id' => $post->id,
                    'user_id' => $users[array_rand($users)]->id,
                    'created_at' => $post->created_at->addHours(rand(1, 8)),
                ]);
            }
        }

        // Fixes propuestos por el equipo
        $solutions = [
            'Actualizar ImageMagick a v7.1.2',
            'Añadir mutex en /api/stats',
            'Validar formato HEIC en frontend',
            'Implementar circuit breaker',
        ];

        foreach ($posts as $post) {
            if ($post->status === 'in_progress') {
                Solution::create([
                    'description' => $solutions[array_rand($solutions)],
                    'post_id' => $post->id,
                    'user_id' => $users[array_rand($users)]->id,
                    'created_at' => $post->created_at->addHours(12),
                ]);
            }
        }

        $this->command->info('✨ Demo data lista! Prueba con alex@bugtracker.test / test123');
    }
}
