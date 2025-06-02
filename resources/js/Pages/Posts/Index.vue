<template>
    <AppLayout title="Bugs">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Bugs
                </h2>
                <Link
                    v-if="can.create"
                    :href="route('posts.create')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                >
                    Nuevo Bug
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filtros y Búsqueda -->
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <form @submit.prevent="filter" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Búsqueda</label>
                                <input
                                    v-model="form.search"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Buscar bugs..."
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoría</label>
                                <select
                                    v-model="form.category"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Todas</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estado</label>
                                <select
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Todos</option>
                                    <option value="open">Abierto</option>
                                    <option value="in_progress">En Progreso</option>
                                    <option value="resolved">Resuelto</option>
                                    <option value="closed">Cerrado</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ordenar por</label>
                                <select
                                    v-model="form.sort"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="latest">Más recientes</option>
                                    <option value="oldest">Más antiguos</option>
                                    <option value="most_voted">Más votados</option>
                                    <option value="most_commented">Más comentados</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Lista de Bugs -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="posts.data.length === 0" class="text-center text-gray-500 py-4">
                            No se encontraron bugs
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="post in posts.data" :key="post.id" class="border-b pb-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <Link
                                            :href="route('posts.show', post.id)"
                                            class="text-lg font-semibold text-indigo-600 hover:text-indigo-800"
                                        >
                                            {{ post.title }}
                                        </Link>
                                        <p class="text-sm text-gray-600 mt-1">{{ post.description }}</p>
                                        <div class="flex items-center space-x-4 mt-2">
                                            <span class="text-sm text-gray-500">
                                                <i class="fas fa-user"></i> {{ post.user.name }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                <i class="fas fa-folder"></i> {{ post.categories[0]?.name || 'Sin categoría' }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                <i class="fas fa-clock"></i> {{ formatDate(post.created_at) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span
                                            :class="{
                                                'bg-green-100 text-green-800': post.status === 'resolved',
                                                'bg-yellow-100 text-yellow-800': post.status === 'in_progress',
                                                'bg-red-100 text-red-800': post.status === 'open',
                                                'bg-gray-100 text-gray-800': post.status === 'closed'
                                            }"
                                            class="px-2 py-1 rounded-full text-xs font-medium"
                                        >
                                            {{ getStatusText(post.status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            <Pagination :links="posts.links" />
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
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    posts: Object,
    categories: Array,
    filters: Object,
    can: Object
});

const form = useForm({
    search: props.filters.search || '',
    category: props.filters.category || '',
    status: props.filters.status || '',
    sort: props.filters.sort || 'latest'
});

const filter = () => {
    form.get(route('posts.index'), {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getStatusText = (status) => {
    const statusMap = {
        'open': 'Abierto',
        'in_progress': 'En Progreso',
        'resolved': 'Resuelto',
        'closed': 'Cerrado'
    };
    return statusMap[status] || status;
};
</script> 