<script setup>
import LandingLayout from '@/Layouts/LandingLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <LandingLayout title="Iniciar Sesión - BugTracker">
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                
                <!-- Header -->
                <div class="text-center mb-8">
                    <Link :href="route('home')" class="inline-block group">
                        <div class="relative w-48 h-48 mx-auto mb-5">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/30 via-purple-500/30 to-blue-500/30 rounded-full blur-3xl group-hover:blur-4xl transition-all duration-500"></div>
                            <img src="/images/logo.svg" alt="BugTracker Logo" class="relative w-full h-full transform group-hover:scale-105 transition-transform duration-500 drop-shadow-2xl" />
                        </div>
                        <div class="space-y-1">
                            <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-blue-400">Iniciar Sesión</h2>
                            <p class="text-base text-slate-300">Accede a tu cuenta de BugTracker</p>
                        </div>
                    </Link>
                </div>

                <!-- Form Card -->
                <div class="relative">
                    <!-- Efectos de fondo -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-purple-400/20 via-indigo-400/20 to-purple-400/20 rounded-3xl blur-xl"></div>
                    <div class="absolute -inset-1 bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-purple-500/10 rounded-3xl blur-lg animate-pulse"></div>
                    
                    <div class="relative bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 shadow-[0_8px_30px_rgb(124,58,237,0.1)] hover:shadow-[0_8px_30px_rgb(124,58,237,0.2)] transition-all duration-500">
                        <!-- Status Message -->
                        <div v-if="status" class="mb-4 p-3 bg-green-500/20 border border-green-500/30 rounded-lg backdrop-blur-sm">
                            <p class="text-green-200 text-sm">{{ status }}</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <!-- Email -->
                            <div class="group">
                                <label for="email" class="block text-sm font-medium text-white mb-1 group-hover:text-white/90 transition-colors">
                                    Email
                                </label>
                                <input
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-purple-300/50 focus:border-transparent transition-all duration-300 hover:bg-white/10"
                                    placeholder="tu@email.com"
                                />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-400/90">
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- Password -->
                            <div class="group">
                                <label for="password" class="block text-sm font-medium text-white mb-1 group-hover:text-white/90 transition-colors">
                                    Contraseña
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-purple-300/50 focus:border-transparent transition-all duration-300 hover:bg-white/10"
                                    placeholder="••••••••"
                                />
                                <p v-if="form.errors.password" class="mt-1 text-sm text-red-400/90">
                                    {{ form.errors.password }}
                                </p>
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center group">
                                <input
                                    id="remember"
                                    type="checkbox"
                                    v-model="form.remember"
                                    class="w-4 h-4 bg-white/5 border-white/10 rounded focus:ring-2 focus:ring-purple-300/50 focus:ring-offset-0 transition-all duration-300"
                                />
                                <label for="remember" class="ml-2 text-sm text-white/90 group-hover:text-white transition-colors">
                                    Recordarme
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="relative w-full group overflow-hidden rounded-xl"
                            >
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-400 via-indigo-400 to-purple-400 group-hover:from-purple-500 group-hover:via-indigo-500 group-hover:to-purple-500 transition-all duration-300"></div>
                                <div class="relative px-4 py-2 bg-gradient-to-r from-purple-500/80 via-indigo-500/80 to-purple-500/80 backdrop-blur-sm group-hover:bg-transparent transition-all duration-300 text-white font-semibold">
                                    <span v-if="form.processing" class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Iniciando sesión...
                                    </span>
                                    <span v-else>Iniciar Sesión</span>
                                </div>
                            </button>

                            <!-- Links -->
                            <div class="flex items-center justify-between text-sm mt-4">
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-white/70 hover:text-white transition-colors relative group"
                                >
                                    <span>¿Olvidaste tu contraseña?</span>
                                    <span class="absolute -bottom-0.5 left-0 w-full h-0.5 bg-white/50 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="text-white/70 hover:text-white transition-colors relative group"
                                >
                                    <span>Crear cuenta</span>
                                    <span class="absolute -bottom-0.5 left-0 w-full h-0.5 bg-white/50 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-6">
                    <Link
                        :href="route('home')"
                        class="inline-flex items-center text-white/70 hover:text-white transition-colors relative group"
                    >
                        <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Volver al inicio</span>
                        <span class="absolute -bottom-0.5 left-0 w-full h-0.5 bg-white/50 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                    </Link>
                </div>
            </div>
        </div>
    </LandingLayout>
</template>
