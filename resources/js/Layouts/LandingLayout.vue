<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
  title: String,
});
</script>

<template>
  <div>
    <Head :title="title" />
    
    <div class="w-full min-h-screen">
      <slot />
    </div>
  </div>
</template> 