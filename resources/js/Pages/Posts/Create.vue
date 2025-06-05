<template>
    <AppLayout title="Nuevo Bug">
        <div class="min-h-screen bg-gradient-to-br from-purple-800 via-purple-900 to-purple-950 py-12">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-purple-300">
                        Reportar Bug 🐛
                    </h1>
                    <p class="mt-2 text-purple-300/70">
                        Ayúdanos a mejorar describiendo el problema en detalle
                    </p>
                </div>

                <!-- Form card with enhanced effects -->
                <div class="relative group">
                    <!-- Multi-layer glow effect -->
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-400/10 via-blue-400/5 to-purple-400/10 rounded-xl blur-md opacity-75"></div>
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500/5 to-blue-500/5 rounded-xl blur-lg opacity-50 animate-pulse"></div>
                    
                    <div class="relative bg-purple-950/30 backdrop-blur-md border border-purple-500/5 rounded-xl p-6 shadow-lg">
                        <form @submit.prevent="submitBug" class="space-y-6">
                            <!-- Form fields with improved styling -->
                            <div>
                                <label class="label">Título del Bug</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    class="input"
                                    placeholder="Ej: App crashea al subir fotos PNG"
                                    :class="{ 'error': form.errors.title }"
                                />
                                <p v-if="form.errors.title" class="error-text">{{ form.errors.title }}</p>
                            </div>

                            <div>
                                <label class="label">Descripción</label>
                                <textarea
                                    v-model="form.description"
                                    rows="4"
                                    class="input"
                                    placeholder="Describe el problema, pasos para reproducirlo, y comportamiento esperado..."
                                    :class="{ 'error': form.errors.description }"
                                ></textarea>
                                <p v-if="form.errors.description" class="error-text">{{ form.errors.description }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="label">Categoría</label>
                                    <select
                                        v-model="form.category_id"
                                        class="input"
                                        :class="{ 'error': form.errors.category_id }"
                                    >
                                        <option value="">Selecciona una categoría</option>
                                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                            {{ cat.name }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.category_id" class="error-text">{{ form.errors.category_id }}</p>
                                </div>

                                <div>
                                    <label class="label">Prioridad</label>
                                    <select
                                        v-model="form.priority"
                                        class="input"
                                        :class="{ 'error': form.errors.priority }"
                                    >
                                        <option value="">Selecciona la prioridad</option>
                                        <option value="low">🟢 Baja - Puede esperar</option>
                                        <option value="medium">🟡 Media - Afecta algunas funciones</option>
                                        <option value="high">🔴 Alta - Bloquea funcionalidad</option>
                                        <option value="critical">💀 Crítica - Sistema caído</option>
                                    </select>
                                    <p v-if="form.errors.priority" class="error-text">{{ form.errors.priority }}</p>
                                </div>
                            </div>

                            <!-- Info técnica section with gradient border -->
                            <div class="relative">
                                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-purple-500/5 via-blue-500/10 to-purple-500/5"></div>
                                <div class="pt-6">
                                    <h3 class="text-lg font-medium text-white/80 mb-4">
                                        Info Técnica
                                        <span class="text-sm text-white/40">(opcional)</span>
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="label">Tecnología</label>
                                            <input
                                                v-model="form.technology"
                                                type="text"
                                                class="input"
                                                placeholder="Ej: React 18.2.0"
                                            />
                                        </div>

                                        <div>
                                            <label class="label">Aplicación/Módulo</label>
                                            <input
                                                v-model="form.application"
                                                type="text"
                                                class="input"
                                                placeholder="Ej: Admin Dashboard"
                                            />
                                        </div>

                                        <div>
                                            <label class="label">Versión</label>
                                            <input
                                                v-model="form.version"
                                                type="text"
                                                class="input"
                                                placeholder="Ej: v2.1.0"
                                            />
                                        </div>

                                        <div>
                                            <label class="label">Año</label>
                                            <input
                                                v-model="form.year"
                                                type="text"
                                                class="input"
                                                placeholder="Ej: 2024"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons with enhanced gradients -->
                            <div class="flex justify-end space-x-4 pt-6">
                                <Link
                                    :href="route('posts.index')"
                                    class="btn btn-secondary"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">
                                        Guardando...
                                    </span>
                                    <span v-else>
                                        Reportar Bug 🚀
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Props
const props = defineProps({
    categories: Array
})

// Form con valores por defecto
const form = useForm({
    title: '',
    description: '',
    category_id: '',
    priority: '',
    technology: '',
    application: '',
    version: '',
    year: new Date().getFullYear().toString() // año actual por defecto
})

// Submit handler
const submitBug = () => {
    form.post(route('posts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // TODO: Mostrar toast de éxito
            form.reset()
        },
    })
}
</script>

<style scoped>
.label {
    @apply block text-sm font-medium text-white/60 mb-1;
}

.input {
    @apply w-full bg-purple-900/20 border border-purple-500/5 rounded-lg px-4 py-2.5 
        text-white/80 placeholder-white/30
        focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500/20 
        hover:bg-purple-900/30 transition-all duration-300;
}

.input.error {
    @apply border-red-500/30 focus:ring-red-500/20 focus:border-red-500/20;
}

.error-text {
    @apply mt-1 text-sm text-red-400/80;
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

/* Animación del pulso más suave */
@keyframes subtle-pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.3; }
}

.animate-subtle-pulse {
    animation: subtle-pulse 3s ease-in-out infinite;
}
</style> 