<template>
  <nav class="bg-gradient-to-b from-purple-600 via-purple-500 to-purple-400/90 backdrop-blur-lg border-b border-white/20 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <!-- Logo y Home -->
        <div class="flex items-center space-x-6">
          <Link :href="route('home')" class="flex items-center space-x-3 hover:opacity-90 transition-all duration-300 transform hover:scale-105">
            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-300 to-purple-400 rounded-xl shadow-lg shadow-purple-500/50">
              <span class="text-2xl">🐞</span>
            </div>
            <span class="font-bold text-xl bg-gradient-to-r from-white via-indigo-100 to-white bg-clip-text text-transparent bg-size-200 animate-gradient">
              BugTracker
            </span>
          </Link>

          <!-- Navigation Links (Desktop) -->
          <div class="hidden md:flex items-center space-x-1">
            <Link :href="route('dashboard')" class="nav-link group" :class="{ 'nav-link-active': isActive('dashboard') }">
              <span class="text-lg mr-1 group-hover:rotate-12 transition-transform duration-300">📊</span>
              <span class="relative">
                Dashboard
                <span class="absolute inset-x-0 bottom-0 h-0.5 bg-white transform origin-left scale-x-0 transition-transform group-hover:scale-x-100"></span>
              </span>
            </Link>
            <Link :href="route('posts.index')" class="nav-link group" :class="{ 'nav-link-active': isActive('posts.index') }">
              <span class="text-lg mr-1 group-hover:rotate-12 transition-transform duration-300">🐞</span>
              <span class="relative">
                Bugs
                <span class="absolute inset-x-0 bottom-0 h-0.5 bg-white transform origin-left scale-x-0 transition-transform group-hover:scale-x-100"></span>
              </span>
            </Link>
            <Link :href="route('community.index')" class="nav-link group" :class="{ 'nav-link-active': isActive('community.index') }">
              <span class="text-lg mr-1 group-hover:rotate-12 transition-transform duration-300">💭</span>
              <span class="relative">
                Comunidad
                <span class="absolute inset-x-0 bottom-0 h-0.5 bg-white transform origin-left scale-x-0 transition-transform group-hover:scale-x-100"></span>
              </span>
            </Link>
            <Link :href="route('posts.advanced')" class="nav-link group" :class="{ 'nav-link-active': isActive('posts.advanced') }">
              <span class="text-lg mr-1 group-hover:rotate-12 transition-transform duration-300">🔍</span>
              <span class="relative">
                Búsqueda Avanzada
                <span class="absolute inset-x-0 bottom-0 h-0.5 bg-white transform origin-left scale-x-0 transition-transform group-hover:scale-x-100"></span>
              </span>
            </Link>
          </div>
        </div>

        <!-- Search Bar (Desktop) -->
        <div class="hidden md:flex flex-1 max-w-xl mx-8">
          <form @submit.prevent="performSearch" class="w-full">
            <div class="relative group">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar bugs, soluciones... 🔍"
                class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-full text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-300 group-hover:bg-white/30 group-hover:shadow-lg shadow-purple-500/30"
              />
              <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-white/70 hover:text-white transition-colors duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </button>
            </div>
          </form>
        </div>

        <!-- Right side: Notifications, User Menu, Mobile Button -->
        <div class="flex items-center space-x-4">
          <!-- Notifications -->
          <NotificationDropdown 
            v-if="user"
            :initial-notifications="[]" 
            :initial-unread-count="0" 
          />

          <!-- User Menu -->
          <div v-if="user" class="relative">
            <button @click="toggleUserMenu" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-white/20 transition-all duration-300 group">
              <img 
                v-if="user.profile_photo_url" 
                :src="user.profile_photo_url" 
                class="h-8 w-8 rounded-full object-cover border-2 border-white/70 group-hover:border-white transition-colors duration-300" 
                :alt="user.name" 
              />
              <div v-else class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-300 to-purple-400 flex items-center justify-center text-white font-semibold text-sm shadow-lg">
                {{ user.name.charAt(0).toUpperCase() }}
              </div>
              <span class="hidden md:block text-white/90 font-medium group-hover:text-white transition-colors duration-300">{{ user.name }}</span>
              <svg class="w-4 h-4 text-white/70 group-hover:text-white transition-colors duration-300 transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- User Dropdown -->
            <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-purple-500/90 backdrop-blur-lg rounded-xl shadow-lg border border-white/30 py-1 z-50 transform transition-all duration-300 origin-top-right">
              <Link :href="route('profile.edit')" class="dropdown-link">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Mi Perfil
              </Link>
              <Link :href="route('notifications.index')" class="dropdown-link">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Notificaciones
              </Link>
              <div class="border-t border-white/20 my-1"></div>
              <button @click="logout" class="dropdown-link text-red-200 w-full text-left hover:bg-red-300/20">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Cerrar Sesión
              </button>
            </div>
          </div>

          <!-- Mobile menu button -->
          <button @click="toggleMobileMenu" class="md:hidden p-2 rounded-lg hover:bg-white/20 transition-all duration-300">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!showMobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div v-if="showMobileMenu" class="md:hidden border-t border-white/20 py-4">
        <!-- Mobile Search -->
        <div class="px-4 mb-4">
          <form @submit.prevent="performSearch">
            <div class="relative group">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar bugs..."
                class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-300 group-hover:bg-white/30"
              />
              <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white/70 hover:text-white transition-colors duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </button>
            </div>
          </form>
        </div>

        <!-- Mobile Navigation Links -->
        <div class="space-y-1 px-4">
          <Link :href="route('dashboard')" class="mobile-nav-link group" :class="{ 'mobile-nav-link-active': isActive('dashboard') }">
            <span class="group-hover:rotate-12 transition-transform duration-300">📊</span>
            <span class="ml-2">Dashboard</span>
          </Link>
          <Link :href="route('posts.index')" class="mobile-nav-link group" :class="{ 'mobile-nav-link-active': isActive('posts.index') }">
            <span class="group-hover:rotate-12 transition-transform duration-300">🐞</span>
            <span class="ml-2">Bugs</span>
          </Link>
          <Link :href="route('community.index')" class="mobile-nav-link group" :class="{ 'mobile-nav-link-active': isActive('community.index') }">
            <span class="group-hover:rotate-12 transition-transform duration-300">💭</span>
            <span class="ml-2">Comunidad</span>
          </Link>
          <Link :href="route('posts.advanced')" class="mobile-nav-link group" :class="{ 'mobile-nav-link-active': isActive('posts.advanced') }">
            <span class="group-hover:rotate-12 transition-transform duration-300">🔍</span>
            <span class="ml-2">Búsqueda Avanzada</span>
          </Link>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import NotificationDropdown from './NotificationDropdown.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const searchQuery = ref('');
const showUserMenu = ref(false);
const showMobileMenu = ref(false);

function isActive(routeName) {
  return page.url.startsWith(route(routeName, undefined, false));
}

function performSearch() {
  if (searchQuery.value.trim()) {
    router.get(route('posts.index', { search: searchQuery.value }));
    searchQuery.value = '';
    showMobileMenu.value = false;
  }
}

function toggleUserMenu() {
  showUserMenu.value = !showUserMenu.value;
  showMobileMenu.value = false;
}

function toggleMobileMenu() {
  showMobileMenu.value = !showMobileMenu.value;
  showUserMenu.value = false;
}

function logout() {
  router.post(route('logout'));
}

// Close menus when clicking outside
onMounted(() => {
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
      showUserMenu.value = false;
      showMobileMenu.value = false;
    }
  });
});
</script>

<style scoped>
.nav-link {
  @apply px-3 py-2 rounded-lg text-sm font-medium text-white/90 hover:text-white transition-all duration-300 flex items-center;
}

.nav-link-active {
  @apply bg-white/20 text-white font-semibold;
}

.dropdown-link {
  @apply flex items-center px-4 py-2 text-sm text-white/90 hover:bg-white/20 hover:text-white transition-all duration-300;
}

.mobile-nav-link {
  @apply flex items-center px-4 py-3 text-base font-medium text-white/90 hover:text-white hover:bg-white/20 rounded-lg transition-all duration-300;
}

.mobile-nav-link-active {
  @apply bg-white/20 text-white font-semibold;
}

@keyframes gradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.animate-gradient {
  animation: gradient 3s ease infinite;
}

.bg-size-200 {
  background-size: 200% 200%;
}
</style> 