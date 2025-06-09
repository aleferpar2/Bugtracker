<template>
    <AppLayout title="Bugs">
        <div class="min-h-screen bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con botón de crear -->
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Bugs Reportados</h1>
                    <Link
                        v-if="can.create"
                        :href="route('posts.create')"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-sm transition-colors duration-200"
                    >
                        <span class="mr-2">🐞</span>
                        Reportar Nuevo Bug
                    </Link>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-4 gap-6 mb-12">
                    <!-- Total -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200 hover:shadow-lg hover:shadow-purple-100/50 transition-all duration-300">
                        <div class="flex flex-col items-center">
                            <span class="text-2xl mb-2">🐛</span>
                            <span class="text-3xl font-bold text-purple-700">{{ stats.total }}</span>
                            <span class="text-sm text-purple-600">Total</span>
                        </div>
                    </div>

                    <!-- Resueltos -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200 hover:shadow-lg hover:shadow-green-100/50 transition-all duration-300">
                        <div class="flex flex-col items-center">
                            <span class="text-2xl mb-2">✅</span>
                            <span class="text-3xl font-bold text-green-700">{{ stats.resolved }}</span>
                            <span class="text-sm text-green-600">Resueltos</span>
                        </div>
                    </div>

                    <!-- En Progreso -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200 hover:shadow-lg hover:shadow-yellow-100/50 transition-all duration-300">
                        <div class="flex flex-col items-center">
                            <span class="text-2xl mb-2">⚡</span>
                            <span class="text-3xl font-bold text-yellow-700">{{ stats.in_progress }}</span>
                            <span class="text-sm text-yellow-600">En Progreso</span>
                        </div>
                    </div>

                    <!-- Abiertos -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200 hover:shadow-lg hover:shadow-red-100/50 transition-all duration-300">
                        <div class="flex flex-col items-center">
                            <span class="text-2xl mb-2">🆘</span>
                            <span class="text-3xl font-bold text-red-700">{{ stats.open }}</span>
                            <span class="text-sm text-red-600">Abiertos</span>
                        </div>
                    </div>
                </div>

                <!-- Lista de Bugs -->
                <div class="space-y-4">
                    <div v-for="post in posts" :key="post.id" 
                        class="relative group">
                        <!-- Glow effect -->
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-100 to-purple-200 rounded-xl blur-sm opacity-75 group-hover:opacity-100 transition-all duration-300"></div>
                        
                        <div class="relative bg-white border border-purple-200 rounded-xl overflow-hidden hover:shadow-lg hover:shadow-purple-100/50 transition-all duration-300">
                            <div class="flex items-start p-6">
                                <!-- ID Badge -->
                                <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg flex flex-col items-center justify-center mr-6 border border-purple-200">
                                    <span class="text-2xl font-mono text-purple-700">#{{ post.id }}</span>
                                    <span class="text-xs text-purple-600">{{ post.user.name }}</span>
                                </div>

                                <!-- Content -->
                                <div class="flex-1">
                                    <Link :href="route('posts.show', post.id)" class="block group/title">
                                        <h2 class="text-xl font-semibold text-gray-900 group-hover/title:text-purple-700 transition-colors mb-2">
                                            {{ post.title }}
                                        </h2>
                                        <p class="text-gray-600 text-sm mb-4">
                                            {{ post.description }}
                                        </p>
                                    </Link>

                                    <!-- Meta info -->
                                    <div class="flex items-center space-x-4 text-sm">
                                        <!-- Status -->
                                        <div class="flex items-center">
                                            <span class="w-2 h-2 rounded-full mr-2"
                                                :class="{
                                                    'bg-green-500': post.status === 'resolved',
                                                    'bg-yellow-500': post.status === 'in_progress',
                                                    'bg-red-500': post.status === 'open',
                                                }"
                                            ></span>
                                            <span class="text-gray-600">{{ post.status }}</span>
                                        </div>

                                        <!-- Priority -->
                                        <span class="px-2 py-0.5 rounded-full text-xs"
                                            :class="{
                                                'bg-red-100 text-red-700': post.priority === 'high',
                                                'bg-yellow-100 text-yellow-700': post.priority === 'medium',
                                                'bg-blue-100 text-blue-700': post.priority === 'low'
                                            }"
                                        >
                                            {{ post.priority }}
                                        </span>

                                        <!-- Categories -->
                                        <div class="flex space-x-2">
                                            <span v-for="category in post.categories" :key="category.id"
                                                class="px-2 py-0.5 rounded-full text-xs bg-purple-100 text-purple-700 border border-purple-200 hover:bg-purple-200 transition-colors">
                                                {{ category.name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex-shrink-0">
                                    <button class="p-2 text-gray-400 hover:text-purple-700 hover:bg-purple-50 rounded-lg transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    posts: {
        type: Array,
        default: () => []
    },
    categories: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    can: {
        type: Object,
        default: () => ({})
    },
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            resolved: 0,
            in_progress: 0,
            open: 0
        })
    }
});

// Helpers para status y priority
const statusColor = (status) => ({
    'bg-green-500': status === 'resolved',
    'bg-yellow-500': status === 'in_progress',
    'bg-red-500': status === 'open'
})

const statusText = (status) => ({
    resolved: '✨ Resuelto',
    in_progress: '⚡ En Progreso',
    open: '🔥 Abierto'
})[status]

const priorityColor = (priority) => ({
    'bg-red-100 text-red-700': priority === 'high',
    'bg-yellow-100 text-yellow-700': priority === 'medium',
    'bg-blue-100 text-blue-700': priority === 'low'
})

const priorityText = (priority) => ({
    high: '🚨 Alta',
    medium: '⚠️ Media', 
    low: 'ℹ️ Baja'
})[priority]
</script>

<style scoped>
@keyframes gradient {
    0% { background-position: 0% 50% }
    50% { background-position: 100% 50% }
    100% { background-position: 0% 50% }
}

.animate-gradient {
    animation: gradient 3s ease infinite;
    background-size: 200% 200%;
}
</style> 