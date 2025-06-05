<template>
    <AppLayout title="Detalles del Bug">
        <div class="min-h-screen bg-gradient-to-br from-purple-800 via-purple-900 to-purple-950 py-12">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Bug Card -->
                <div class="relative group mb-8">
                    <!-- Multi-layer glow effect -->
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-400/10 via-blue-400/5 to-purple-400/10 rounded-xl blur-md opacity-75"></div>
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500/5 to-blue-500/5 rounded-xl blur-lg opacity-50 animate-pulse"></div>

                    <div class="relative bg-purple-950/30 backdrop-blur-md border border-purple-500/5 rounded-xl p-8">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="bg-gradient-to-br from-purple-900/30 to-purple-800/30 rounded-lg p-3 border border-purple-500/5">
                                    <span class="text-2xl text-white/90">#{{ post.id }}</span>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-white/90 mb-2">{{ post.title }}</h1>
                                    <div class="flex items-center space-x-3 text-sm text-white/60">
                                        <span>Por {{ post.user.name }}</span>
                                        <span>•</span>
                                        <span>{{ new Date(post.created_at).toLocaleDateString() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full shadow-glow"
                                    :class="{
                                        'bg-green-400 shadow-green-400/50': post.status === 'resolved',
                                        'bg-yellow-400 shadow-yellow-400/50': post.status === 'in_progress',
                                        'bg-red-400 shadow-red-400/50': post.status === 'open',
                                    }"
                                ></span>
                                <span class="text-white/70">{{ post.status }}</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-white/80 mb-2">Descripción</h3>
                            <p class="text-white/70">{{ post.description }}</p>
                        </div>

                        <!-- Meta Info -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-purple-900/20 rounded-lg p-4 border border-purple-500/5">
                                <span class="text-sm text-white/50">Prioridad</span>
                                <div class="mt-1 flex items-center">
                                    <span class="px-2 py-0.5 rounded-full text-sm"
                                        :class="{
                                            'bg-gradient-to-r from-red-500/10 to-red-400/10 text-white/70': post.priority === 'high',
                                            'bg-gradient-to-r from-yellow-500/10 to-yellow-400/10 text-white/70': post.priority === 'medium',
                                            'bg-gradient-to-r from-blue-500/10 to-blue-400/10 text-white/70': post.priority === 'low'
                                        }"
                                    >
                                        {{ post.priority }}
                                    </span>
                                </div>
                            </div>

                            <div class="bg-purple-900/20 rounded-lg p-4 border border-purple-500/5">
                                <span class="text-sm text-white/50">Categorías</span>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    <span v-for="category in post.categories" :key="category.id"
                                        class="px-2 py-0.5 rounded-full text-xs bg-purple-500/10 text-white/70">
                                        {{ category.name }}
                                    </span>
                                </div>
                            </div>

                            <div class="bg-purple-900/20 rounded-lg p-4 border border-purple-500/5">
                                <span class="text-sm text-white/50">Tecnología</span>
                                <div class="mt-1 text-white/70">
                                    {{ post.technology || 'No especificada' }}
                                </div>
                            </div>

                            <div class="bg-purple-900/20 rounded-lg p-4 border border-purple-500/5">
                                <span class="text-sm text-white/50">Versión</span>
                                <div class="mt-1 text-white/70">
                                    {{ post.version || 'No especificada' }}
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4">
                            <button v-if="can.update"
                                class="btn btn-secondary"
                                @click="editPost"
                            >
                                Editar
                            </button>
                            <button v-if="can.delete"
                                class="btn btn-danger"
                                @click="confirmDelete"
                            >
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="relative group mb-8">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-400/10 to-blue-400/10 rounded-xl blur-md opacity-75"></div>
                    
                    <div class="relative bg-purple-950/30 backdrop-blur-md border border-purple-500/5 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white/80 mb-6">Comentarios</h3>

                        <!-- Comment Form -->
                        <form v-if="can.comment" @submit.prevent="submitComment" class="mb-6">
                            <textarea
                                v-model="commentForm.content"
                                rows="3"
                                class="input mb-4"
                                placeholder="Escribe un comentario..."
                            ></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="btn btn-primary" :disabled="commentForm.processing">
                                    Comentar
                                </button>
                            </div>
                        </form>

                        <!-- Comments List -->
                        <div class="space-y-4">
                            <div v-for="comment in post.comments" :key="comment.id"
                                class="bg-purple-900/20 rounded-lg p-4 border border-purple-500/5">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium text-white/80">{{ comment.user.name }}</span>
                                        <span class="text-sm text-white/50">{{ new Date(comment.created_at).toLocaleDateString() }}</span>
                                    </div>
                                    <button v-if="can.delete" @click="deleteComment(comment)"
                                        class="text-white/50 hover:text-white/80">
                                        <span class="sr-only">Eliminar</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-white/70">{{ comment.content }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solutions Section -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-400/10 to-blue-400/10 rounded-xl blur-md opacity-75"></div>
                    
                    <div class="relative bg-purple-950/30 backdrop-blur-md border border-purple-500/5 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white/80 mb-6">Soluciones Propuestas</h3>

                        <!-- Solution Form -->
                        <form @submit.prevent="submitSolution" class="mb-6">
                            <textarea
                                v-model="solutionForm.description"
                                rows="3"
                                class="input mb-4"
                                placeholder="Propón una solución..."
                            ></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="btn btn-primary" :disabled="solutionForm.processing">
                                    Proponer Solución
                                </button>
                            </div>
                        </form>

                        <!-- Solutions List -->
                        <div class="space-y-4">
                            <div v-for="solution in post.solutions" :key="solution.id"
                                class="bg-purple-900/20 rounded-lg p-4 border border-purple-500/5">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium text-white/80">{{ solution.user.name }}</span>
                                        <span class="text-sm text-white/50">{{ new Date(solution.created_at).toLocaleDateString() }}</span>
                                        <span v-if="solution.is_accepted"
                                            class="px-2 py-0.5 rounded-full text-xs bg-green-500/20 text-green-300">
                                            Aceptada ✓
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button v-if="!solution.is_accepted && can.update"
                                            @click="acceptSolution(solution)"
                                            class="text-white/50 hover:text-white/80">
                                            Aceptar
                                        </button>
                                        <button v-if="can.delete" @click="deleteSolution(solution)"
                                            class="text-white/50 hover:text-white/80">
                                            <span class="sr-only">Eliminar</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-white/70">{{ solution.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import axios from 'axios';

const page = usePage();
const props = defineProps({
    post: Object,
    can: Object
});

const commentForm = useForm({
    content: ''
});

const solutionForm = useForm({
    description: ''
});

const hasVoted = ref(props.post.has_voted);

const editingSolutionId = ref(null);
const editSolutionForm = useForm({ description: '' });

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

const getPriorityText = (priority) => {
    const priorityMap = {
        'low': 'Baja',
        'medium': 'Media',
        'high': 'Alta',
        'critical': 'Crítica'
    };
    return priorityMap[priority] || priority;
};

const vote = () => {
    // Por ahora deshabilitar votación hasta implementar rutas
    console.log('Votación no implementada aún');
};

const submitComment = () => {
    console.log('Submitting comment:', commentForm.content);
    console.log('Post ID:', props.post.id);
    console.log('Route:', route('posts.comments.store', props.post.id));
    
    commentForm.post(route('posts.comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Comment submitted successfully');
            commentForm.reset();
        },
        onError: (errors) => {
            console.error('Comment submission failed:', errors);
        }
    });
};

const deleteComment = (comment) => {
    if (confirm('¿Estás seguro de que quieres eliminar este comentario?')) {
        router.delete(route('posts.comments.destroy', [props.post.id, comment.id]), {
            preserveScroll: true,
        });
    }
};

const acceptSolution = (solution) => {
    if (confirm('¿Estás seguro de que quieres aceptar esta solución?')) {
        router.patch(route('posts.solutions.update-status', [props.post.id, solution.id]), 
            { is_accepted: true },
            { preserveScroll: true }
        );
    }
};

const confirmDelete = () => {
    if (confirm('¿Estás seguro de que quieres eliminar este bug?')) {
        router.delete(route('posts.destroy', props.post.id));
    }
};

function submitSolution() {
    console.log('Submitting solution:', solutionForm.description);
    console.log('Post ID:', props.post.id);
    console.log('Route:', route('posts.solutions.store', props.post.id));
    
    solutionForm.post(route('posts.solutions.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Solution submitted successfully');
            solutionForm.reset();
        },
        onError: (errors) => {
            console.error('Solution submission failed:', errors);
        }
    });
}

function canEditOrDeleteSolution(solution) {
    // El usuario puede editar/eliminar si es el autor o admin
    const user = page.props.auth?.user;
    return user && (solution.user.id === user.id || user.role === 'admin');
}

function startEdit(solution) {
    editingSolutionId.value = solution.id;
    editSolutionForm.description = solution.description;
}

function cancelEdit() {
    editingSolutionId.value = null;
    editSolutionForm.reset();
}

function updateSolution(solution) {
    editSolutionForm.put(route('posts.solutions.update', [props.post.id, solution.id]), {
        preserveScroll: true,
        onSuccess: () => {
            editingSolutionId.value = null;
            editSolutionForm.reset();
        },
    });
}

function deleteSolution(solution) {
    if (confirm('¿Estás seguro de que quieres eliminar esta solución?')) {
        router.delete(route('posts.solutions.destroy', [props.post.id, solution.id]), {
            preserveScroll: true,
        });
    }
}

function canAcceptSolution(solution) {
    // Solo el autor del post o admin puede aceptar una solución
    const user = page.props.auth?.user;
    return user && (user.id === props.post.user.id || user.role === 'admin');
}
</script>

<style scoped>
.input {
    @apply w-full bg-purple-900/20 border border-purple-500/5 rounded-lg px-4 py-2.5 
        text-white/80 placeholder-white/30
        focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500/20 
        hover:bg-purple-900/30 transition-all duration-300;
}

.btn {
    @apply px-6 py-2.5 rounded-lg font-medium transition-all duration-300
        disabled:opacity-50 disabled:cursor-not-allowed;
}

.btn-primary {
    @apply bg-gradient-to-r from-purple-500/80 via-blue-500/80 to-purple-500/80 
        text-white/90 hover:from-purple-500/90 hover:via-blue-500/90 hover:to-purple-500/90
        hover:shadow-lg hover:shadow-purple-500/10;
}

.btn-secondary {
    @apply bg-purple-500/5 text-white/70
        hover:bg-purple-500/10 hover:text-white/80;
}

.btn-danger {
    @apply bg-gradient-to-r from-red-500/20 to-red-600/20 text-white/70
        hover:from-red-500/30 hover:to-red-600/30 hover:text-white/80;
}

.shadow-glow {
    box-shadow: 0 0 8px currentColor;
}

@keyframes subtle-pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.3; }
}

.animate-subtle-pulse {
    animation: subtle-pulse 3s ease-in-out infinite;
}
</style> 