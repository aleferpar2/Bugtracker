<?php

namespace Database\Seeders;

use App\Models\Topic;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $users = User::take(3)->get();
        $categories = Category::all()->keyBy('name');

        $blogs = [
            [
                'title' => 'Novedades en Frontend 2024',
                'content' => 'Descubre las últimas tendencias y herramientas en desarrollo Frontend para este año.',
                'category_id' => $categories['Frontend']->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Buenas prácticas en Backend',
                'content' => 'Comparte y aprende sobre arquitecturas robustas y seguras para el backend.',
                'category_id' => $categories['Backend']->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Tips de Seguridad para tu app',
                'content' => 'Consejos esenciales para proteger tus aplicaciones web y móviles.',
                'category_id' => $categories['Seguridad']->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Tendencias en UX/UI',
                'content' => '¿Qué está de moda en experiencia de usuario y diseño de interfaces? ¡Descúbrelo aquí!',
                'category_id' => $categories['UX/UI']->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Quantum Bugs: El futuro de los errores',
                'content' => 'Explora los desafíos y curiosidades de los bugs en la computación cuántica.',
                'category_id' => $categories['Quantum Bugs']->id ?? $categories->first()->id,
            ],
            // Ejemplos relacionados con bugs y errores
            [
                'title' => 'Bug crítico: No se guardan los reportes',
                'content' => 'Detectamos un bug donde los reportes de errores no se guardan correctamente en la base de datos. Ya estamos trabajando en la solución.',
                'category_id' => $categories['Backend']->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Error en el login de usuarios',
                'content' => 'Algunos usuarios reportan que no pueden iniciar sesión. El error parece estar relacionado con la validación de contraseñas.',
                'category_id' => $categories['Seguridad']->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Bug visual en la sección de comunidad',
                'content' => 'Se ha detectado un error visual en la sección de comunidad al mostrar los blogs. Se recomienda limpiar caché y recargar la página.',
                'category_id' => $categories['Frontend']->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Mejorando la gestión de errores',
                'content' => 'Hemos implementado un sistema de logs para detectar y solucionar errores más rápido. ¡Cuéntanos tu experiencia!',
                'category_id' => $categories['Backend']->id ?? $categories->first()->id,
            ],
            [
                'title' => 'Reporte: Error 500 al crear un nuevo blog',
                'content' => 'Varios usuarios han reportado un error 500 al intentar crear un nuevo blog. El equipo está investigando la causa.',
                'category_id' => $categories['Quantum Bugs']->id ?? $categories->first()->id,
            ],
        ];

        foreach ($blogs as $blog) {
            Topic::create([
                'title' => $blog['title'],
                'content' => $blog['content'],
                'category_id' => $blog['category_id'],
                'user_id' => $users->random()->id,
                'views_count' => rand(0, 100),
            ]);
        }
    }
} 