<template>
  <AppLayout title="Búsqueda Avanzada">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Búsqueda Avanzada</h2>
    </template>
    <div class="py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form @submit.prevent="search" class="space-y-6 bg-white p-6 rounded-lg shadow">
          <!-- Búsqueda por texto (obligatorio) -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Buscar <span class="text-red-500">*</span></label>
            <input
              type="text"
              v-model="form.search"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="Buscar en StackOverflow..."
            >
          </div>
          <!-- Filtros avanzados reales -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Tecnología</label>
            <input
              v-model="form.technology"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="Ej: PHP, JavaScript, Python..."
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Aplicación</label>
            <input
              v-model="form.application"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="Ej: Visual Studio Code, XAMPP, etc."
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Versión</label>
            <input
              v-model="form.version"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="Ej: 1.0.0, 2024, etc."
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Año</label>
            <input
              v-model="form.year"
              type="number"
              min="1990"
              max="2100"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="Ej: 2024"
            >
          </div>
          <!-- Botones de acción -->
          <div class="flex justify-end space-x-4">
            <button type="button" @click="resetForm" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Limpiar filtros</button>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Buscar</button>
          </div>
        </form>
        <!-- Resultados de la búsqueda (StackOverflow) -->
        <div class="mt-8">
          <div v-if="loading" class="text-center text-gray-500">Buscando en StackOverflow...</div>
          <div v-else-if="error" class="text-center text-red-500">{{ error }}</div>
          <div v-else-if="results.length > 0">
            <h3 class="text-lg font-semibold mb-4">Resultados en StackOverflow</h3>
            <ul class="space-y-4">
              <li v-for="item in results" :key="item.link" class="bg-gray-50 p-4 rounded shadow">
                <a :href="item.link" target="_blank" class="text-indigo-700 font-bold hover:underline">{{ item.title }}</a>
                <div class="text-sm text-gray-600 mt-1">
                  <span v-if="item.owner">Por {{ item.owner.display_name }} · </span>
                  <span>Puntuación: {{ item.score }}</span>
                  <span v-if="item.is_answered" class="ml-2 text-green-600">✔ Respondida</span>
                </div>
                <div class="text-xs text-gray-400 mt-1">Etiquetas: {{ item.tags.join(', ') }}</div>
              </li>
            </ul>
          </div>
          <div v-else class="text-center text-gray-400">No hay resultados para esta búsqueda.</div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { useStackOverflowSearch } from '@/composables/useStackOverflowSearch';

const form = ref({
  search: '',
  technology: '',
  application: '',
  version: '',
  year: ''
});

const { results, loading, error, search: searchStackOverflow } = useStackOverflowSearch();

const statuses = {
  open: 'Abierto',
  in_progress: 'En Progreso',
  resolved: 'Resuelto',
  closed: 'Cerrado',
};
const priorities = {
  low: 'Baja',
  medium: 'Media',
  high: 'Alta',
  urgent: 'Urgente',
};
// Simulación de datos (en integración real, vendrán por props o petición API)
const categories = ref([
  { id: 1, name: 'Frontend' },
  { id: 2, name: 'Backend' },
]);
const users = ref([
  { id: 1, name: 'Usuario 1' },
  { id: 2, name: 'Usuario 2' },
]);

function search() {
  if (!form.value.search.trim()) return;
  // Concatenar filtros avanzados si tienen valor
  let query = form.value.search;
  if (form.value.technology) query += ' ' + form.value.technology;
  if (form.value.application) query += ' ' + form.value.application;
  if (form.value.version) query += ' ' + form.value.version;
  if (form.value.year) query += ' ' + form.value.year;
  searchStackOverflow(query);
}
function resetForm() {
  form.value = {
    search: '',
    technology: '',
    application: '',
    version: '',
    year: ''
  };
}
function goToDetail(questionId) {
  router.visit(route('posts.stackoverflow.result', { questionId }));
}
</script> 