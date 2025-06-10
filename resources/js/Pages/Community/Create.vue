<template>
  <AppLayout>
    <div class="min-h-screen bg-white py-12">
      <div class="max-w-4xl mx-auto px-4">
        <!-- Encabezado con degradado en el texto -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold mb-2 flex items-center justify-center gap-2">
            <span class="bg-gradient-to-r from-purple-600 to-purple-400 bg-clip-text text-transparent">
              Crear Nuevo Blog
            </span>
            <span class="text-2xl">💭</span>
          </h1>
          <p class="text-gray-600">Comparte tus conocimientos con la comunidad</p>
        </div>

        <!-- Formulario con sombra y borde gradiente -->
        <div class="bg-white rounded-xl p-8 relative
                  shadow-[0_0_15px_rgba(168,85,247,0.1)]
                  before:content-[''] before:absolute before:inset-0 before:-z-10
                  before:p-[1px] before:bg-gradient-to-r before:from-purple-500/50 before:via-purple-400/50 before:to-purple-300/50
                  before:rounded-xl before:blur-sm">
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Título -->
            <div class="group">
              <label class="block text-gray-700 text-sm font-medium mb-2">Título del Blog</label>
              <input 
                v-model="form.title"
                type="text"
                required
                placeholder="Escribe un título descriptivo..."
                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg
                       shadow-[0_0_0_1px_rgba(168,85,247,0.05)]
                       focus:shadow-[0_0_0_4px_rgba(168,85,247,0.1)]
                       focus:border-purple-400
                       group-hover:border-purple-300
                       transition-all duration-300"
              >
            </div>

            <!-- Categoría -->
            <div class="group">
              <label class="block text-gray-700 text-sm font-medium mb-2">Categoría</label>
              <select 
                v-model="form.category_id"
                required
                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg
                       shadow-[0_0_0_1px_rgba(168,85,247,0.05)]
                       focus:shadow-[0_0_0_4px_rgba(168,85,247,0.1)]
                       focus:border-purple-400
                       group-hover:border-purple-300
                       transition-all duration-300"
              >
                <option value="">Selecciona una categoría</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>

            <!-- Contenido con editor enriquecido -->
            <div class="group">
              <label class="block text-gray-700 text-sm font-medium mb-2">Contenido</label>
              <div class="relative rounded-lg p-6 space-y-6 overflow-hidden
                        before:content-[''] before:absolute before:inset-0 before:bg-gradient-to-r 
                        before:from-purple-50 before:to-purple-100/50 before:opacity-50">
                <textarea 
                  v-model="form.content"
                  rows="8"
                  required
                  placeholder="Escribe el contenido de tu blog..."
                  class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg
                         shadow-[0_0_0_1px_rgba(168,85,247,0.05)]
                         focus:shadow-[0_0_0_4px_rgba(168,85,247,0.1)]
                         focus:border-purple-400
                         group-hover:border-purple-300
                         transition-all duration-300"
                ></textarea>
              </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end gap-4 pt-6">
              <!-- Botón Cancelar -->
              <Link 
                :href="route('community.index')"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium
                       hover:bg-gray-50 hover:border-gray-400
                       transform hover:scale-[1.02] active:scale-[0.98]
                       transition-all duration-200"
              >
                Cancelar
              </Link>

              <!-- Botón Publicar con degradado -->
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
                  <span>Publicar Blog</span>
                  <span class="text-xl">✨</span>
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
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  categories: {
    type: Array,
    required: true
  }
});

const form = useForm({
  title: '',
  content: '',
  category_id: ''
});

function submit() {
  form.post(route('community.store'));
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