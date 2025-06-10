<template>
  <AppLayout title="Búsqueda Avanzada">
    <div class="min-h-screen bg-white py-12">
      <div class="max-w-4xl mx-auto px-4">
        <!-- Encabezado con degradado en el texto -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold mb-2 flex items-center justify-center gap-2">
            <span class="bg-gradient-to-r from-purple-600 to-purple-400 bg-clip-text text-transparent">
              Búsqueda Avanzada
            </span>
            <span class="text-2xl">🔍</span>
          </h1>
          <p class="text-gray-600">Encuentra soluciones usando filtros avanzados</p>
        </div>

        <!-- Formulario con sombra y borde gradiente -->
        <div class="bg-white rounded-xl p-8 relative
                  shadow-[0_0_15px_rgba(168,85,247,0.1)]
                  before:content-[''] before:absolute before:inset-0 before:-z-10
                  before:p-[1px] before:bg-gradient-to-r before:from-purple-500/50 before:via-purple-400/50 before:to-purple-300/50
                  before:rounded-xl before:blur-sm">
          <form @submit.prevent="search" class="space-y-6">
            <!-- Campo de búsqueda principal -->
            <div class="group">
              <label class="block text-gray-700 text-sm font-medium mb-2">Buscar <span class="text-red-500">*</span></label>
              <input
                v-model="form.search"
                type="text"
                required
                placeholder="Buscar en StackOverflow..."
                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg
                       shadow-[0_0_0_1px_rgba(168,85,247,0.05)]
                       focus:shadow-[0_0_0_4px_rgba(168,85,247,0.1)]
                       focus:border-purple-400
                       group-hover:border-purple-300
                       transition-all duration-300"
              >
            </div>

            <!-- Filtros avanzados con fondo y borde gradiente sutil -->
            <div class="relative rounded-lg p-6 space-y-6 overflow-hidden
                      before:content-[''] before:absolute before:inset-0 before:bg-gradient-to-r 
                      before:from-purple-50 before:to-purple-100/50 before:opacity-50">
              <div class="relative z-10 space-y-6">
                <h3 class="text-gray-700 font-medium flex items-center gap-2">
                  <span>Filtros Avanzados</span>
                  <span class="text-sm text-purple-400">(opcional)</span>
                </h3>
                
                <!-- Tecnología -->
                <div class="group">
                  <label class="block text-gray-700 text-sm font-medium mb-2">Tecnología</label>
                  <input
                    v-model="form.technology"
                    type="text"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg
                           shadow-[0_0_0_1px_rgba(168,85,247,0.05)]
                           focus:shadow-[0_0_0_4px_rgba(168,85,247,0.1)]
                           focus:border-purple-400
                           group-hover:border-purple-300
                           transition-all duration-300"
                    placeholder="Ej: PHP, JavaScript, Python..."
                  >
                </div>

                <!-- Aplicación -->
                <div class="group">
                  <label class="block text-gray-700 text-sm font-medium mb-2">Aplicación</label>
                  <input
                    v-model="form.application"
                    type="text"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg
                           shadow-[0_0_0_1px_rgba(168,85,247,0.05)]
                           focus:shadow-[0_0_0_4px_rgba(168,85,247,0.1)]
                           focus:border-purple-400
                           group-hover:border-purple-300
                           transition-all duration-300"
                    placeholder="Ej: Visual Studio Code, XAMPP, etc."
                  >
                </div>

                <!-- Versión -->
                <div class="group">
                  <label class="block text-gray-700 text-sm font-medium mb-2">Versión</label>
                  <input
                    v-model="form.version"
                    type="text"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg
                           shadow-[0_0_0_1px_rgba(168,85,247,0.05)]
                           focus:shadow-[0_0_0_4px_rgba(168,85,247,0.1)]
                           focus:border-purple-400
                           group-hover:border-purple-300
                           transition-all duration-300"
                    placeholder="Ej: 1.0.0, 2024, etc."
                  >
                </div>

                <!-- Año -->
                <div class="group">
                  <label class="block text-gray-700 text-sm font-medium mb-2">Año</label>
                  <input
                    v-model="form.year"
                    type="number"
                    min="1990"
                    max="2100"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg
                           shadow-[0_0_0_1px_rgba(168,85,247,0.05)]
                           focus:shadow-[0_0_0_4px_rgba(168,85,247,0.1)]
                           focus:border-purple-400
                           group-hover:border-purple-300
                           transition-all duration-300"
                    placeholder="Ej: 2024"
                  >
                </div>
              </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end gap-4 pt-6">
              <!-- Botón Limpiar -->
              <button 
                type="button" 
                @click="resetForm"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium
                       hover:bg-gray-50 hover:border-gray-400
                       transform hover:scale-[1.02] active:scale-[0.98]
                       transition-all duration-200"
              >
                Limpiar filtros
              </button>

              <!-- Botón Buscar con degradado -->
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
              >
                <span class="relative z-10 flex items-center gap-2">
                  <span>Buscar</span>
                  <span class="text-xl">🔍</span>
                </span>
              </button>
            </div>
          </form>
        </div>

        <!-- Resultados de la búsqueda -->
        <div class="mt-8">
          <div v-if="loading" class="text-center text-gray-500">Buscando en StackOverflow...</div>
          <div v-else-if="error" class="text-center text-red-500">{{ error }}</div>
          <div v-else-if="results.length > 0" class="space-y-4">
            <h3 class="text-lg font-semibold mb-4">Resultados en StackOverflow</h3>
            <div v-for="item in results" :key="item.link" 
                 class="bg-white p-4 rounded-lg border border-gray-200 hover:border-purple-200 transition-colors duration-300">
              <a :href="item.link" target="_blank" class="text-purple-600 font-bold hover:text-purple-800">
                {{ item.title }}
              </a>
              <div class="text-sm text-gray-600 mt-1">
                <span v-if="item.owner">Por {{ item.owner.display_name }} · </span>
                <span>Puntuación: {{ item.score }}</span>
                <span v-if="item.is_answered" class="ml-2 text-green-600">✔ Respondida</span>
              </div>
              <div class="text-xs text-gray-400 mt-1">Etiquetas: {{ item.tags.join(', ') }}</div>
            </div>
          </div>
          <div v-else class="text-center text-gray-400">No hay resultados para esta búsqueda.</div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useStackOverflowSearch } from '@/composables/useStackOverflowSearch';

const form = ref({
  search: '',
  technology: '',
  application: '',
  version: '',
  year: ''
});

const { results, loading, error, search: searchStackOverflow } = useStackOverflowSearch();

function search() {
  if (!form.value.search.trim()) return;
  
  let query = form.value.search;
  if (form.value.technology) query += ' ' + form.value.technology;
  if (form.value.application) query += ' ' + form.value.application;
  if (form.value.version) query += ' ' + form.value.version;
  if (form.value.year) query += ' ' + form.value.year;
  
  searchStackOverflow(query);
}

function resetForm() {
  form.value = {
    search: '',
    technology: '',
    application: '',
    version: '',
    year: ''
  };
}
</script>

<style scoped>
/* Efecto de brillo en el hover del botón */
button[type="submit"]:hover::after {
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
</style> 