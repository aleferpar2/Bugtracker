<template>
    <AppLayout :title="post.title">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ post.title }}
                </h2>
                <div class="flex space-x-2">
                    <Link
                        v-if="can.update"
                        :href="route('posts.edit', post.id)"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Editar
                    </Link>
                    <button
                        v-if="can.delete"
                        @click="confirmDelete"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Información del Bug -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4 mb-4">
                                    <span
                                        :class="{
                                            'bg-green-100 text-green-800': post.status === 'resolved',
                                            'bg-yellow-100 text-yellow-800': post.status === 'in_progress',
                                            'bg-red-100 text-red-800': post.status === 'open',
                                            'bg-gray-100 text-gray-800': post.status === 'closed'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-medium"
                                    >
                                        {{ getStatusText(post.status) }}
                                    </span>
                                    <span
                                        :class="{
                                            'bg-blue-100 text-blue-800': post.priority === 'low',
                                            'bg-yellow-100 text-yellow-800': post.priority === 'medium',
                                            'bg-orange-100 text-orange-800': post.priority === 'high',
                                            'bg-red-100 text-red-800': post.priority === 'critical'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-medium"
                                    >
                                        {{ getPriorityText(post.priority) }}
                                    </span>
                                </div>
                                <p class="text-gray-600 whitespace-pre-line">{{ post.description }}</p>
                                <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500">
                                    <span>
                                        <i class="fas fa-user"></i> {{ post.user.name }}
                                    </span>
                                    <span>
                                        <i class="fas fa-folder"></i> {{ post.category.name }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock"></i> {{ formatDate(post.created_at) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-center space-y-2">
                                <button
                                    @click="vote"
                                    :class="{
                                        'text-indigo-600': hasVoted,
                                        'text-gray-400 hover:text-indigo-600': !hasVoted
                                    }"
                                    class="focus:outline-none"
                                >
                                    <i class="fas fa-arrow-up text-xl"></i>
                                </button>
                                <span class="text-lg font-semibold">{{ post.votes_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Soluciones -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Soluciones</h3>
                        <div v-if="post.solutions.length === 0" class="text-center text-gray-500 py-4">
                            No hay soluciones propuestas
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="solution in post.solutions" :key="solution.id" :class="[ 'border-b pb-4', solution.is_accepted ? 'bg-green-50 border-green-400' : '' ]">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div v-if="editingSolutionId === solution.id">
                                            <textarea v-model="editSolutionForm.description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                            <div class="flex space-x-2 mt-2">
                                                <button @click="updateSolution(solution)" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1 px-3 rounded" :disabled="editSolutionForm.processing">Guardar</button>
                                                <button @click="cancelEdit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded">Cancelar</button>
                                            </div>
                                            <div v-if="editSolutionForm.errors.description" class="text-red-500 text-sm mt-1">{{ editSolutionForm.errors.description }}</div>
                                        </div>
                                        <div v-else>
                                            <p class="text-gray-600 flex items-center">
                                              <span v-if="solution.is_accepted" class="text-green-600 mr-2" title="Solución aceptada"><i class="fas fa-check-circle"></i></span>
                                              {{ solution.description }}
                                            </p>
                                            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                                <span><i class="fas fa-user"></i> {{ solution.user.name }}</span>
                                                <span><i class="fas fa-clock"></i> {{ formatDate(solution.created_at) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex flex-col space-y-2">
                                        <button v-if="canAcceptSolution(solution) && !solution.is_accepted" @click="acceptSolution(solution)" class="text-green-600 hover:text-green-800">Aceptar</button>
                                        <button v-if="canEditOrDeleteSolution(solution) && editingSolutionId !== solution.id" @click="startEdit(solution)" class="text-indigo-600 hover:text-indigo-800">Editar</button>
                                        <button v-if="canEditOrDeleteSolution(solution)" @click="deleteSolution(solution)" class="text-red-600 hover:text-red-800">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Formulario para nueva solución -->
                        <form v-if="can.comment" @submit.prevent="submitSolution" class="mt-8">
                            <div class="mb-4">
                                <textarea
                                    v-model="solutionForm.description"
                                    rows="3"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Propón una solución..."
                                    required
                                ></textarea>
                                <div v-if="solutionForm.errors.description" class="text-red-500 text-sm mt-1">
                                    {{ solutionForm.errors.description }}
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                    :disabled="solutionForm.processing"
                                >
                                    Proponer solución
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Comentarios -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Comentarios</h3>
                        
                        <!-- Formulario de comentario -->
                        <form v-if="can.comment" @submit.prevent="submitComment" class="mb-6">
                            <div class="mb-4">
                                <textarea
                                    v-model="commentForm.content"
                                    rows="3"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Escribe un comentario..."
                                    required
                                ></textarea>
                                <div v-if="commentForm.errors.content" class="text-red-500 text-sm mt-1">
                                    {{ commentForm.errors.content }}
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                                    :disabled="commentForm.processing"
                                >
                                    Comentar
                                </button>
                            </div>
                        </form>

                        <!-- Lista de comentarios -->
                        <div v-if="post.comments.length === 0" class="text-center text-gray-500 py-4">
                            No hay comentarios
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="comment in post.comments" :key="comment.id" class="border-b pb-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="text-gray-600">{{ comment.content }}</p>
                                        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                            <span>
                                                <i class="fas fa-user"></i> {{ comment.user.name }}
                                            </span>
                                            <span>
                                                <i class="fas fa-clock"></i> {{ formatDate(comment.created_at) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div v-if="can.delete" class="ml-4">
                                        <button
                                            @click="deleteComment(comment)"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';

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
    if (!hasVoted.value) {
        axios.post(route('posts.vote', props.post.id))
            .then(() => {
                hasVoted.value = true;
                props.post.votes_count++;
            });
    } else {
        axios.delete(route('posts.vote', props.post.id))
            .then(() => {
                hasVoted.value = false;
                props.post.votes_count--;
            });
    }
};

const submitComment = () => {
    commentForm.post(route('posts.comments.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
        },
    });
};

const deleteComment = (comment) => {
    if (confirm('¿Estás seguro de que quieres eliminar este comentario?')) {
        axios.delete(route('posts.comments.destroy', [props.post.id, comment.id]))
            .then(() => {
                const index = props.post.comments.findIndex(c => c.id === comment.id);
                if (index > -1) {
                    props.post.comments.splice(index, 1);
                }
            });
    }
};

const acceptSolution = (solution) => {
    if (confirm('¿Estás seguro de que quieres aceptar esta solución?')) {
        axios.put(route('posts.solutions.update', [props.post.id, solution.id]), { is_accepted: true })
            .then(() => {
                props.post.solutions.forEach(s => { s.is_accepted = false; });
                solution.is_accepted = true;
                props.post.status = 'resolved';
            });
    }
};

const confirmDelete = () => {
    if (confirm('¿Estás seguro de que quieres eliminar este bug?')) {
        axios.delete(route('posts.destroy', props.post.id))
            .then(() => {
                window.location.href = route('posts.index');
            });
    }
};

function submitSolution() {
    solutionForm.post(route('posts.solutions.store', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            solutionForm.reset();
        },
    });
}

function canEditOrDeleteSolution(solution) {
    // El usuario puede editar/eliminar si es el autor o admin
    return solution.user.id === $page.props.auth.user.id || $page.props.auth.user.role === 'admin';
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
        axios.delete(route('posts.solutions.destroy', [props.post.id, solution.id]))
            .then(() => {
                // Elimina la solución del array localmente para feedback inmediato
                const idx = props.post.solutions.findIndex(s => s.id === solution.id);
                if (idx > -1) props.post.solutions.splice(idx, 1);
            });
    }
}

function canAcceptSolution(solution) {
    // Solo el autor del post o admin puede aceptar una solución
    return $page.props.auth.user.id === props.post.user.id || $page.props.auth.user.role === 'admin';
}
</script> 