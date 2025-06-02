<template>
  <AppLayout :title="question?.title || 'Detalle StackOverflow'">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalle de la pregunta</h2>
    </template>
    <div class="py-12">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div v-if="loading" class="text-center text-gray-500">Cargando detalle...</div>
        <div v-else-if="error" class="text-center text-red-500">{{ error }}</div>
        <div v-else-if="question">
          <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="text-2xl font-bold mb-2">{{ question.title }}</h3>
            <div class="text-sm text-gray-600 mb-2">
              <span v-if="question.owner">Por {{ question.owner.display_name }} · </span>
              <span>Puntuación: {{ question.score }}</span>
              <span v-if="question.is_answered" class="ml-2 text-green-600">✔ Respondida</span>
            </div>
            <div class="text-xs text-gray-400 mb-2">Etiquetas: {{ question.tags?.join(', ') }}</div>
            <div class="prose max-w-none" v-html="question.body"></div>
            <a :href="question.link" target="_blank" class="block mt-4 text-indigo-700 font-bold hover:underline">Ver en StackOverflow</a>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <h4 class="text-lg font-semibold mb-4">Respuestas principales</h4>
            <div v-if="answers.length === 0" class="text-gray-400">No hay respuestas destacadas.</div>
            <div v-else>
              <div v-for="answer in answers" :key="answer.answer_id" class="mb-6 border-b pb-4">
                <div class="text-sm text-gray-600 mb-2">
                  <span v-if="answer.owner">Por {{ answer.owner.display_name }} · </span>
                  <span>Puntuación: {{ answer.score }}</span>
                  <span v-if="answer.is_accepted" class="ml-2 text-green-600">✔ Aceptada</span>
                </div>
                <div class="prose max-w-none" v-html="answer.body"></div>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="text-center text-gray-400">No se encontró la pregunta.</div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
  questionId: String
});

const question = ref(null);
const answers = ref([]);
const loading = ref(true);
const error = ref('');

onMounted(() => {
  fetchDetail();
});

function fetchDetail() {
  loading.value = true;
  error.value = '';
  axios.get(route('stackoverflow.detail', { question_id: props.questionId }))
    .then(res => {
      question.value = res.data.question;
      answers.value = res.data.answers;
    })
    .catch(() => {
      error.value = 'No se pudo obtener el detalle de la pregunta.';
    })
    .finally(() => {
      loading.value = false;
    });
}
</script> 