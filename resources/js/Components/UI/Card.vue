<template>
  <div :class="cardClasses">
    <!-- Header -->
    <div v-if="$slots.header || title" :class="headerClasses">
      <slot name="header">
        <div class="flex items-center justify-between">
          <h3 v-if="title" :class="titleClasses">{{ title }}</h3>
          <div v-if="$slots.headerActions" class="flex items-center space-x-2">
            <slot name="headerActions" />
          </div>
        </div>
      </slot>
    </div>

    <!-- Content -->
    <div :class="contentClasses">
      <slot />
    </div>

    <!-- Footer -->
    <div v-if="$slots.footer" :class="footerClasses">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  // Visual variants
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'elevated', 'bordered', 'flat', 'gradient'].includes(value)
  },
  
  // Sizes
  padding: {
    type: String,
    default: 'md',
    validator: (value) => ['none', 'sm', 'md', 'lg', 'xl'].includes(value)
  },
  
  // Layout
  rounded: {
    type: String,
    default: 'lg',
    validator: (value) => ['none', 'sm', 'md', 'lg', 'xl', 'full'].includes(value)
  },
  
  // Header
  title: String,
  headerPadding: {
    type: String,
    default: 'md',
    validator: (value) => ['none', 'sm', 'md', 'lg', 'xl'].includes(value)
  },
  headerBorder: {
    type: Boolean,
    default: true
  },
  
  // States
  hover: {
    type: Boolean,
    default: false
  },
  clickable: {
    type: Boolean,
    default: false
  },
  
  // Colors
  background: {
    type: String,
    default: 'white'
  }
});

const cardClasses = computed(() => {
  const classes = ['relative'];
  
  // Background
  if (props.background === 'white') {
    classes.push('bg-white');
  } else if (props.background === 'gray') {
    classes.push('bg-gray-50');
  } else {
    classes.push(props.background);
  }
  
  // Variants
  switch (props.variant) {
    case 'default':
      classes.push('shadow-sm border border-gray-200');
      break;
    case 'elevated':
      classes.push('shadow-lg border border-gray-100');
      break;
    case 'bordered':
      classes.push('border-2 border-gray-200');
      break;
    case 'flat':
      classes.push('border border-gray-100');
      break;
    case 'gradient':
      classes.push('bg-gradient-to-br from-white to-gray-50 shadow-md border border-gray-100');
      break;
  }
  
  // Rounded
  switch (props.rounded) {
    case 'none':
      classes.push('rounded-none');
      break;
    case 'sm':
      classes.push('rounded-sm');
      break;
    case 'md':
      classes.push('rounded-md');
      break;
    case 'lg':
      classes.push('rounded-lg');
      break;
    case 'xl':
      classes.push('rounded-xl');
      break;
    case 'full':
      classes.push('rounded-full');
      break;
  }
  
  // Interactive states
  if (props.hover || props.clickable) {
    classes.push('transition-all duration-200');
  }
  
  if (props.hover) {
    classes.push('hover:shadow-lg hover:shadow-gray-200/50 hover:-translate-y-1');
  }
  
  if (props.clickable) {
    classes.push('cursor-pointer hover:shadow-md active:scale-[0.98]');
  }
  
  return classes.join(' ');
});

const headerClasses = computed(() => {
  const classes = [];
  
  // Padding
  switch (props.headerPadding) {
    case 'none':
      break;
    case 'sm':
      classes.push('px-3 py-2');
      break;
    case 'md':
      classes.push('px-4 py-3');
      break;
    case 'lg':
      classes.push('px-6 py-4');
      break;
    case 'xl':
      classes.push('px-8 py-6');
      break;
  }
  
  // Border
  if (props.headerBorder) {
    classes.push('border-b border-gray-200');
  }
  
  return classes.join(' ');
});

const contentClasses = computed(() => {
  const classes = [];
  
  // Padding
  switch (props.padding) {
    case 'none':
      break;
    case 'sm':
      classes.push('p-3');
      break;
    case 'md':
      classes.push('p-4');
      break;
    case 'lg':
      classes.push('p-6');
      break;
    case 'xl':
      classes.push('p-8');
      break;
  }
  
  return classes.join(' ');
});

const footerClasses = computed(() => {
  const classes = ['px-4 py-3 bg-gray-50 border-t border-gray-200'];
  
  // Rounded bottom corners
  switch (props.rounded) {
    case 'sm':
      classes.push('rounded-b-sm');
      break;
    case 'md':
      classes.push('rounded-b-md');
      break;
    case 'lg':
      classes.push('rounded-b-lg');
      break;
    case 'xl':
      classes.push('rounded-b-xl');
      break;
  }
  
  return classes.join(' ');
});

const titleClasses = computed(() => {
  return 'text-lg font-semibold text-gray-900';
});
</script> 