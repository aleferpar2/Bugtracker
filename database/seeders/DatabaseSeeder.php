<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Solution;
use App\Models\Topic;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Usuario administrador
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Usuarios de ejemplo
        $users = [];
        $users[] = User::create([
            'name' => 'Juan Desarrollador',
            'email' => 'juan@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        $users[] = User::create([
            'name' => 'María QA',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        $users[] = User::create([
            'name' => 'Carlos Frontend',
            'email' => 'carlos@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Crear categorías
        $categories = [];
        $categoryNames = [
            'Frontend' => 'Problemas y discusiones relacionadas con el desarrollo frontend',
            'Backend' => 'Temas relacionados con el desarrollo backend y APIs',
            'DevOps' => 'Discusiones sobre despliegue, CI/CD y operaciones',
            'Base de Datos' => 'Problemas y optimizaciones de bases de datos',
            'Seguridad' => 'Temas de seguridad y buenas prácticas',
        ];

        foreach ($categoryNames as $name => $description) {
            $categories[] = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
            ]);
        }

        // Posts (Bugs) de ejemplo
        $bugs = [
            [
                'title' => 'Error en la autenticación OAuth2',
                'description' => 'Al intentar autenticar con Google, el callback URL no está funcionando correctamente. Error 404 en /auth/callback.',
                'status' => 'open',
                'priority' => 'high',
                'technology' => 'Laravel',
                'application' => 'Sistema de Autenticación',
                'version' => '10.0',
                'year' => '2024',
            ],
            [
                'title' => 'Memory leak en procesamiento de imágenes',
                'description' => 'Detectado un memory leak al procesar múltiples imágenes simultáneamente. El uso de memoria aumenta progresivamente.',
                'status' => 'in_progress',
                'priority' => 'critical',
                'technology' => 'Node.js',
                'application' => 'Servicio de Procesamiento de Imágenes',
                'version' => '18.x',
                'year' => '2024',
            ],
            [
                'title' => 'Problema de renderizado en Safari',
                'description' => 'Los estilos flexbox no se aplican correctamente en Safari móvil. Layout se rompe en iOS.',
                'status' => 'open',
                'priority' => 'medium',
                'technology' => 'CSS',
                'application' => 'Frontend App',
                'version' => 'Safari 15',
                'year' => '2024',
            ],
            [
                'title' => 'Error en la sincronización de datos offline',
                'description' => 'La sincronización de datos offline no maneja correctamente los conflictos cuando hay cambios simultáneos.',
                'status' => 'resolved',
                'priority' => 'high',
                'technology' => 'IndexedDB',
                'application' => 'PWA',
                'version' => '1.0',
                'year' => '2024',
            ],
            [
                'title' => 'Vulnerabilidad XSS en formulario de comentarios',
                'description' => 'Detectada posibilidad de inyección XSS en el campo de comentarios. No se están escapando correctamente los caracteres especiales.',
                'status' => 'resolved',
                'priority' => 'critical',
                'technology' => 'JavaScript',
                'application' => 'Sistema de Comentarios',
                'version' => '2.1',
                'year' => '2024',
            ],
            [
                'title' => 'Error en la generación de PDFs',
                'description' => 'Los PDFs generados muestran caracteres incorrectos cuando contienen símbolos especiales o emojis.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'technology' => 'PHP',
                'application' => 'Generador de Reportes',
                'version' => '8.2',
                'year' => '2024',
            ],
            [
                'title' => 'Problema de rendimiento en consultas SQL',
                'description' => 'Las consultas a la tabla de usuarios están tomando más de 5 segundos cuando se filtran por múltiples campos.',
                'status' => 'open',
                'priority' => 'high',
                'technology' => 'MySQL',
                'application' => 'Base de Datos',
                'version' => '8.0',
                'year' => '2024',
            ],
            [
                'title' => 'Error en el worker de procesamiento de colas',
                'description' => 'El worker de Redis se detiene inesperadamente al procesar trabajos que contienen archivos adjuntos grandes.',
                'status' => 'in_progress',
                'priority' => 'high',
                'technology' => 'Redis',
                'application' => 'Sistema de Colas',
                'version' => '6.2',
                'year' => '2024',
            ],
            [
                'title' => 'Bug en la validación de formularios dinámicos',
                'description' => 'La validación de formularios dinámicos no funciona correctamente cuando se añaden nuevos campos mediante JavaScript.',
                'status' => 'open',
                'priority' => 'medium',
                'technology' => 'Vue.js',
                'application' => 'Frontend Forms',
                'version' => '3.x',
                'year' => '2024',
            ],
            [
                'title' => 'Error en la integración con API de pagos',
                'description' => 'Las transacciones duplicadas no se están manejando correctamente, causando cargos múltiples al cliente.',
                'status' => 'resolved',
                'priority' => 'critical',
                'technology' => 'Stripe API',
                'application' => 'Sistema de Pagos',
                'version' => '2024-01',
                'year' => '2024',
            ],
        ];

        // Crear los posts y asociar comentarios y soluciones
        foreach ($bugs as $bugData) {
            $post = Post::create([
                ...$bugData,
                'user_id' => $users[array_rand($users)]->id,
            ]);

            // Asociar categorías aleatorias (1-2 categorías por post)
            $numCategories = rand(1, 2);
            $randomCategories = array_rand($categories, $numCategories);
            if (!is_array($randomCategories)) {
                $randomCategories = [$randomCategories];
            }
            foreach ($randomCategories as $categoryIndex) {
                $post->categories()->attach($categories[$categoryIndex]);
            }

            // Añadir algunos comentarios
            for ($i = 0; $i < rand(1, 4); $i++) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $users[array_rand($users)]->id,
                    'content' => $this->getRandomComment(),
                ]);
            }

            // Añadir soluciones para algunos bugs
            if (in_array($bugData['status'], ['resolved', 'in_progress'])) {
                Solution::create([
                    'post_id' => $post->id,
                    'user_id' => $users[array_rand($users)]->id,
                    'description' => $this->getRandomSolution(),
                    'is_accepted' => $bugData['status'] === 'resolved',
                ]);
            }
        }

        // Crear algunos temas de comunidad
        $topics = [
            [
                'title' => 'Mejores prácticas para prevenir XSS',
                'content' => 'Me gustaría discutir las mejores prácticas actuales para prevenir ataques XSS en aplicaciones web modernas.',
                'category_id' => $categories[array_search('Seguridad', array_column($categories, 'name'))]->id,
            ],
            [
                'title' => 'Optimización de consultas SQL',
                'content' => '¿Qué técnicas utilizan para optimizar consultas SQL complejas? Compartamos experiencias.',
                'category_id' => $categories[array_search('Base de Datos', array_column($categories, 'name'))]->id,
            ],
            [
                'title' => 'Manejo de errores en APIs REST',
                'content' => '¿Cómo manejan los errores en sus APIs REST? ¿Qué estándares siguen?',
                'category_id' => $categories[array_search('Backend', array_column($categories, 'name'))]->id,
            ],
        ];

        foreach ($topics as $topicData) {
            Topic::create([
                ...$topicData,
                'user_id' => $users[array_rand($users)]->id,
            ]);
        }
    }

    private function getRandomComment(): string
    {
        $comments = [
            'He encontrado el mismo problema en la versión anterior.',
            'Esto parece estar relacionado con un problema similar que resolvimos la semana pasada.',
            'Puedo confirmar que este error también ocurre en el ambiente de producción.',
            '¿Has intentado limpiar la caché y reiniciar el servicio?',
            'Necesitamos más información sobre el entorno donde ocurre el error.',
            'He creado un caso de prueba que reproduce el problema.',
            'Este bug parece tener prioridad alta debido a su impacto en los usuarios.',
            'Estoy trabajando en una posible solución, actualizaré pronto.',
        ];

        return $comments[array_rand($comments)];
    }

    private function getRandomSolution(): string
    {
        $solutions = [
            'La solución fue actualizar las dependencias del proyecto y limpiar la caché.',
            'Se implementó un middleware adicional para manejar la validación de tokens.',
            'Se optimizó la consulta SQL añadiendo índices apropiados en las columnas frecuentemente consultadas.',
            'Se corrigió el problema implementando un sistema de retry con backoff exponencial.',
            'La solución fue modificar la configuración del worker para aumentar el timeout.',
            'Se implementó un sistema de validación más robusto utilizando un schema validator.',
            'Se añadió manejo de excepciones específico para este caso de error.',
            'La solución implicó refactorizar el código para utilizar async/await correctamente.',
        ];

        return $solutions[array_rand($solutions)];
    }
}
