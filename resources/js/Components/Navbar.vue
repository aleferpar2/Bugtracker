<template>
  <nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <!-- Logo y Home -->
        <div class="flex items-center space-x-4">
          <Link :href="route('home')" class="flex items-center space-x-2">
            <span class="text-2xl font-bold text-indigo-600">🐞</span>
            <span class="font-semibold text-lg text-gray-800">BugTracker</span>
          </Link>
          <Link :href="route('dashboard')" class="nav-link" :class="{ 'font-bold text-indigo-600': isActive('dashboard') }">Dashboard</Link>
          <Link :href="route('posts.index')" class="nav-link" :class="{ 'font-bold text-indigo-600': isActive('posts.index') }">Bugs</Link>
          <Link :href="route('community.index')" class="nav-link" :class="{ 'font-bold text-indigo-600': isActive('community.index') }">Comunidad</Link>
        </div>
        <!-- Usuario y logout -->
        <div class="flex items-center space-x-4">
          <Link :href="route('profile.edit')" class="flex items-center space-x-2">
            <img v-if="user && user.profile_photo_url" :src="user.profile_photo_url" class="h-8 w-8 rounded-full object-cover border" alt="avatar" />
            <span class="text-gray-700">{{ user?.name }}</span>
          </Link>
          <form @submit.prevent="logout">
            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Salir</button>
          </form>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

function isActive(routeName) {
  return page.url.startsWith(route(routeName, undefined, false));
}

function logout() {
  router.post(route('logout'));
}
</script>

<style scoped>
.nav-link {
  @apply px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-700 transition;
}
</style> 