<template>
  <div :class="containerClasses">
    <!-- Icon/Illustration -->
    <div class="flex justify-center mb-4">
      <div v-if="icon" :class="iconClasses">
        {{ icon }}
      </div>
      <div v-else-if="illustration" class="w-48 h-48 flex items-center justify-center bg-gray-100 rounded-full">
        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      </div>
    </div>

    <!-- Title -->
    <h3 v-if="title" :class="titleClasses">
      {{ title }}
    </h3>

    <!-- Description -->
    <p v-if="description" :class="descriptionClasses">
      {{ description }}
    </p>

    <!-- Actions -->
    <div v-if="$slots.actions || primaryAction || secondaryAction" class="mt-6">
      <slot name="actions">
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <button
            v-if="primaryAction"
            @click="$emit('primary-action')"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
          >
            <span v-if="primaryAction.icon" class="mr-2">{{ primaryAction.icon }}</span>
            {{ primaryAction.text }}
          </button>
          
          <button
            v-if="secondaryAction"
            @click="$emit('secondary-action')"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
          >
            <span v-if="secondaryAction.icon" class="mr-2">{{ secondaryAction.icon }}</span>
            {{ secondaryAction.text }}
          </button>
        </div>
      </slot>
    </div>

    <!-- Additional content -->
    <div v-if="$slots.default" class="mt-6">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  // Content
  title: {
    type: String,
    required: true
  },
  description: String,
  
  // Visual
  icon: String,
  illustration: {
    type: Boolean,
    default: false
  },
  
  // Size
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  
  // Actions
  primaryAction: {
    type: Object,
    default: null
    // Expected: { text: String, icon?: String }
  },
  secondaryAction: {
    type: Object,
    default: null
    // Expected: { text: String, icon?: String }
  },
  
  // Layout
  centered: {
    type: Boolean,
    default: true
  },
  
  // Padding
  padding: {
    type: String,
    default: 'lg',
    validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
  }
});

const emit = defineEmits(['primary-action', 'secondary-action']);

const containerClasses = computed(() => {
  const classes = [];
  
  // Centering
  if (props.centered) {
    classes.push('text-center');
  }
  
  // Padding
  switch (props.padding) {
    case 'sm':
      classes.push('p-4');
      break;
    case 'md':
      classes.push('p-6');
      break;
    case 'lg':
      classes.push('p-8');
      break;
    case 'xl':
      classes.push('p-12');
      break;
  }
  
  return classes.join(' ');
});

const iconClasses = computed(() => {
  const classes = ['flex items-center justify-center rounded-full'];
  
  switch (props.size) {
    case 'sm':
      classes.push('w-12 h-12 text-2xl bg-gray-100');
      break;
    case 'md':
      classes.push('w-16 h-16 text-4xl bg-gray-100');
      break;
    case 'lg':
      classes.push('w-20 h-20 text-5xl bg-gray-100');
      break;
  }
  
  return classes.join(' ');
});

const titleClasses = computed(() => {
  const classes = ['font-semibold text-gray-900 mb-2'];
  
  switch (props.size) {
    case 'sm':
      classes.push('text-lg');
      break;
    case 'md':
      classes.push('text-xl');
      break;
    case 'lg':
      classes.push('text-2xl');
      break;
  }
  
  return classes.join(' ');
});

const descriptionClasses = computed(() => {
  const classes = ['text-gray-500 max-w-md'];
  
  if (props.centered) {
    classes.push('mx-auto');
  }
  
  switch (props.size) {
    case 'sm':
      classes.push('text-sm');
      break;
    case 'md':
      classes.push('text-base');
      break;
    case 'lg':
      classes.push('text-lg');
      break;
  }
  
  return classes.join(' ');
});
</script> 