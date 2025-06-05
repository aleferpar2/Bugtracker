<script setup>
import LandingLayout from '@/Layouts/LandingLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  stats: Object,
});
</script>

<template>
  <LandingLayout title="BugTracker - Plataforma de Seguimiento de Errores">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
      
      <!-- Hero Section -->
      <div class="relative">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 via-purple-600/20 to-blue-600/20"></div>
        <div class="relative w-full mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
          
          <!-- Logo/Icon -->
          <div class="mb-12">
            <div class="relative w-64 h-64 mx-auto mb-8 group">
              <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/30 via-purple-500/30 to-blue-500/30 rounded-full blur-3xl group-hover:blur-4xl transition-all duration-500"></div>
              <img src="/images/logo.svg" alt="BugTracker Logo" class="relative w-full h-full transform group-hover:scale-105 transition-transform duration-500 drop-shadow-2xl" />
            </div>
            <div class="space-y-4">
              <h1 class="text-5xl md:text-7xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-blue-400 animate-gradient bg-size-200">
                BugTracker
              </h1>
              <p class="text-xl md:text-2xl text-slate-300 max-w-3xl mx-auto">
                La plataforma colaborativa para identificar, resolver y prevenir errores de software
              </p>
            </div>
          </div>

          <!-- Auth Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
            <Link :href="route('register')" 
                  class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-4 px-8 rounded-xl text-lg transition-all transform hover:scale-105 shadow-xl">
              🚀 Comenzar Gratis
            </Link>
            <Link :href="route('login')" 
                  class="border-2 border-white/20 bg-white/5 backdrop-blur-sm text-white hover:bg-white/10 font-semibold py-4 px-8 rounded-xl text-lg transition-all">
              🔑 Iniciar Sesión
            </Link>
          </div>

          <!-- Quick Stats -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full max-w-4xl mx-auto">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
              <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ stats.total_posts }}</div>
              <div class="text-slate-300 text-sm">Bugs</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
              <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ stats.resolved_posts }}</div>
              <div class="text-slate-300 text-sm">Resueltos</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
              <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ stats.total_solutions }}</div>
              <div class="text-slate-300 text-sm">Soluciones</div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
              <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ stats.total_users }}</div>
              <div class="text-slate-300 text-sm">Desarrolladores</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Features Section -->
      <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-2xl md:text-3xl font-bold text-white text-center mb-12">
          ¿Por qué elegir BugTracker?
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
          <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 text-center hover:bg-white/10 transition-all">
            <div class="text-4xl mb-4">🎯</div>
            <h3 class="text-xl font-bold text-white mb-3">Tracking Eficiente</h3>
            <p class="text-slate-300">
              Organiza y prioriza tus bugs de manera sistemática
            </p>
          </div>
          
          <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 text-center hover:bg-white/10 transition-all">
            <div class="text-4xl mb-4">👥</div>
            <h3 class="text-xl font-bold text-white mb-3">Colaboración</h3>
            <p class="text-slate-300">
              Trabaja en equipo para encontrar las mejores soluciones
            </p>
          </div>
          
          <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 text-center hover:bg-white/10 transition-all">
            <div class="text-4xl mb-4">📈</div>
            <h3 class="text-xl font-bold text-white mb-3">Insights</h3>
            <p class="text-slate-300">
              Obtén estadísticas sobre patrones de errores
            </p>
          </div>
        </div>

        <!-- Advanced Search CTA -->
        <div class="text-center mt-12">
          <Link :href="route('posts.advanced')" class="inline-flex items-center px-6 py-3 bg-white/5 border border-white/20 rounded-xl text-white hover:bg-white/10 transition-all transform hover:scale-105">
            <span class="text-lg mr-2">🔍</span>
            <span class="font-medium">Búsqueda Avanzada en StackOverflow</span>
          </Link>
          <p class="text-slate-400 text-sm mt-2">Busca soluciones con filtros avanzados</p>
        </div>
      </div>


      <!-- Final CTA -->
      <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <div class="bg-gradient-to-r from-indigo-600/20 to-purple-600/20 backdrop-blur-sm rounded-3xl p-8 border border-white/10 max-w-3xl mx-auto">
          <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
            ¿Listo para comenzar?
          </h2>
          <p class="text-slate-300 mb-8">
            Únete a nuestra comunidad de desarrolladores
          </p>
          
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link :href="route('register')" 
                  class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-all transform hover:scale-105">
              Crear Cuenta Gratis
            </Link>
            <Link :href="route('login')" 
                  class="border border-white/20 bg-white/5 text-white hover:bg-white/10 font-semibold py-3 px-6 rounded-xl transition-all">
              Iniciar Sesión
            </Link>
          </div>
        </div>
      </div>

      <!-- Simple Footer -->
      <footer class="border-t border-white/10 py-8">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <p class="text-slate-400 text-sm">
            &copy; 2024 BugTracker. Todos los derechos reservados.
          </p>
        </div>
      </footer>

    </div>
  </LandingLayout>
</template> 