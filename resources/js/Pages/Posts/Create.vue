<template>
    <AppLayout>
        <div class="min-h-screen bg-white py-12">
            <div class="max-w-4xl mx-auto px-4">
                <!-- Encabezado con degradado en el texto -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold mb-2 flex items-center justify-center gap-2">
                        <span class="bg-gradient-to-r from-purple-600 to-purple-400 bg-clip-text text-transparent">
                            Reportar Bug
                        </span>
                        <span class="text-2xl">🐛</span>
                    </h1>
                    <p class="text-gray-600">Ayúdanos a mejorar describiendo el problema en detalle</p>
                </div>

                <!-- Formulario con sombra y borde gradiente -->
                <div class="bg-white rounded-xl p-8 relative
                          shadow-[0_0_15px_rgba(168,85,247,0.1)]
                          before:content-[''] before:absolute before:inset-0 before:-z-10
                          before:p-[1px] before:bg-gradient-to-r before:from-purple-500/50 before:via-purple-400/50 before:to-purple-300/50
                          before:rounded-xl before:blur-sm">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Campos con efectos mejorados -->
                        <div class="group">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Título del Bug</label>
                            <input 
                                v-model="form.title"
                                type="text"
                                placeholder="Ej: App crashea al subir fotos PNG"
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg
                                       shadow-[0_0_0_1px_rgba(168,85,247,0.05)]
                                       focus:shadow-[0_0_0_4px_rgba(168,85,247,0.1)]
                                       focus:border-purple-400
                                       group-hover:border-purple-300
                                       transition-all duration-300"
                            >
                        </div>

                        <!-- Descripción con efectos similares -->
                        <div class="group">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Descripción</label>
                            <textarea 
                                v-model="form.description"
                                rows="4"
                                placeholder="Describe el problema, pasos para reproducirlo, y comportamiento esperado..."
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg
                                       shadow-[0_0_0_1px_rgba(168,85,247,0.05)]
                                       focus:shadow-[0_0_0_4px_rgba(168,85,247,0.1)]
                                       focus:border-purple-400
                                       group-hover:border-purple-300
                                       transition-all duration-300"
                            ></textarea>
                        </div>

                        <!-- Categoría y Prioridad -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Categoría</label>
                                <select 
                                    v-model="form.category"
                                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-gray-900
                                           focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20
                                           group-hover:border-purple-200 transition-all duration-300"
                                >
                                    <option value="" disabled>Selecciona una categoría</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="group">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Prioridad</label>
                                <select 
                                    v-model="form.priority"
                                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-gray-900
                                           focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20
                                           group-hover:border-purple-200 transition-all duration-300"
                                >
                                    <option value="" disabled>Selecciona la prioridad</option>
                                    <option value="low">Baja</option>
                                    <option value="medium">Media</option>
                                    <option value="high">Alta</option>
                                    <option value="critical">Crítica</option>
                                </select>
                            </div>
                        </div>

                        <!-- Info Técnica con fondo y borde gradiente sutil -->
                        <div class="relative rounded-lg p-6 space-y-6 overflow-hidden
                                  before:content-[''] before:absolute before:inset-0 before:bg-gradient-to-r 
                                  before:from-purple-50 before:to-purple-100/50 before:opacity-50">
                            <div class="relative z-10">
                                <h3 class="text-gray-700 font-medium flex items-center gap-2">
                                    <span>Info Técnica</span>
                                    <span class="text-sm text-purple-400">(opcional)</span>
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="group">
                                        <label class="block text-gray-700 text-sm font-medium mb-2">Tecnología</label>
                                        <input 
                                            v-model="form.technology"
                                            type="text"
                                            placeholder="Ej: React 18.2.0"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400
                                                   focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20
                                                   group-hover:border-purple-200 transition-all duration-300"
                                        >
                                    </div>
                                    <div class="group">
                                        <label class="block text-gray-700 text-sm font-medium mb-2">Aplicación/Módulo</label>
                                        <input 
                                            v-model="form.application"
                                            type="text"
                                            placeholder="Ej: Admin Dashboard"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400
                                                   focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20
                                                   group-hover:border-purple-200 transition-all duration-300"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botón con degradado como en la imagen -->
                        <div class="flex justify-end pt-6">
                            <button 
                                type="submit"
                                class="px-6 py-3 relative group overflow-hidden rounded-lg
                                       text-white font-medium text-sm
                                       before:content-[''] before:absolute before:inset-0
                                       before:bg-gradient-to-b before:from-purple-500 before:to-purple-700
                                       hover:before:opacity-90
                                       shadow-lg shadow-purple-500/20
                                       transform hover:scale-[1.02] active:scale-[0.98]
                                       transition-all duration-200"
                                :disabled="form.processing"
                            >
                                <span class="relative z-10 flex items-center gap-2">
                                    <span>Reportar Bug</span>
                                    <span class="text-xl">🐛</span>
                                </span>
                            </button>
                        </div>
                    </form>
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
/* Efecto de brillo en el hover del botón */
button:hover::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent,
        rgba(255,255,255,0.1),
        transparent
    );
    transform: rotate(45deg);
    animation: shine 1.5s infinite;
}

@keyframes shine {
    0% {
        transform: translateX(-100%) rotate(45deg);
    }
    100% {
        transform: translateX(100%) rotate(45deg);
    }
}

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