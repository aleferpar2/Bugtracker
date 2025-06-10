<template>
  <AppLayout title="Comunidad">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blogs de la Comunidad</h2>
        <Link
          :href="route('community.create')"
          class="relative group overflow-hidden rounded-full px-6 py-2
                 before:content-[''] before:absolute before:inset-0
                 before:bg-gradient-to-b before:from-purple-500 before:to-purple-700
                 hover:before:opacity-90
                 shadow-lg shadow-purple-500/20
                 transform hover:scale-[1.02] active:scale-[0.98]
                 transition-all duration-200"
        >
          <span class="relative z-10 flex items-center gap-2 text-white font-medium">
            <span class="text-xl">💭</span>
            <span>Crear Nuevo Blog</span>
          </span>
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filtros y Búsqueda -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
          <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex-1 w-full">
              <input
                v-model="search"
                type="text"
                placeholder="Buscar blogs..."
                class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                @input="filterTopics"
              >
            </div>
            <div class="flex gap-4">
              <select
                v-model="selectedCategory"
                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                @change="filterTopics"
              >
                <option value="">Todas las categorías</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <select
                v-model="sortBy"
                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                @change="filterTopics"
              >
                <option value="recent">Más recientes</option>
                <option value="popular">Más populares</option>
                <option value="responses">Más respondidos</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Lista de Blogs -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div v-if="!topics.data || topics.data.length === 0" class="p-6 text-center text-gray-500">
            No hay blogs disponibles.
          </div>
          <div v-else class="divide-y divide-gray-200">
            <div v-for="topic in topics.data" :key="topic.id" class="p-6 hover:bg-gray-50">
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <img :src="topic.author.profile_photo_url" :alt="topic.author.name" class="h-10 w-10 rounded-full">
                </div>
                <div class="flex-1 min-w-0">
                  <Link :href="route('community.show', topic.id)" class="text-lg font-medium text-gray-900 hover:text-indigo-600">
                    {{ topic.title }}
                  </Link>
                  <p class="mt-1 text-sm text-gray-500">
                    {{ topic.content.substring(0, 150) }}...
                  </p>
                  <div class="mt-2 flex items-center space-x-4">
                    <div class="flex items-center text-sm text-gray-500">
                      <span>Por {{ topic.author.name }}</span>
                      <span class="mx-1">•</span>
                      <span>{{ topic.created_at }}</span>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                      <span class="bg-gray-100 px-2.5 py-0.5 rounded-full">
                        {{ topic.category.name }}
                      </span>
                    </div>
                  </div>
                </div>
                <div class="flex-shrink-0 flex flex-col items-end space-y-2">
                  <div class="flex items-center space-x-1 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ topic.replies_count }}</span>
                  </div>
                  <div class="flex items-center space-x-1 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                      <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ topic.views_count }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
          <Pagination :links="topics.links" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  topics: {
    type: Object,
    required: true
  },
  categories: {
    type: Array,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({})
  }
});

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');
const sortBy = ref(props.filters.sort || 'recent');

const filterTopics = () => {
  router.get(route('community.index'), {
    search: search.value,
    category: selectedCategory.value,
    sort: sortBy.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['topics']
  });
};

watch([search, selectedCategory, sortBy], () => {
  filterTopics();
});
</script>

<style scoped>
.topic-link:hover {
  text-decoration: none;
}

/* Efecto de brillo en el hover del botón */
.group:hover::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent,
        rgba(255,255,255,0.1),
        transparent
    );
    transform: rotate(45deg);
    animation: shine 1.5s infinite;
}

@keyframes shine {
    0% {
        transform: translateX(-100%) rotate(45deg);
    }
    100% {
        transform: translateX(100%) rotate(45deg);
    }
}
</style> 