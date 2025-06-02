<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        $posts = [
            [
                'title' => 'Error 500 al guardar usuario',
                'description' => 'Al intentar guardar un usuario nuevo, aparece un error 500. Esto ocurre solo cuando el email contiene caracteres especiales.',
                'status' => 'open',
                'priority' => 'high',
                'user_id' => $users->random()->id,
                'category_ids' => [$categories->where('name', 'Backend')->first()?->id ?? $categories->first()->id],
            ],
            [
                'title' => 'Los gráficos no se muestran en Firefox',
                'description' => 'Los gráficos del dashboard no se muestran correctamente en Firefox. En Chrome funcionan bien.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'user_id' => $users->random()->id,
                'category_ids' => [$categories->where('name', 'Frontend')->first()?->id ?? $categories->first()->id],
            ],
            [
                'title' => 'Vulnerabilidad XSS en formulario de comentarios',
                'description' => 'Se detectó una vulnerabilidad XSS en el formulario de comentarios. Es posible inyectar código JavaScript.',
                'status' => 'open',
                'priority' => 'critical',
                'user_id' => $users->random()->id,
                'category_ids' => [$categories->where('name', 'Seguridad')->first()?->id ?? $categories->first()->id],
            ],
            [
                'title' => 'Botón de submit no responde en móviles',
                'description' => 'El botón de submit en el formulario de contacto no responde al tacto en dispositivos móviles.',
                'status' => 'open',
                'priority' => 'high',
                'user_id' => $users->random()->id,
                'category_ids' => [$categories->where('name', 'UX/UI')->first()?->id ?? $categories->first()->id],
            ],
            [
                'title' => 'Error al importar archivos CSV grandes',
                'description' => 'Cuando se intenta importar un archivo CSV con más de 10,000 registros, el sistema se cuelga.',
                'status' => 'closed',
                'priority' => 'medium',
                'user_id' => $users->random()->id,
                'category_ids' => [$categories->where('name', 'Backend')->first()?->id ?? $categories->first()->id],
            ],
            [
                'title' => 'Problema de sincronización en tiempo real',
                'description' => 'Los datos no se sincronizan correctamente cuando múltiples usuarios editan el mismo registro.',
                'status' => 'in_progress',
                'priority' => 'high',
                'user_id' => $users->random()->id,
                'category_ids' => [$categories->where('name', 'Quantum Bugs')->first()?->id ?? $categories->first()->id],
            ],
        ];

        foreach ($posts as $postData) {
            $categoryIds = $postData['category_ids'];
            unset($postData['category_ids']);
            
            $post = Post::create($postData);
            
            if (!empty($categoryIds)) {
                $post->categories()->attach($categoryIds);
            }
        }
    }
}
