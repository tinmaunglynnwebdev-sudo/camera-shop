<template>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center gap-4 mb-8">
      <div class="p-3 bg-red-50 rounded-2xl">
        <HeartIcon class="w-8 h-8 text-red-500" />
      </div>
      <div>
        <h1 class="text-3xl font-black text-gray-900">My Wishlist</h1>
        <p class="text-gray-500">You have {{ wishlistStore.itemCount }} items saved</p>
      </div>
    </div>

    <div v-if="wishlistStore.loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="i in 4" :key="i" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-pulse">
        <div class="h-48 bg-gray-100"></div>
        <div class="p-5 space-y-3">
          <div class="h-4 bg-gray-100 rounded w-1/4"></div>
          <div class="h-6 bg-gray-100 rounded w-3/4"></div>
          <div class="h-4 bg-gray-100 rounded w-full"></div>
        </div>
      </div>
    </div>

    <div v-else-if="wishlistStore.items.length === 0" class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-200">
      <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
        <HeartIcon class="w-10 h-10 text-gray-300" />
      </div>
      <h2 class="text-xl font-bold text-gray-900 mb-2">Your wishlist is empty</h2>
      <p class="text-gray-500 mb-8">Save items you like to see them here later.</p>
      <router-link to="/" class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
        Start Shopping
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="product in wishlistStore.items" :key="product.id" class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
        <div class="relative overflow-hidden aspect-square">
          <img :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
          <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          
          <!-- Remove from Wishlist -->
          <button 
            @click="wishlistStore.toggleWishlist(product)"
            class="absolute top-4 right-4 p-2 bg-white/90 backdrop-blur rounded-full shadow-lg text-red-500 hover:bg-red-500 hover:text-white transition-colors z-10"
          >
            <XMarkIcon class="w-5 h-5" />
          </button>
        </div>

        <div class="p-5 flex-1 flex flex-col">
          <div class="flex justify-between items-start mb-2">
            <span class="text-[10px] font-black uppercase tracking-widest text-blue-600 bg-blue-50 px-2 py-1 rounded-md">
              {{ product.category?.name }}
            </span>
            <span class="text-xs font-bold text-gray-400">{{ product.brand?.name }}</span>
          </div>
          
          <router-link :to="'/product/' + product.slug" class="text-lg font-bold text-gray-900 hover:text-blue-600 transition mb-2 block line-clamp-2">
            {{ product.name }}
          </router-link>

          <div class="mt-auto flex items-center justify-between">
            <div class="flex flex-col">
              <span class="text-xl font-black text-gray-900">${{ parseFloat(product.price).toLocaleString() }}</span>
              <span v-if="product.original_price" class="text-xs text-gray-400 line-through">${{ parseFloat(product.original_price).toLocaleString() }}</span>
            </div>
            
            <button 
              @click="cartStore.addToCart(product)"
              class="p-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/20"
            >
              <ShoppingBagIcon class="w-5 h-5" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useWishlistStore } from '../stores/wishlist';
import { useCartStore } from '../stores/cart';
import { HeartIcon, ShoppingBagIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const wishlistStore = useWishlistStore();
const cartStore = useCartStore();
</script>
