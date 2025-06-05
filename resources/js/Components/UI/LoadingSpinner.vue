<template>
  <div v-if="show" :class="containerClasses">
    <!-- Spinner -->
    <div :class="spinnerClasses">
      <svg
        v-if="type === 'spinner'"
        class="animate-spin"
        :class="sizeClasses"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        ></circle>
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
        ></path>
      </svg>

      <div
        v-else-if="type === 'dots'"
        class="flex space-x-1"
      >
        <div
          v-for="n in 3"
          :key="n"
          :class="[dotClasses, `animate-bounce`]"
          :style="{ animationDelay: `${(n - 1) * 150}ms` }"
        ></div>
      </div>

      <div
        v-else-if="type === 'pulse'"
        :class="[pulseClasses, 'animate-pulse']"
      ></div>
    </div>

    <!-- Loading text -->
    <div v-if="text" :class="textClasses">
      {{ text }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  // Visibility
  show: {
    type: Boolean,
    default: true
  },
  
  // Appearance
  type: {
    type: String,
    default: 'spinner',
    validator: (value) => ['spinner', 'dots', 'pulse'].includes(value)
  },
  
  // Size
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(value)
  },
  
  // Color
  color: {
    type: String,
    default: 'indigo',
    validator: (value) => ['indigo', 'gray', 'white', 'red', 'green', 'blue'].includes(value)
  },
  
  // Text
  text: String,
  
  // Layout
  centered: {
    type: Boolean,
    default: false
  },
  overlay: {
    type: Boolean,
    default: false
  },
  fullscreen: {
    type: Boolean,
    default: false
  }
});

const containerClasses = computed(() => {
  const classes = [];
  
  if (props.fullscreen) {
    classes.push('fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-75');
  } else if (props.overlay) {
    classes.push('absolute inset-0 z-10 flex items-center justify-center bg-white bg-opacity-75');
  } else if (props.centered) {
    classes.push('flex flex-col items-center justify-center');
  } else {
    classes.push('flex flex-col items-center');
  }
  
  return classes.join(' ');
});

const spinnerClasses = computed(() => {
  const classes = [];
  
  // Color
  switch (props.color) {
    case 'indigo':
      classes.push('text-indigo-600');
      break;
    case 'gray':
      classes.push('text-gray-600');
      break;
    case 'white':
      classes.push('text-white');
      break;
    case 'red':
      classes.push('text-red-600');
      break;
    case 'green':
      classes.push('text-green-600');
      break;
    case 'blue':
      classes.push('text-blue-600');
      break;
  }
  
  return classes.join(' ');
});

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'xs':
      return 'w-3 h-3';
    case 'sm':
      return 'w-4 h-4';
    case 'md':
      return 'w-6 h-6';
    case 'lg':
      return 'w-8 h-8';
    case 'xl':
      return 'w-12 h-12';
    default:
      return 'w-6 h-6';
  }
});

const dotClasses = computed(() => {
  const classes = ['rounded-full'];
  
  // Size
  switch (props.size) {
    case 'xs':
      classes.push('w-1 h-1');
      break;
    case 'sm':
      classes.push('w-1.5 h-1.5');
      break;
    case 'md':
      classes.push('w-2 h-2');
      break;
    case 'lg':
      classes.push('w-3 h-3');
      break;
    case 'xl':
      classes.push('w-4 h-4');
      break;
  }
  
  // Color
  switch (props.color) {
    case 'indigo':
      classes.push('bg-indigo-600');
      break;
    case 'gray':
      classes.push('bg-gray-600');
      break;
    case 'white':
      classes.push('bg-white');
      break;
    case 'red':
      classes.push('bg-red-600');
      break;
    case 'green':
      classes.push('bg-green-600');
      break;
    case 'blue':
      classes.push('bg-blue-600');
      break;
  }
  
  return classes.join(' ');
});

const pulseClasses = computed(() => {
  const classes = ['rounded-full'];
  
  // Size
  switch (props.size) {
    case 'xs':
      classes.push('w-3 h-3');
      break;
    case 'sm':
      classes.push('w-4 h-4');
      break;
    case 'md':
      classes.push('w-6 h-6');
      break;
    case 'lg':
      classes.push('w-8 h-8');
      break;
    case 'xl':
      classes.push('w-12 h-12');
      break;
  }
  
  // Color
  switch (props.color) {
    case 'indigo':
      classes.push('bg-indigo-600');
      break;
    case 'gray':
      classes.push('bg-gray-600');
      break;
    case 'white':
      classes.push('bg-white');
      break;
    case 'red':
      classes.push('bg-red-600');
      break;
    case 'green':
      classes.push('bg-green-600');
      break;
    case 'blue':
      classes.push('bg-blue-600');
      break;
  }
  
  return classes.join(' ');
});

const textClasses = computed(() => {
  const classes = ['mt-2 font-medium'];
  
  // Size
  switch (props.size) {
    case 'xs':
      classes.push('text-xs');
      break;
    case 'sm':
      classes.push('text-sm');
      break;
    case 'md':
      classes.push('text-base');
      break;
    case 'lg':
      classes.push('text-lg');
      break;
    case 'xl':
      classes.push('text-xl');
      break;
  }
  
  // Color
  switch (props.color) {
    case 'indigo':
      classes.push('text-indigo-600');
      break;
    case 'gray':
      classes.push('text-gray-600');
      break;
    case 'white':
      classes.push('text-white');
      break;
    case 'red':
      classes.push('text-red-600');
      break;
    case 'green':
      classes.push('text-green-600');
      break;
    case 'blue':
      classes.push('text-blue-600');
      break;
  }
  
  return classes.join(' ');
});
</script>

<style scoped>
@keyframes bounce {
  0%, 80%, 100% {
    transform: scale(0);
  }
  40% {
    transform: scale(1);
  }
}

.animate-bounce {
  animation: bounce 1.4s infinite ease-in-out both;
}
</style> 