<template>
  <div v-if="product" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col md:flex-row gap-12">
    <!-- Image Section -->
    <div class="md:w-1/2">
      <div class="rounded-xl overflow-hidden bg-gray-50 mb-4">
        <img :src="product.image" :alt="product.name" class="w-full object-cover hover:scale-105 transition duration-500">
      </div>
    </div>

    <!-- Details Section -->
    <div class="md:w-1/2 flex flex-col">
      <div class="mb-2">
        <span class="text-blue-600 font-bold tracking-wider uppercase text-sm bg-blue-50 px-3 py-1 rounded-full">
          {{ product.brand?.name }}
        </span>
      </div>
      
      <h1 class="text-4xl font-extrabold text-gray-900 mb-4 leading-tight">{{ product.name }}</h1>
      
      <div class="flex items-center gap-4 mb-6">
        <span class="text-3xl font-bold text-gray-900">${{ parseFloat(product.price).toLocaleString() }}</span>
        <span class="text-green-600 text-sm font-medium flex items-center gap-1">
          <CheckCircleIcon class="w-4 h-4" /> In Stock
        </span>
      </div>

      <p class="text-gray-600 text-lg leading-relaxed mb-8">{{ product.description }}</p>
      
      <div class="mt-auto space-y-4">
        <button @click="addToCart" class="w-full bg-blue-600 text-white px-8 py-4 rounded-xl text-lg font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-lg hover:shadow-blue-600/30">
          <ShoppingBagIcon class="w-6 h-6" />
          Add to Cart
        </button>
        
        <router-link to="/" class="block text-center text-gray-500 hover:text-gray-900 font-medium transition">
          Continue Shopping
        </router-link>
      </div>
    </div>
  </div>

  <div v-else class="flex justify-center items-center py-20">
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useCartStore } from '../stores/cart';
import { useToastStore } from '../stores/toast';
import { ShoppingBagIcon, CheckCircleIcon } from '@heroicons/vue/24/solid';

const route = useRoute();
const product = ref(null);
const cartStore = useCartStore();
const toastStore = useToastStore();

onMounted(async () => {
  try {
    const response = await axios.get(`/api/products/${route.params.slug}`);
    product.value = response.data;
  } catch (error) {
    console.error('Error fetching product:', error);
    toastStore.addToast('Failed to load product details', 'error');
  }
});

function addToCart() {
  if (product.value) {
    cartStore.addToCart(product.value);
    toastStore.addToast(`Added ${product.value.name} to cart`);
  }
}
</script>