<template>
  <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-gray-100">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
      <router-link to="/" class="flex items-center gap-2 group">
        <div class="bg-blue-600 text-white p-1.5 rounded-lg group-hover:bg-blue-700 transition">
          <CameraIcon class="w-6 h-6" />
        </div>
        <span class="text-xl font-bold text-gray-900 tracking-tight">CameraShop</span>
      </router-link>

      <div class="flex items-center gap-6">
        <router-link to="/" class="text-gray-600 hover:text-blue-600 font-medium transition text-sm">Shop</router-link>
        <router-link to="/cart" class="relative text-gray-600 hover:text-blue-600 transition">
          <ShoppingBagIcon class="w-6 h-6" />
          <span v-if="cartStore.itemCount > 0" class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center shadow-sm">
            {{ cartStore.itemCount }}
          </span>
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useCartStore } from '../stores/cart';
import { ShoppingBagIcon, CameraIcon } from '@heroicons/vue/24/outline';

const cartStore = useCartStore();
</script>