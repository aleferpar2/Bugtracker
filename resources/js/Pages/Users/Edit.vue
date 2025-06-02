<template>
    <AppLayout :title="'Editar: ' + user.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Usuario
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="space-y-6">
                                <!-- Avatar y Nombre -->
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img
                                            :src="user.profile_photo_url"
                                            :alt="user.name"
                                            class="h-16 w-16 rounded-full object-cover"
                                        >
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ user.name }}</h3>
                                        <p class="text-sm text-gray-500">{{ user.email }}</p>
                                    </div>
                                </div>

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

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.email }}
                                    </div>
                                </div>

                                <!-- Contraseña (opcional) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nueva Contraseña (opcional)</label>
                                    <input
                                        v-model="form.password"
                                        type="password"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.password }}
                                    </div>
                                </div>

                                <!-- Confirmar Contraseña -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                                    <input
                                        v-model="form.password_confirmation"
                                        type="password"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                                <!-- Rol -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Rol</label>
                                    <select
                                        v-model="form.role"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                        <option value="user">Usuario</option>
                                        <option value="admin">Administrador</option>
                                    </select>
                                    <div v-if="form.errors.role" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.role }}
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div>
                                    <label class="flex items-center">
                                        <input
                                            type="checkbox"
                                            v-model="form.is_active"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                        <span class="ml-2 text-sm text-gray-600">Usuario Activo</span>
                                    </label>
                                </div>

                                <!-- Botones -->
                                <div class="flex justify-end space-x-4">
                                    <Link
                                        :href="route('users.index')"
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
    user: Object
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.role,
    is_active: props.user.is_active
});

const submit = () => {
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script> 