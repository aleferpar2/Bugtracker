<template>
    <AppLayout :title="'Editar: ' + category.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Categoría
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="space-y-6">
                                <!-- Nombre -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.name }}
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

                                <!-- Color -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Color</label>
                                    <div class="mt-1 flex space-x-2">
                                        <button
                                            v-for="color in colors"
                                            :key="color"
                                            type="button"
                                            @click="form.color = color"
                                            class="w-8 h-8 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            :class="{
                                                'ring-2 ring-offset-2 ring-indigo-500': form.color === color,
                                                'bg-red-500': color === 'red',
                                                'bg-blue-500': color === 'blue',
                                                'bg-green-500': color === 'green',
                                                'bg-yellow-500': color === 'yellow',
                                                'bg-purple-500': color === 'purple',
                                                'bg-pink-500': color === 'pink',
                                                'bg-indigo-500': color === 'indigo',
                                                'bg-gray-500': color === 'gray'
                                            }"
                                        ></button>
                                    </div>
                                    <div v-if="form.errors.color" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.color }}
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="flex justify-end space-x-4">
                                    <Link
                                        :href="route('categories.index')"
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
    category: Object
});

const form = useForm({
    name: props.category.name,
    description: props.category.description,
    color: props.category.color
});

const colors = ['red', 'blue', 'green', 'yellow', 'purple', 'pink', 'indigo', 'gray'];

const submit = () => {
    form.put(route('categories.update', props.category.id), {
        preserveScroll: true,
        onSuccess: () => {
            window.location.href = route('categories.index');
        },
    });
};
</script> 