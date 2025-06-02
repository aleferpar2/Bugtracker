<template>
    <AppLayout :title="'Editar: ' + post.title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Bug
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="space-y-6">
                                <!-- Título -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Título</label>
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                    <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.title }}
                                    </div>
                                </div>

                                <!-- Descripción -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                    <textarea
                                        v-model="form.description"
                                        rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    ></textarea>
                                    <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.description }}
                                    </div>
                                </div>

                                <!-- Categoría -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Categoría</label>
                                    <select
                                        v-model="form.category_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                        <option value="">Selecciona una categoría</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.category_id }}
                                    </div>
                                </div>

                                <!-- Prioridad -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Prioridad</label>
                                    <select
                                        v-model="form.priority"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                        <option value="low">Baja</option>
                                        <option value="medium">Media</option>
                                        <option value="high">Alta</option>
                                        <option value="critical">Crítica</option>
                                    </select>
                                    <div v-if="form.errors.priority" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.priority }}
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                                    <select
                                        v-model="form.status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                        <option value="open">Abierto</option>
                                        <option value="in_progress">En Progreso</option>
                                        <option value="resolved">Resuelto</option>
                                        <option value="closed">Cerrado</option>
                                    </select>
                                    <div v-if="form.errors.status" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.status }}
                                    </div>
                                </div>

                                <!-- Tecnología -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tecnología</label>
                                    <input
                                        v-model="form.technology"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Ej: PHP, JavaScript, Python..."
                                    >
                                    <div v-if="form.errors.technology" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.technology }}
                                    </div>
                                </div>
                                <!-- Aplicación -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Aplicación</label>
                                    <input
                                        v-model="form.application"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Ej: Visual Studio Code, XAMPP, etc."
                                    >
                                    <div v-if="form.errors.application" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.application }}
                                    </div>
                                </div>
                                <!-- Versión -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Versión</label>
                                    <input
                                        v-model="form.version"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Ej: 1.0.0, 2024, etc."
                                    >
                                    <div v-if="form.errors.version" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.version }}
                                    </div>
                                </div>
                                <!-- Año -->
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
                                    <div v-if="form.errors.year" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.year }}
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="flex justify-end space-x-4">
                                    <Link
                                        :href="route('posts.show', post.id)"
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded"
                                    >
                                        Cancelar
                                    </Link>
                                    <button
                                        type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                                        :disabled="form.processing"
                                    >
                                        Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    post: Object,
    categories: Array
});

const form = useForm({
    title: props.post.title,
    description: props.post.description,
    category_id: props.post.category_id,
    priority: props.post.priority,
    status: props.post.status,
    technology: props.post.technology,
    application: props.post.application,
    version: props.post.version,
    year: props.post.year
});

const submit = () => {
    form.put(route('posts.update', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Redirigir a la vista del post
            window.location.href = route('posts.show', props.post.id);
        },
    });
};
</script> 