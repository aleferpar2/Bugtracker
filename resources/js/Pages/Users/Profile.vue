<template>
    <AppLayout title="Mi Perfil">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mi Perfil
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Información y edición del perfil -->
                    <div class="md:col-span-2">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <form @submit.prevent="updateProfile">
                                    <input type="hidden" name="_method" value="PATCH">
                                    <div class="space-y-6">
                                        <!-- Foto de perfil -->
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img
                                                    :src="form.photoUrl || user.profile_photo_url"
                                                    :alt="user.name"
                                                    class="h-16 w-16 rounded-full object-cover border"
                                                >
                                            </div>
                                            <div>
                                                <input type="file" @change="onPhotoChange" accept="image/*" class="block text-sm text-gray-500" />
                                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, máx. 2MB</p>
                                            </div>
                                        </div>

                                        <!-- Nombre -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                            <input
                                                v-model="form.name"
                                                type="text"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                required
                                            >
                                            <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.name }}
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Email</label>
                                            <input
                                                v-model="form.email"
                                                type="email"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                required
                                            >
                                            <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.email }}
                                            </div>
                                        </div>

                                        <!-- Biografía -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Biografía</label>
                                            <textarea
                                                v-model="form.bio"
                                                rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            ></textarea>
                                            <div v-if="form.errors.bio" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.bio }}
                                            </div>
                                        </div>

                                        <!-- Cambio de contraseña -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                                            <input
                                                v-model="form.password"
                                                type="password"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                placeholder="Dejar en blanco para no cambiar"
                                            >
                                            <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.password }}
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Confirmar nueva contraseña</label>
                                            <input
                                                v-model="form.password_confirmation"
                                                type="password"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                placeholder="Repite la nueva contraseña"
                                            >
                                        </div>

                                        <!-- Preferencias de notificación -->
                                        <div class="flex space-x-6">
                                            <label class="flex items-center">
                                                <input
                                                    type="checkbox"
                                                    v-model="form.email_notifications"
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >
                                                <span class="ml-2 text-sm text-gray-600">Notificaciones por email</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input
                                                    type="checkbox"
                                                    v-model="form.app_notifications"
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >
                                                <span class="ml-2 text-sm text-gray-600">Notificaciones en la app</span>
                                            </label>
                                        </div>

                                        <!-- Botón Guardar -->
                                        <div class="flex justify-end">
                                            <button
                                                type="submit"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                                                :disabled="form.processing"
                                            >
                                                Guardar Cambios
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas personales -->
                    <div class="space-y-6">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Bugs Reportados</span>
                                        <span class="font-semibold">{{ stats.reported_bugs }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Soluciones Propuestas</span>
                                        <span class="font-semibold">{{ stats.proposed_solutions }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Comentarios</span>
                                        <span class="font-semibold">{{ stats.comments }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Votos Recibidos</span>
                                        <span class="font-semibold">{{ stats.received_votes }}</span>
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
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    user: Object,
    stats: Object
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    bio: props.user.bio || '',
    photo: null,
    photoUrl: null,
    password: '',
    password_confirmation: '',
    email_notifications: props.user.email_notifications,
    app_notifications: props.user.app_notifications
});

function onPhotoChange(e) {
    const file = e.target.files[0];
    if (file) {
        form.photo = file;
        form.photoUrl = URL.createObjectURL(file);
    }
}

function updateProfile() {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation', 'photo');
        },
    });
}
</script> 