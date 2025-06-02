<template>
    <AppLayout title="Notificaciones">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Notificaciones
                </h2>
                <div class="flex space-x-4">
                    <button
                        @click="markAllAsRead"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded"
                        :disabled="!hasUnreadNotifications"
                    >
                        Marcar todas como leídas
                    </button>
                    <button
                        @click="deleteAllNotifications"
                        class="bg-red-100 hover:bg-red-200 text-red-800 font-bold py-2 px-4 rounded"
                        :disabled="notifications.length === 0"
                    >
                        Eliminar todas
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <!-- Filtros -->
                        <div class="mb-6">
                            <div class="flex space-x-4">
                                <select
                                    v-model="typeFilter"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @change="filterNotifications"
                                >
                                    <option value="">Todos los tipos</option>
                                    <option value="bug">Bugs</option>
                                    <option value="comment">Comentarios</option>
                                    <option value="solution">Soluciones</option>
                                    <option value="vote">Votos</option>
                                </select>
                                <select
                                    v-model="readFilter"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    @change="filterNotifications"
                                >
                                    <option value="">Todos los estados</option>
                                    <option value="unread">No leídas</option>
                                    <option value="read">Leídas</option>
                                </select>
                            </div>
                        </div>

                        <!-- Lista de Notificaciones -->
                        <div class="space-y-4">
                            <div
                                v-for="notification in notifications"
                                :key="notification.id"
                                class="p-4 rounded-lg transition-colors duration-200"
                                :class="{
                                    'bg-white': notification.read_at,
                                    'bg-indigo-50': !notification.read_at
                                }"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-4">
                                        <!-- Icono según tipo -->
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 rounded-full flex items-center justify-center"
                                                :class="{
                                                    'bg-red-100 text-red-600': notification.type === 'bug',
                                                    'bg-blue-100 text-blue-600': notification.type === 'comment',
                                                    'bg-green-100 text-green-600': notification.type === 'solution',
                                                    'bg-purple-100 text-purple-600': notification.type === 'vote'
                                                }"
                                            >
                                                <svg
                                                    v-if="notification.type === 'bug'"
                                                    class="w-6 h-6"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <svg
                                                    v-else-if="notification.type === 'comment'"
                                                    class="w-6 h-6"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                </svg>
                                                <svg
                                                    v-else-if="notification.type === 'solution'"
                                                    class="w-6 h-6"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <svg
                                                    v-else-if="notification.type === 'vote'"
                                                    class="w-6 h-6"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Contenido -->
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-900">
                                                {{ notification.data.message }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ formatDate(notification.created_at) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Acciones -->
                                    <div class="flex items-center space-x-2">
                                        <Link
                                            v-if="notification.data.link"
                                            :href="notification.data.link"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm"
                                            @click="markAsRead(notification)"
                                        >
                                            Ver
                                        </Link>
                                        <button
                                            @click="deleteNotification(notification)"
                                            class="text-red-600 hover:text-red-900 text-sm"
                                        >
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensaje cuando no hay notificaciones -->
                            <div
                                v-if="notifications.length === 0"
                                class="text-center py-12"
                            >
                                <svg
                                    class="mx-auto h-12 w-12 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay notificaciones</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    No tienes notificaciones en este momento.
                                </p>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            <Pagination :links="links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    notifications: Array,
    links: Object
});

const typeFilter = ref('');
const readFilter = ref('');

const form = useForm({
    type: typeFilter.value,
    read: readFilter.value
});

const hasUnreadNotifications = computed(() => {
    return props.notifications.some(notification => !notification.read_at);
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const filterNotifications = () => {
    form.get(route('notifications.index'), {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};

const markAsRead = (notification) => {
    if (!notification.read_at) {
        form.put(route('notifications.markAsRead', notification.id), {
            preserveScroll: true
        });
    }
};

const markAllAsRead = () => {
    form.put(route('notifications.markAllAsRead'), {
        preserveScroll: true
    });
};

const deleteNotification = (notification) => {
    if (confirm('¿Estás seguro de que deseas eliminar esta notificación?')) {
        form.delete(route('notifications.destroy', notification.id), {
            preserveScroll: true
        });
    }
};

const deleteAllNotifications = () => {
    if (confirm('¿Estás seguro de que deseas eliminar todas las notificaciones?')) {
        form.delete(route('notifications.destroyAll'), {
            preserveScroll: true
        });
    }
};
</script> 