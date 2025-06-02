<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <Link
                        :href="route('posts.index')"
                        class="block p-6 bg-red-100 rounded-lg shadow hover:bg-red-200 transition-colors text-center"
                    >
                        <div class="text-4xl mb-2">🐞</div>
                        <div class="text-xl font-bold">Bugs</div>
                        <div class="text-gray-600 text-sm">Gestión de bugs y errores</div>
                    </Link>

                    <Link
                        :href="route('categories.index')"
                        class="block p-6 bg-blue-100 rounded-lg shadow hover:bg-blue-200 transition-colors text-center"
                    >
                        <div class="text-4xl mb-2">📂</div>
                        <div class="text-xl font-bold">Categorías</div>
                        <div class="text-gray-600 text-sm">Organiza los bugs por categoría</div>
                    </Link>

                    <Link
                        :href="route('users.index')"
                        class="block p-6 bg-green-100 rounded-lg shadow hover:bg-green-200 transition-colors text-center"
                    >
                        <div class="text-4xl mb-2">👤</div>
                        <div class="text-xl font-bold">Usuarios</div>
                        <div class="text-gray-600 text-sm">Gestión de usuarios</div>
                    </Link>

                    <Link
                        :href="route('notifications.index')"
                        class="block p-6 bg-yellow-100 rounded-lg shadow hover:bg-yellow-200 transition-colors text-center"
                    >
                        <div class="text-4xl mb-2">🔔</div>
                        <div class="text-xl font-bold">Notificaciones</div>
                        <div class="text-gray-600 text-sm">Tus notificaciones recientes</div>
                    </Link>

                    <Link
                        :href="route('profile.edit')"
                        class="block p-6 bg-purple-100 rounded-lg shadow hover:bg-purple-200 transition-colors text-center"
                    >
                        <div class="text-4xl mb-2">⚙️</div>
                        <div class="text-xl font-bold">Mi Perfil</div>
                        <div class="text-gray-600 text-sm">Configuración y datos personales</div>
                    </Link>

                    <Link
                        :href="route('posts.advanced')"
                        class="block p-6 bg-pink-100 rounded-lg shadow hover:bg-pink-200 transition-colors text-center"
                    >
                        <div class="text-4xl mb-2">🔎</div>
                        <div class="text-xl font-bold">Búsqueda avanzada</div>
                        <div class="text-gray-600 text-sm">Filtra bugs por múltiples criterios</div>
                    </Link>
                    </div>
                <!-- Espacio para estadísticas rápidas o gráficos -->
                <div class="mt-10">
                    <!-- Aquí puedes agregar estadísticas o gráficos en el futuro -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
