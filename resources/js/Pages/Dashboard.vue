<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    postsByStatus: Object,
    postsByPriority: Object,
    myPosts: Array,
    assignedPosts: Array,
    recentComments: Array,
    recentSolutions: Array,
    unreadNotifications: Array,
    user: Object,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
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

const getPriorityText = (priority) => {
    const priorityMap = {
        'low': 'Baja',
        'medium': 'Media',
        'high': 'Alta',
        'critical': 'Crítica'
    };
    return priorityMap[priority] || priority;
};

const truncateText = (text, maxLength) => {
    if (text.length <= maxLength) return text;
    return text.substr(0, maxLength) + '...';
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
                <p class="text-gray-600">¡Bienvenido, {{ user.name }}!</p>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Estadísticas Generales -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <!-- Total de Bugs -->
                    <div class="bg-gradient-to-r from-red-500 to-red-600 overflow-hidden shadow-xl rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="text-3xl">🐞</div>
                                </div>
                                <div class="ml-4 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-red-100">Total Bugs</dt>
                                        <dd class="text-2xl font-bold">{{ stats.total_posts }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Usuarios -->
                    <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-xl rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="text-3xl">👥</div>
                                </div>
                                <div class="ml-4 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-green-100">Usuarios</dt>
                                        <dd class="text-2xl font-bold">{{ stats.total_users }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Comentarios -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-xl rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="text-3xl">💬</div>
                                </div>
                                <div class="ml-4 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-blue-100">Comentarios</dt>
                                        <dd class="text-2xl font-bold">{{ stats.total_comments }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Soluciones -->
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 overflow-hidden shadow-xl rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="text-3xl">💡</div>
                                </div>
                                <div class="ml-4 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-yellow-100">Soluciones</dt>
                                        <dd class="text-2xl font-bold">{{ stats.total_solutions }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Topics -->
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 overflow-hidden shadow-xl rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="text-3xl">💭</div>
                                </div>
                                <div class="ml-4 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-purple-100">Topics</dt>
                                        <dd class="text-2xl font-bold">{{ stats.total_topics }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accesos Rápidos -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">🚀 Accesos Rápidos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <Link :href="route('posts.index')" class="flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                <div class="text-2xl mb-2">🐞</div>
                                <span class="text-sm font-medium text-center">Ver Bugs</span>
                            </Link>

                            <Link :href="route('posts.create')" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <div class="text-2xl mb-2">➕</div>
                                <span class="text-sm font-medium text-center">Nuevo Bug</span>
                            </Link>

                            <Link :href="route('community.index')" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <div class="text-2xl mb-2">💭</div>
                                <span class="text-sm font-medium text-center">Comunidad</span>
                            </Link>

                            <Link :href="route('posts.advanced')" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <div class="text-2xl mb-2">🔍</div>
                                <span class="text-sm font-medium text-center">Búsqueda Avanzada</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Gráficos de Estado y Prioridad -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Estados de Bugs -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">🔄 Bugs por Estado</h3>
                            <div class="space-y-2">
                                <div v-for="(count, status) in postsByStatus" :key="status" class="flex justify-between items-center">
                                    <span 
                                        :class="{
                                            'text-green-600': status === 'resolved',
                                            'text-yellow-600': status === 'in_progress',
                                            'text-red-600': status === 'open',
                                            'text-gray-600': status === 'closed'
                                        }"
                                        class="font-medium"
                                    >
                                        {{ getStatusText(status) }}
                                    </span>
                                    <div class="flex items-center space-x-2">
                                        <div 
                                            :class="{
                                                'bg-green-200': status === 'resolved',
                                                'bg-yellow-200': status === 'in_progress',
                                                'bg-red-200': status === 'open',
                                                'bg-gray-200': status === 'closed'
                                            }"
                                            class="h-3 rounded"
                                            :style="{ width: `${(count / stats.total_posts) * 100}px` }"
                                        ></div>
                                        <span class="text-sm font-bold">{{ count }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prioridades de Bugs -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">🎯 Bugs por Prioridad</h3>
                            <div class="space-y-2">
                                <div v-for="(count, priority) in postsByPriority" :key="priority" class="flex justify-between items-center">
                                    <span 
                                        :class="{
                                            'text-blue-600': priority === 'low',
                                            'text-yellow-600': priority === 'medium',
                                            'text-orange-600': priority === 'high',
                                            'text-red-600': priority === 'critical'
                                        }"
                                        class="font-medium"
                                    >
                                        {{ getPriorityText(priority) }}
                                    </span>
                                    <div class="flex items-center space-x-2">
                                        <div 
                                            :class="{
                                                'bg-blue-200': priority === 'low',
                                                'bg-yellow-200': priority === 'medium',
                                                'bg-orange-200': priority === 'high',
                                                'bg-red-200': priority === 'critical'
                                            }"
                                            class="h-3 rounded"
                                            :style="{ width: `${(count / stats.total_posts) * 100}px` }"
                                        ></div>
                                        <span class="text-sm font-bold">{{ count }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mis Bugs y Notificaciones -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Mis Bugs Recientes -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">🐛 Mis Bugs Recientes</h3>
                                <Link :href="route('posts.create')" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    + Nuevo Bug
                                </Link>
                            </div>
                            <div v-if="myPosts.length === 0" class="text-center text-gray-500 py-4">
                                No has creado bugs aún
                            </div>
                            <div v-else class="space-y-3">
                                <div v-for="post in myPosts" :key="post.id" class="border-l-4 border-indigo-400 pl-4 py-2">
                                    <Link :href="route('posts.show', post.id)" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        {{ post.title }}
                                    </Link>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span 
                                            :class="{
                                                'bg-green-100 text-green-800': post.status === 'resolved',
                                                'bg-yellow-100 text-yellow-800': post.status === 'in_progress',
                                                'bg-red-100 text-red-800': post.status === 'open',
                                                'bg-gray-100 text-gray-800': post.status === 'closed'
                                            }"
                                            class="px-2 py-1 rounded text-xs font-medium"
                                        >
                                            {{ getStatusText(post.status) }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ formatDate(post.created_at) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notificaciones Recientes -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">🔔 Notificaciones</h3>
                                <Link :href="route('notifications.index')" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Ver todas
                                </Link>
                            </div>
                            <div v-if="unreadNotifications.length === 0" class="text-center text-gray-500 py-4">
                                No tienes notificaciones pendientes
                            </div>
                            <div v-else class="space-y-3">
                                <div v-for="notification in unreadNotifications" :key="notification.id" class="p-3 bg-blue-50 rounded-lg">
                                    <p class="text-sm text-gray-800">{{ notification.data.message }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ formatDate(notification.created_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actividad Reciente -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Comentarios Recientes -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">💬 Comentarios Recientes</h3>
                            <div v-if="recentComments.length === 0" class="text-center text-gray-500 py-4">
                                No hay comentarios recientes
                            </div>
                            <div v-else class="space-y-3">
                                <div v-for="comment in recentComments" :key="comment.id" class="border-l-4 border-blue-400 pl-4 py-2">
                                    <Link :href="route('posts.show', comment.post.id)" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        {{ comment.post.title }}
                                    </Link>
                                    <p class="text-gray-600 text-sm mt-1">{{ truncateText(comment.content, 60) }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-user"></i> {{ comment.user.name }} - {{ formatDate(comment.created_at) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Soluciones Recientes -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">💡 Soluciones Recientes</h3>
                            <div v-if="recentSolutions.length === 0" class="text-center text-gray-500 py-4">
                                No hay soluciones recientes
                            </div>
                            <div v-else class="space-y-3">
                                <div v-for="solution in recentSolutions" :key="solution.id" class="border-l-4 border-green-400 pl-4 py-2">
                                    <Link :href="route('posts.show', solution.post.id)" class="text-green-600 hover:text-green-800 font-medium text-sm">
                                        {{ solution.post.title }}
                                    </Link>
                                    <p class="text-gray-600 text-sm mt-1">{{ truncateText(solution.description, 60) }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-user"></i> {{ solution.user.name }} - {{ formatDate(solution.created_at) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
