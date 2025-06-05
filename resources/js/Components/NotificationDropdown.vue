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
                            :disabled="loading"
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
                                :class="getNotificationIconClasses(notification)"
                            >
                                <component :is="getNotificationIcon(notification)" class="w-4 h-4" />
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">
                                {{ getNotificationMessage(notification) }}
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
                                :disabled="loading"
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
                    class="p-8 text-center text-gray-500"
                >
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-sm">No tienes notificaciones</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    initialNotifications: {
        type: Array,
        default: () => []
    },
    initialUnreadCount: {
        type: Number,
        default: 0
    }
});

const notifications = ref(props.initialNotifications);
const unreadCount = ref(props.initialUnreadCount);
const isOpen = ref(false);
const loading = ref(false);

const form = useForm({});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('es-ES', {
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
    
    // Load notifications when opening dropdown
    if (isOpen.value && notifications.value.length === 0) {
        loadNotifications();
    }
};

const loadNotifications = async () => {
    try {
        loading.value = true;
        // For now, we'll just use the initial data
        // In the future, this could fetch fresh notifications
    } catch (error) {
        console.error('Error loading notifications:', error);
    } finally {
        loading.value = false;
    }
};

const markAllAsRead = () => {
    if (loading.value) return;
    
    loading.value = true;
    
    form.post(route('notifications.markAllAsRead'), {
        preserveScroll: true,
        onSuccess: () => {
            notifications.value = notifications.value.map(notification => ({
                ...notification,
                read_at: new Date()
            }));
            unreadCount.value = 0;
        },
        onError: (error) => {
            console.error('Error marking notifications as read:', error);
        },
        onFinish: () => {
            loading.value = false;
        }
    });
};

const deleteNotification = (notification) => {
    if (loading.value) return;
    
    loading.value = true;
    
    form.delete(route('notifications.destroy', notification.id), {
        preserveScroll: true,
        onSuccess: () => {
            notifications.value = notifications.value.filter(n => n.id !== notification.id);
            if (!notification.read_at) {
                unreadCount.value = Math.max(0, unreadCount.value - 1);
            }
        },
        onError: (error) => {
            console.error('Error deleting notification:', error);
        },
        onFinish: () => {
            loading.value = false;
        }
    });
};

const getNotificationMessage = (notification) => {
    if (notification.data && notification.data.message) {
        return notification.data.message;
    }
    return 'Nueva notificación';
};

const getNotificationIconClasses = (notification) => {
    const type = getNotificationType(notification);
    
    const classes = {
        'comment': 'bg-blue-100 text-blue-600',
        'solution': 'bg-green-100 text-green-600', 
        'post': 'bg-red-100 text-red-600',
        'system': 'bg-purple-100 text-purple-600',
        'default': 'bg-gray-100 text-gray-600'
    };
    
    return classes[type] || classes.default;
};

const getNotificationIcon = (notification) => {
    const type = getNotificationType(notification);
    
    // Return string to use with dynamic component
    const icons = {
        'comment': 'CommentIcon',
        'solution': 'CheckCircleIcon',
        'post': 'ExclamationTriangleIcon', 
        'system': 'InformationCircleIcon',
        'default': 'BellIcon'
    };
    
    return icons[type] || icons.default;
};

const getNotificationType = (notification) => {
    if (notification.type) {
        if (notification.type.includes('Comment')) return 'comment';
        if (notification.type.includes('Solution')) return 'solution';
        if (notification.type.includes('Post')) return 'post';
    }
    return 'default';
};

// Icon components (inline SVG)
const CommentIcon = {
    template: `
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
        </svg>
    `
};

const CheckCircleIcon = {
    template: `
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    `
};

const ExclamationTriangleIcon = {
    template: `
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
    `
};

const InformationCircleIcon = {
    template: `
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    `
};

const BellIcon = {
    template: `
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
    `
};

// Register icon components
const iconComponents = {
    CommentIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
    BellIcon
};

// Close dropdown when clicking outside
const handleOutsideClick = (e) => {
    if (!e.target.closest('.relative')) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleOutsideClick);
});

onUnmounted(() => {
    document.removeEventListener('click', handleOutsideClick);
});
</script>

<script>
export default {
    components: {
        CommentIcon: {
            template: `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            `
        },
        CheckCircleIcon: {
            template: `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `
        },
        ExclamationTriangleIcon: {
            template: `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            `
        },
        InformationCircleIcon: {
            template: `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `
        },
        BellIcon: {
            template: `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            `
        }
    }
}
</script> 