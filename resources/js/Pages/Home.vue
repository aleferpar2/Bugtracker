<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  stats: Object,
  latestPosts: Array,
  mostCommentedPosts: Array,
  popularCategories: Array,
  recentActivity: Array,
});

const search = ref('');

function submitSearch() {
  if (search.value.trim()) {
    router.get(route('posts.index', { search: search.value }));
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
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
</script>

<template>
  <AppLayout title="Inicio">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
      
      <!-- Hero Section -->
      <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-700">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
          <div class="text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-6">
              🐞 BugTracker
            </h1>
            <p class="text-xl sm:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
              La plataforma colaborativa para identificar, resolver y prevenir errores de software
            </p>
            
            <!-- Search Bar -->
            <form @submit.prevent="submitSearch" class="max-w-4xl mx-auto mb-8">
              <div class="relative">
                <input
                  v-model="search"
                  type="text"
                  class="w-full rounded-full border-0 bg-white/90 backdrop-blur-sm shadow-xl px-6 py-4 text-lg placeholder-gray-500 focus:bg-white focus:ring-4 focus:ring-blue-300 transition-all"
                  placeholder="Buscar bugs, errores, soluciones... 🔍"
                />
                <button type="submit" class="absolute right-2 top-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2 rounded-full transition-colors">
                  Buscar
                </button>
              </div>
            </form>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
              <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                <div class="text-3xl font-bold text-white">{{ stats.total_posts }}</div>
                <div class="text-blue-100 text-sm">Bugs Reportados</div>
              </div>
              <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                <div class="text-3xl font-bold text-white">{{ stats.resolved_posts }}</div>
                <div class="text-blue-100 text-sm">Resueltos</div>
              </div>
              <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                <div class="text-3xl font-bold text-white">{{ stats.total_solutions }}</div>
                <div class="text-blue-100 text-sm">Soluciones</div>
              </div>
              <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                <div class="text-3xl font-bold text-white">{{ stats.total_users }}</div>
                <div class="text-blue-100 text-sm">Desarrolladores</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- Quick Actions -->
        <div class="mb-16">
          <h2 class="text-3xl font-bold text-gray-900 text-center mb-8">¿Qué quieres hacer hoy?</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <Link :href="route('posts.create')" class="group bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-xl p-8 text-center transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
              <div class="text-6xl mb-4">🐞</div>
              <h3 class="text-2xl font-bold text-white mb-2">Reportar Bug</h3>
              <p class="text-red-100">Reporta un nuevo error o problema</p>
            </Link>

            <Link :href="route('posts.index')" class="group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl p-8 text-center transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
              <div class="text-6xl mb-4">🔍</div>
              <h3 class="text-2xl font-bold text-white mb-2">Explorar Bugs</h3>
              <p class="text-blue-100">Busca y resuelve problemas existentes</p>
            </Link>

            <Link :href="route('community.index')" class="group bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 rounded-xl p-8 text-center transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
              <div class="text-6xl mb-4">💭</div>
              <h3 class="text-2xl font-bold text-white mb-2">Comunidad</h3>
              <p class="text-purple-100">Únete a las discusiones de la comunidad</p>
            </Link>
          </div>
        </div>

        <!-- Latest Posts and Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
          
          <!-- Latest Posts -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                  🕐 Últimos Bugs Reportados
                </h3>
              </div>
              <div class="p-6">
                <div v-if="latestPosts.length === 0" class="text-center text-gray-500 py-8">
                  No hay posts aún. ¡Sé el primero en reportar un bug!
                </div>
                <div v-else class="space-y-4">
                  <div v-for="post in latestPosts" :key="post.id" class="border-l-4 border-indigo-400 pl-4 py-3 hover:bg-gray-50 transition-colors">
                    <Link :href="route('posts.show', post.id)" class="block">
                      <h4 class="font-semibold text-gray-900 hover:text-indigo-600 transition-colors">{{ post.title }}</h4>
                      <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                        <span class="flex items-center">
                          <i class="fas fa-user mr-1"></i> {{ post.user.name }}
                        </span>
                        <span 
                          :class="{
                            'bg-green-100 text-green-800': post.status === 'resolved',
                            'bg-yellow-100 text-yellow-800': post.status === 'in_progress',
                            'bg-red-100 text-red-800': post.status === 'open',
                            'bg-gray-100 text-gray-800': post.status === 'closed'
                          }"
                          class="px-2 py-1 rounded text-xs font-medium"
                        >
                          {{ getStatusText(post.status) }}
                        </span>
                        <span>{{ formatDate(post.created_at) }}</span>
                      </div>
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Activity -->
          <div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                  ⚡ Actividad Reciente
                </h3>
              </div>
              <div class="p-6">
                <div v-if="recentActivity.length === 0" class="text-center text-gray-500 py-8">
                  Sin actividad reciente
                </div>
                <div v-else class="space-y-4">
                  <div v-for="activity in recentActivity" :key="`${activity.type}-${activity.post.id}`" class="flex items-start space-x-3">
                    <div 
                      :class="{
                        'bg-blue-100 text-blue-600': activity.type === 'comment',
                        'bg-green-100 text-green-600': activity.type === 'solution'
                      }"
                      class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                    >
                      {{ activity.type === 'comment' ? '💬' : '💡' }}
                    </div>
                    <div class="flex-1">
                      <p class="text-sm text-gray-800">
                        <span class="font-medium">{{ activity.user.name }}</span>
                        {{ activity.type === 'comment' ? 'comentó en' : 'propuso una solución para' }}
                      </p>
                      <Link :href="route('posts.show', activity.post.id)" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        {{ activity.post.title }}
                      </Link>
                      <p class="text-xs text-gray-500 mt-1">{{ formatDate(activity.created_at) }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Popular Categories -->
        <div class="mb-16">
          <h2 class="text-3xl font-bold text-gray-900 text-center mb-8">Categorías Populares</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <Link v-for="category in popularCategories" :key="category.id" :href="route('categories.show', category.id)" 
                  class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 p-6 text-center">
              <div class="text-4xl mb-3">📂</div>
              <h3 class="font-bold text-gray-900 mb-2">{{ category.name }}</h3>
              <p class="text-indigo-600 font-semibold">{{ category.posts_count }} bugs</p>
              <p class="text-gray-500 text-sm mt-2">{{ category.description || 'Sin descripción' }}</p>
            </Link>
          </div>
        </div>

        <!-- Most Commented Posts -->
        <div v-if="mostCommentedPosts.length > 0" class="mb-16">
          <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
              <h3 class="text-2xl font-bold text-white flex items-center">
                🔥 Más Discutidos Esta Semana
              </h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="post in mostCommentedPosts" :key="post.id" class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                  <Link :href="route('posts.show', post.id)" class="block">
                    <h4 class="font-semibold text-gray-900 hover:text-indigo-600 transition-colors mb-2">{{ post.title }}</h4>
                    <div class="flex items-center justify-between text-sm text-gray-500">
                      <span>{{ post.user.name }}</span>
                      <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-medium">
                        {{ post.comments_count }} comentarios
                      </span>
                    </div>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Features Section -->
        <div class="text-center mb-16">
          <h2 class="text-3xl font-bold text-gray-900 mb-8">¿Por qué usar BugTracker?</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-8">
              <div class="text-5xl mb-4">🎯</div>
              <h3 class="text-xl font-bold text-gray-900 mb-3">Tracking Eficiente</h3>
              <p class="text-gray-600">Organiza y prioriza tus bugs de manera sistemática para resolverlos más rápido.</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8">
              <div class="text-5xl mb-4">👥</div>
              <h3 class="text-xl font-bold text-gray-900 mb-3">Colaboración</h3>
              <p class="text-gray-600">Trabaja en equipo para encontrar las mejores soluciones a los problemas técnicos.</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8">
              <div class="text-5xl mb-4">📈</div>
              <h3 class="text-xl font-bold text-gray-900 mb-3">Insights</h3>
              <p class="text-gray-600">Obtén estadísticas detalladas sobre patrones de errores y rendimiento del equipo.</p>
            </div>
          </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl px-8 py-12 text-center">
          <h2 class="text-3xl font-bold text-white mb-4">¿Listo para comenzar?</h2>
          <p class="text-indigo-100 text-lg mb-8">Únete a nuestra comunidad de desarrolladores y contribuye a un software mejor.</p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link :href="route('posts.create')" class="bg-white text-indigo-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-lg transition-colors">
              Reportar mi primer Bug
            </Link>
            <Link :href="route('posts.advanced')" class="border-2 border-white text-white hover:bg-white hover:text-indigo-600 font-bold py-3 px-8 rounded-lg transition-colors">
              Búsqueda Avanzada
            </Link>
          </div>
        </div>

      </div>
    </div>
  </AppLayout>
</template> 