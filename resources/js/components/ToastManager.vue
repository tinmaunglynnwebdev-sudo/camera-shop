<template>
  <div class="fixed bottom-4 right-4 z-50 flex flex-col gap-2">
    <transition-group name="toast">
      <div 
        v-for="toast in toastStore.toasts" 
        :key="toast.id" 
        class="px-4 py-3 rounded-lg shadow-lg text-white flex items-center gap-3 min-w-[300px]"
        :class="{
          'bg-green-600': toast.type === 'success',
          'bg-red-600': toast.type === 'error',
          'bg-blue-600': toast.type === 'info',
          'bg-yellow-500': toast.type === 'warning'
        }"
      >
        <component :is="getIcon(toast.type)" class="w-6 h-6 shrink-0" />
        <span class="text-sm font-medium">{{ toast.message }}</span>
        <button @click="toastStore.removeToast(toast.id)" class="ml-auto text-white/80 hover:text-white">
          <XMarkIcon class="w-5 h-5" />
        </button>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useToastStore } from '../stores/toast';
import { CheckCircleIcon, XCircleIcon, InformationCircleIcon, ExclamationTriangleIcon, XMarkIcon } from '@heroicons/vue/24/solid';

const toastStore = useToastStore();

function getIcon(type) {
  switch (type) {
    case 'success': return CheckCircleIcon;
    case 'error': return XCircleIcon;
    case 'warning': return ExclamationTriangleIcon;
    default: return InformationCircleIcon;
  }
}
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}
.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>