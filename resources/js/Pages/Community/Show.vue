<template>
  <AppLayout :title="topic.title">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ topic.title }}</h2>
        <Link
          :href="route('community.index')"
          class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
          Volver a la Comunidad
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Blog principal -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
          <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
              <img :src="topic.author?.profile_photo_url || ''" :alt="topic.author?.name || ''" class="h-10 w-10 rounded-full">
            </div>
            <div class="flex-1">
              <div class="flex items-center space-x-2">
                <span class="font-medium text-gray-900">{{ topic.author?.name || 'Usuario' }}</span>
                <span class="text-gray-500">•</span>
                <span class="text-gray-500">{{ topic.created_at }}</span>
                <span class="bg-gray-100 px-2.5 py-0.5 rounded-full text-sm text-gray-500">
                  {{ topic.category?.name || topic.category || 'Sin categoría' }}
                </span>
              </div>
              <div class="mt-4 prose max-w-none">
                {{ topic.content }}
              </div>
            </div>
          </div>
        </div>

        <!-- Sección de respuestas -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-6">Respuestas</h3>

          <!-- Formulario de nueva respuesta -->
          <form @submit.prevent="submitReply" class="mb-8">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700" for="reply">
                Tu Respuesta
              </label>
              <textarea
                id="reply"
                v-model="replyForm.content"
                rows="4"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                required
              ></textarea>
              <div v-if="replyForm.errors.content" class="mt-2 text-sm text-red-600">
                {{ replyForm.errors.content }}
              </div>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                :disabled="replyForm.processing"
              >
                Publicar Respuesta
              </button>
            </div>
          </form>

          <!-- Lista de respuestas -->
          <div v-if="!replies.data || replies.data.length === 0" class="text-center text-gray-500 py-8">
            No hay respuestas aún. ¡Sé el primero en responder!
          </div>
          <div v-else class="space-y-6">
            <div v-for="reply in replies.data" :key="reply.id" class="border-t pt-6">
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <img :src="reply.author.profile_photo_url" :alt="reply.author.name" class="h-10 w-10 rounded-full">
                </div>
                <div class="flex-1">
                  <div class="flex items-center space-x-2">
                    <span class="font-medium text-gray-900">{{ reply.author.name }}</span>
                    <span class="text-gray-500">•</span>
                    <span class="text-gray-500">{{ reply.created_at }}</span>
                  </div>
                  <div class="mt-2 text-gray-700">
                    {{ reply.content }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Paginación de respuestas -->
          <div class="mt-6">
            <Pagination :links="replies.links" />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  topic: {
    type: Object,
    required: true,
  },
  replies: {
    type: Object,
    required: true,
  },
});

const replyForm = useForm({
  content: '',
});

const submitReply = () => {
  replyForm.post(route('community.replies.store', props.topic.id), {
    onSuccess: () => {
      replyForm.reset();
    },
  });
};
</script> 