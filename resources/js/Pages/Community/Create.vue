<template>
  <AppLayout title="Crear Nuevo Blog">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nuevo Blog</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700" for="title">
                Título del Blog
              </label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                required
              >
              <div v-if="form.errors.title" class="mt-2 text-sm text-red-600">
                {{ form.errors.title }}
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700" for="category">
                Categoría
              </label>
              <select
                id="category"
                v-model="form.category_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                required
              >
                <option value="">Selecciona una categoría</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <div v-if="form.errors.category_id" class="mt-2 text-sm text-red-600">
                {{ form.errors.category_id }}
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700" for="content">
                Contenido
              </label>
              <textarea
                id="content"
                v-model="form.content"
                rows="6"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                required
              ></textarea>
              <div v-if="form.errors.content" class="mt-2 text-sm text-red-600">
                {{ form.errors.content }}
              </div>
            </div>

            <div class="flex items-center justify-end mt-6">
              <Link
                :href="route('community.index')"
                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition mr-3"
              >
                Cancelar
              </Link>
              <button
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                :disabled="form.processing"
              >
                Crear Tema
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  categories: {
    type: Array,
    required: true
  }
});

const form = useForm({
  title: '',
  category_id: '',
  content: '',
});

const submit = () => {
  form.post(route('community.store'));
};
</script> 