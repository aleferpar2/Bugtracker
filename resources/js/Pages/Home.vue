<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const search = ref('');

function submitSearch() {
  if (search.value.trim()) {
    router.get(route('posts.index', { search: search.value }));
  }
}
</script>

<template>
  <AppLayout title="Inicio">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bienvenido a BugTracker</h2>
    </template>
    <div class="py-12 bg-gray-50 min-h-screen">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Buscador global -->
        <form @submit.prevent="submitSearch" class="mb-10">
          <div class="flex items-center gap-2">
            <input
              v-model="search"
              type="text"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 text-lg"
              placeholder="Buscar bugs, errores, soluciones..."
            />
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-md text-lg">
              Buscar
            </button>
            <Link :href="route('posts.advanced')" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-4 py-3 rounded-md text-lg flex items-center gap-2">
              <span>🔎</span> Búsqueda avanzada
            </Link>
          </div>
        </form>
        <!-- Accesos directos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
          <Link :href="route('posts.index')" class="block p-6 bg-white rounded-lg shadow hover:bg-indigo-50 transition-colors text-center">
            <div class="text-4xl mb-2">🐞</div>
            <div class="text-lg font-bold">Bugs</div>
            <div class="text-gray-600 text-sm">Explora y reporta errores</div>
          </Link>
          <Link :href="route('categories.index')" class="block p-6 bg-white rounded-lg shadow hover:bg-blue-50 transition-colors text-center">
            <div class="text-4xl mb-2">📂</div>
            <div class="text-lg font-bold">Categorías</div>
            <div class="text-gray-600 text-sm">Filtra por tecnología o tema</div>
          </Link>
          <Link :href="route('dashboard')" class="block p-6 bg-white rounded-lg shadow hover:bg-green-50 transition-colors text-center">
            <div class="text-4xl mb-2">📊</div>
            <div class="text-lg font-bold">Dashboard</div>
            <div class="text-gray-600 text-sm">Resumen y estadísticas</div>
          </Link>
        </div>
        <!-- Últimos bugs destacados (opcional, placeholder) -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold mb-4">Últimos bugs reportados</h3>
          <div class="text-gray-500 text-sm">(Aquí puedes mostrar una lista de los últimos bugs destacados...)</div>
        </div>
      </div>
    </div>
  </AppLayout>
</template> 