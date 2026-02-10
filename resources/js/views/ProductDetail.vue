<template>
  <div v-if="product" class="space-y-12 pb-12">
    <!-- Main Product Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-10 flex flex-col lg:flex-row gap-12">
      <!-- Image Gallery Section -->
      <div class="lg:w-3/5 space-y-4">
        <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 relative group">
          <img 
            :src="activeImage" 
            :alt="product.name" 
            class="w-full h-full object-contain transition duration-700 group-hover:scale-105"
          >
          <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
        </div>
        
        <!-- Thumbnails -->
        <div v-if="allImages.length > 1" class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
          <button 
            v-for="(img, index) in allImages" 
            :key="index"
            @click="activeImageIndex = index"
            class="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0 border-2 transition-all"
            :class="activeImageIndex === index ? 'border-blue-600 shadow-md ring-2 ring-blue-50' : 'border-gray-100 hover:border-blue-200'"
          >
            <img :src="img" class="w-full h-full object-cover">
          </button>
        </div>
      </div>

      <!-- Quick Details Section -->
      <div class="lg:w-2/5 flex flex-col">
        <div class="mb-4 flex items-center gap-3">
          <span class="text-blue-600 font-bold tracking-wider uppercase text-xs bg-blue-50 px-3 py-1.5 rounded-lg">
            {{ product.brand?.name }}
          </span>
          <span class="text-gray-400 text-xs font-bold uppercase tracking-widest">
            {{ product.category?.name }}
          </span>
        </div>
        
        <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 leading-tight">
          {{ product.name }}
        </h1>
        
        <div class="flex items-center gap-4 mb-8">
          <span class="text-4xl font-black text-blue-600">${{ parseFloat(product.price).toLocaleString() }}</span>
          <div class="h-6 w-px bg-gray-200"></div>
          <span v-if="product.stock > 0" class="text-green-600 text-sm font-bold flex items-center gap-1.5">
            <CheckCircleIcon class="w-5 h-5" /> In Stock
          </span>
          <span v-else class="text-red-600 text-sm font-bold flex items-center gap-1.5">
            <XCircleIcon class="w-5 h-5" /> Out of Stock
          </span>
        </div>

        <div class="bg-gray-50 rounded-2xl p-6 mb-8 border border-gray-100">
          <p class="text-gray-600 leading-relaxed italic text-lg">
            "{{ product.description }}"
          </p>
        </div>
        
        <div class="mt-auto space-y-4">
          <div class="flex items-center gap-4">
            <div class="flex items-center bg-gray-100 rounded-xl px-4 py-2 border border-gray-200">
              <button @click="quantity > 1 && quantity--" class="p-1 hover:text-blue-600"><MinusIcon class="w-5 h-5" /></button>
              <span class="w-12 text-center font-bold">{{ quantity }}</span>
              <button @click="quantity < product.stock && quantity++" class="p-1 hover:text-blue-600"><PlusIcon class="w-5 h-5" /></button>
            </div>
            <button 
              @click="addToCart" 
              :disabled="product.stock <= 0"
              class="flex-1 bg-blue-600 text-white px-8 py-4 rounded-xl text-lg font-black hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center gap-3 shadow-xl shadow-blue-600/20 active:scale-95"
            >
              <ShoppingBagIcon class="w-6 h-6" />
              Add to Cart
            </button>
          </div>
          
          <router-link to="/" class="flex items-center justify-center gap-2 text-gray-400 hover:text-gray-900 font-bold transition text-sm py-2">
            <ArrowLeftIcon class="w-4 h-4" />
            Continue Shopping
          </router-link>
        </div>
      </div>
    </div>

    <!-- Secondary Info: Tabs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Specifications -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-6 md:p-8 border-b border-gray-50 flex items-center justify-between">
            <h2 class="text-2xl font-black text-gray-900">Technical Specifications</h2>
            <div class="bg-gray-100 p-2 rounded-xl">
              <ClipboardDocumentListIcon class="w-6 h-6 text-gray-400" />
            </div>
          </div>
          <div class="p-6 md:p-8">
            <div v-if="product.specifications && Object.keys(product.specifications).length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
              <div v-for="(value, label) in product.specifications" :key="label" class="flex justify-between py-3 border-b border-gray-50 last:md:border-b-0">
                <span class="text-gray-500 font-medium capitalize">{{ label.replace('_', ' ') }}</span>
                <span class="text-gray-900 font-bold">{{ value }}</span>
              </div>
            </div>
            <div v-else class="text-center py-8 text-gray-400">
              No detailed specifications available for this product.
            </div>
          </div>
        </div>

        <!-- Full Description / Features -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
          <h2 class="text-2xl font-black text-gray-900 mb-6">Product Overview</h2>
          <div class="prose prose-blue max-w-none text-gray-600 text-lg leading-loose">
            {{ product.description }}
            <!-- We could add more long-form content here if the DB supported it -->
          </div>
        </div>
      </div>

      <!-- Sidebar Info -->
      <div class="space-y-6">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-xl shadow-blue-600/20">
          <h3 class="text-xl font-black mb-4">Why buy from us?</h3>
          <ul class="space-y-4">
            <li class="flex gap-3 items-start">
              <div class="bg-white/20 p-1.5 rounded-lg mt-0.5"><TruckIcon class="w-5 h-5" /></div>
              <div>
                <p class="font-bold">Free Express Shipping</p>
                <p class="text-blue-100 text-sm">On all orders over $1,000</p>
              </div>
            </li>
            <li class="flex gap-3 items-start">
              <div class="bg-white/20 p-1.5 rounded-lg mt-0.5"><ShieldCheckIcon class="w-5 h-5" /></div>
              <div>
                <p class="font-bold">2 Year Warranty</p>
                <p class="text-blue-100 text-sm">Official manufacturer warranty</p>
              </div>
            </li>
            <li class="flex gap-3 items-start">
              <div class="bg-white/20 p-1.5 rounded-lg mt-0.5"><ArrowPathIcon class="w-5 h-5" /></div>
              <div>
                <p class="font-bold">Easy Returns</p>
                <p class="text-blue-100 text-sm">30-day money back guarantee</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="flex flex-col justify-center items-center py-32 space-y-4">
    <div class="relative">
      <div class="w-16 h-16 rounded-full border-4 border-blue-100 border-t-blue-600 animate-spin"></div>
      <CameraIcon class="w-6 h-6 text-blue-600 absolute inset-0 m-auto animate-pulse" />
    </div>
    <p class="text-gray-400 font-bold animate-pulse">Fetching product details...</p>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useCartStore } from '../stores/cart';
import { useToastStore } from '../stores/toast';
import { 
  ShoppingBagIcon, 
  CheckCircleIcon, 
  XCircleIcon,
  PlusIcon,
  MinusIcon,
  ArrowLeftIcon,
  ClipboardDocumentListIcon,
  TruckIcon,
  ShieldCheckIcon,
  ArrowPathIcon,
  CameraIcon
} from '@heroicons/vue/24/solid';

const route = useRoute();
const product = ref(null);
const cartStore = useCartStore();
const toastStore = useToastStore();
const quantity = ref(1);
const activeImageIndex = ref(0);

const allImages = computed(() => {
  if (!product.value) return [];
  const gallery = Array.isArray(product.value.gallery) ? product.value.gallery : [];
  return [product.value.image, ...gallery].filter(Boolean);
});

const activeImage = computed(() => {
  return allImages.value[activeImageIndex.value] || product.value?.image;
});

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
    // We can extend addToCart to accept quantity if the store supports it
    for(let i = 0; i < quantity.value; i++) {
      cartStore.addToCart(product.value);
    }
    toastStore.addToast(`Added ${quantity.value} ${product.value.name} to cart`);
  }
}
</script>