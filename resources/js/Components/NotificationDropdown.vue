<template>
    <div class="relative">
        <!-- Botón de Notificaciones -->
        <button
            @click="toggleDropdown"
            class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none"
        >
            <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                />
            </svg>
            <!-- Indicador de notificaciones no leídas -->
            <span
                v-if="unreadCount > 0"
                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"
            >
                {{ unreadCount }}
            </span>
        </button>

        <!-- Dropdown de Notificaciones -->
        <div
            v-if="isOpen"
            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50"
        >
            <div class="p-4 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Notificaciones</h3>
                    <div class="flex space-x-2">
                        <button
                            v-if="unreadCount > 0"
                            @click="markAllAsRead"
                            class="text-sm text-indigo-600 hover:text-indigo-900"
                        >
                            Marcar todas como leídas
                        </button>
                        <Link
                            :href="route('notifications.index')"
                            class="text-sm text-indigo-600 hover:text-indigo-900"
                        >
                            Ver todas
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Lista de Notificaciones -->
            <div class="max-h-96 overflow-y-auto">
                <div
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="p-4 border-b hover:bg-gray-50 transition-colors duration-200"
                    :class="{ 'bg-indigo-50': !notification.read_at }"
                >
                    <div class="flex items-start space-x-3">
                        <!-- Icono según tipo -->
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center"
                                :class="{
                                    'bg-red-100 text-red-600': notification.type === 'bug',
                                    'bg-blue-100 text-blue-600': notification.type === 'comment',
                                    'bg-green-100 text-green-600': notification.type === 'solution',
                                    'bg-purple-100 text-purple-600': notification.type === 'vote'
                                }"
                            >
                                <svg
                                    v-if="notification.type === 'bug'"
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <svg
                                    v-else-if="notification.type === 'comment'"
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                <svg
                                    v-else-if="notification.type === 'solution'"
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg
                                    v-else-if="notification.type === 'vote'"
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">
                                {{ notification.data.message }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ formatDate(notification.created_at) }}
                            </p>
                        </div>

                        <!-- Acciones -->
                        <div class="flex-shrink-0">
                            <button
                                @click="deleteNotification(notification)"
                                class="text-gray-400 hover:text-gray-600"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mensaje cuando no hay notificaciones -->
                <div
                    v-if="notifications.length === 0"
                    class="p-4 text-center text-gray-500"
                >
                    No tienes notificaciones
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import Echo from 'laravel-echo';

const props = defineProps({
    initialNotifications: Array,
    initialUnreadCount: Number
});

const notifications = ref(props.initialNotifications);
const unreadCount = ref(props.initialUnreadCount);
const isOpen = ref(false);

const form = useForm({});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('es-ES', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const markAllAsRead = () => {
    form.put(route('notifications.markAllAsRead'), {
        preserveScroll: true,
        onSuccess: () => {
            notifications.value = notifications.value.map(notification => ({
                ...notification,
                read_at: new Date()
            }));
            unreadCount.value = 0;
        }
    });
};

const deleteNotification = (notification) => {
    form.delete(route('notifications.destroy', notification.id), {
        preserveScroll: true,
        onSuccess: () => {
            notifications.value = notifications.value.filter(n => n.id !== notification.id);
            if (!notification.read_at) {
                unreadCount.value--;
            }
        }
    });
};

// Escuchar nuevas notificaciones
onMounted(() => {
    window.Echo.private(`App.Models.User.${window.auth.user.id}`)
        .notification((notification) => {
            notifications.value.unshift(notification);
            unreadCount.value++;
        });
});

// Cerrar dropdown al hacer clic fuera
onMounted(() => {
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.notification-dropdown')) {
            isOpen.value = false;
        }
    });
});

onUnmounted(() => {
    document.removeEventListener('click', (e) => {
        if (!e.target.closest('.notification-dropdown')) {
            isOpen.value = false;
        }
    });
});
</script> 