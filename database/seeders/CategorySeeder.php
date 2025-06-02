<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Frontend', 'slug' => 'frontend', 'description' => 'Errores en la interfaz de usuario']);
        Category::create(['name' => 'Backend', 'slug' => 'backend', 'description' => 'Errores en la lógica del servidor']);
        Category::create(['name' => 'Seguridad', 'slug' => 'seguridad', 'description' => 'Vulnerabilidades y problemas de seguridad']);
        Category::create(['name' => 'UX/UI', 'slug' => 'ux-ui', 'description' => 'Problemas de experiencia o diseño']);
        Category::create(['name' => 'Quantum Bugs', 'slug' => 'quantum-bugs', 'description' => 'Errores poco comunes o teóricos']);
    }
}
