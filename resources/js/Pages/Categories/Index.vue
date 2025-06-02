<template>
    <AppLayout title="Categorías">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Categorías
                </h2>
                <Link
                    v-if="can.create"
                    :href="route('categories.create')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                >
                    Nueva Categoría
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <!-- Búsqueda -->
                        <div class="mb-6">
                            <input
                                v-model="search"
                                type="text"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Buscar categorías..."
                                @input="filter"
                            >
                        </div>

                        <!-- Lista de Categorías -->
                        <div v-if="categories.data.length === 0" class="text-center text-gray-500 py-4">
                            No se encontraron categorías
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="category in categories.data" :key="category.id" class="border-b pb-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">{{ category.name }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ category.description }}</p>
                                        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                            <span>
                                                <i class="fas fa-bug"></i> {{ category.posts_count }} bugs
                                            </span>
                                            <span>
                                                <i class="fas fa-clock"></i> {{ formatDate(category.created_at) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <Link
                                            v-if="can.update"
                                            :href="route('categories.edit', category.id)"
                                            class="text-indigo-600 hover:text-indigo-800"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <button
                                            v-if="can.delete"
                                            @click="confirmDelete(category)"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            <Pagination :links="categories.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    categories: Object,
    can: Object
});

const search = ref('');

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const filter = debounce(() => {
    window.location.href = route('categories.index', { search: search.value });
}, 300);

const confirmDelete = (category) => {
    if (confirm(`¿Estás seguro de que quieres eliminar la categoría "${category.name}"?`)) {
        axios.delete(route('categories.destroy', category.id))
            .then(() => {
                window.location.reload();
            });
    }
};
</script> 