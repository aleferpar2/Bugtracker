<template>
  <component 
    :is="tag" 
    :href="href"
    :to="to"
    :type="type || 'button'"
    :disabled="disabled || loading"
    :class="btnStyle"
    @click="$emit('click')"
  >
    <div v-if="loading" class="spinner">
      <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
      </svg>
    </div>

    <span v-if="icon && !loading" :class="{'mr-2': true, 'order-last ml-2': iconRight}">
      {{ icon }}
    </span>
    
    <slot />
  </component>
</template>

<script setup>
import { computed } from 'vue'

// props simples, sin tanta validación 
const props = defineProps({
  type: String,
  tag: {
    type: String,
    default: 'button'
  },
  variant: String, // primary, secondary, danger...
  size: String, // sm, md, lg
  icon: String,
  iconRight: Boolean,
  loading: Boolean,
  disabled: Boolean,
  href: String,
  to: [String, Object],
})

// estilos más personalizados y menos rígidos
const btnStyle = computed(() => {
  const base = 'inline-flex items-center font-medium transition-all'
  const styles = []

  // colores chulos ✨
  switch(props.variant) {
    case 'primary':
      styles.push('bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white')
      break
    case 'secondary': 
      styles.push('bg-gray-100 hover:bg-gray-200 text-gray-800')
      break
    case 'danger':
      styles.push('bg-red-500 hover:bg-red-600 text-white')
      break
    case 'success':
      styles.push('bg-emerald-500 hover:bg-emerald-600 text-white')
      break
    default:
      styles.push('bg-white hover:bg-gray-50 text-gray-800 border border-gray-300')
  }

  // tamaños
  switch(props.size) {
    case 'sm': 
      styles.push('text-sm px-3 py-1.5 rounded')
      break
    case 'lg':
      styles.push('text-lg px-6 py-3 rounded-lg')
      break
    default: // md
      styles.push('px-4 py-2 rounded-md')
  }

  // estados
  if (props.disabled || props.loading) {
    styles.push('opacity-60 cursor-not-allowed')
  }

  return [base, ...styles].join(' ')
})
</script>

<style scoped>
.spinner {
  margin: -1px 8px -1px -4px;
}

/* transiciones suaves */
.btn-enter-active,
.btn-leave-active {
  transition: all 0.2s ease;
}

.btn-enter-from,
.btn-leave-to {
  opacity: 0;
  transform: translateY(4px);
}
</style> 