<template>
    <AppLayout title="Usuarios">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Usuarios
                </h2>
                <Link
                    :href="route('users.create')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                >
                    Nuevo Usuario
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <!-- Búsqueda y Filtros -->
                        <div class="mb-6">
                            <div class="flex space-x-4">
                                <div class="flex-1">
                                    <input
                                        v-model="search"
                                        type="text"
                                        placeholder="Buscar usuarios..."
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        @input="debouncedSearch"
                                    >
                                </div>
                                <select
                                    v-model="roleFilter"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @change="debouncedSearch"
                                >
                                    <option value="">Todos los roles</option>
                                    <option value="admin">Administrador</option>
                                    <option value="user">Usuario</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tabla de Usuarios -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rol
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="user in users.data" :key="user.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img
                                                        :src="user.profile_photo_url"
                                                        :alt="user.name"
                                                        class="h-10 w-10 rounded-full"
                                                    >
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ user.name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ user.email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-purple-100 text-purple-800': user.role === 'admin',
                                                    'bg-green-100 text-green-800': user.role === 'user'
                                                }"
                                            >
                                                {{ user.role === 'admin' ? 'Administrador' : 'Usuario' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800': user.is_active,
                                                    'bg-red-100 text-red-800': !user.is_active
                                                }"
                                            >
                                                {{ user.is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <Link
                                                    :href="route('users.edit', user.id)"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                >
                                                    Editar
                                                </Link>
                                                <button
                                                    @click="confirmDelete(user)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            <Pagination :links="users.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación de Eliminación -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 max-w-sm w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Eliminación</h3>
                <p class="text-sm text-gray-500 mb-4">
                    ¿Estás seguro de que deseas eliminar a {{ userToDelete?.name }}? Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-end space-x-4">
                    <button
                        @click="showDeleteModal = false"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteUser"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                        :disabled="form.processing"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    users: Object,
    filters: Object
});

const search = ref(props.filters.search || '');
const roleFilter = ref(props.filters.role || '');
const showDeleteModal = ref(false);
const userToDelete = ref(null);

const form = useForm({
    search: search.value,
    role: roleFilter.value
});

const debouncedSearch = debounce(() => {
    form.get(route('users.index'), {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}, 300);

const confirmDelete = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const deleteUser = () => {
    form.delete(route('users.destroy', userToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            userToDelete.value = null;
        },
    });
};
</script> 