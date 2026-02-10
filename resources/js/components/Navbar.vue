<template>
  <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-gray-100 transition-all duration-300">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2.5 group">
          <div class="bg-blue-600 text-white p-1.5 rounded-xl group-hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
            <CameraIcon class="w-6 h-6" />
          </div>
          <span class="text-xl font-black text-gray-900 tracking-tighter">Camera<span class="text-blue-600">Shop</span></span>
        </router-link>

        <!-- Search Bar (Desktop) -->
        <div class="hidden md:flex flex-1 max-w-md mx-8 relative">
          <form @submit.prevent="handleSearch" class="relative w-full group">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
              <MagnifyingGlassIcon class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" />
            </div>
            <input 
              v-model="searchQuery"
              @input="onSearchInput"
              @focus="isSearchFocused = true"
              type="text" 
              placeholder="Search cameras, lenses..." 
              class="block w-full pl-11 pr-4 py-2 bg-gray-100 border-transparent border-2 rounded-2xl text-sm focus:bg-white focus:border-blue-500/20 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none"
            >
          </form>

          <!-- Search Results Dropdown -->
          <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
          >
            <div 
              v-if="isSearchFocused && (searchResults.length > 0 || isSearching)" 
              v-click-away="() => isSearchFocused = false"
              class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50"
            >
              <div v-if="isSearching" class="p-4 text-center">
                <div class="inline-block animate-spin rounded-full h-5 w-5 border-2 border-blue-600 border-t-transparent"></div>
              </div>
              <div v-else-if="searchResults.length > 0" class="max-h-96 overflow-y-auto">
                <router-link 
                  v-for="product in searchResults" 
                  :key="product.id"
                  :to="'/product/' + product.slug"
                  @click="isSearchFocused = false"
                  class="flex items-center gap-4 p-3 hover:bg-gray-50 transition border-b border-gray-50 last:border-0"
                >
                  <img :src="product.image" :alt="product.name" class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                  <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-gray-900 truncate">{{ product.name }}</h4>
                    <p class="text-xs text-gray-500 truncate">{{ product.category?.name }}</p>
                  </div>
                  <div class="text-sm font-bold text-blue-600">
                    ${{ parseFloat(product.price).toLocaleString() }}
                  </div>
                </router-link>
                <div 
                  @click="handleSearch"
                  class="p-3 text-center text-sm font-bold text-blue-600 hover:bg-blue-50 cursor-pointer transition"
                >
                  View all results
                </div>
              </div>
            </div>
          </transition>
        </div>

        <!-- Navigation Actions -->
        <div class="flex items-center gap-2 md:gap-5">
          <!-- User Menu (Guest) -->
          <router-link to="/admin/login" class="hidden sm:flex items-center gap-2 text-gray-600 hover:text-blue-600 font-bold transition text-sm px-3 py-2 rounded-xl hover:bg-blue-50">
            <UserIcon class="w-5 h-5" />
            <span>Sign In</span>
          </router-link>

          <!-- Cart -->
          <router-link to="/cart" class="relative group p-2 rounded-xl hover:bg-gray-100 transition">
            <ShoppingBagIcon class="w-6 h-6 text-gray-700 group-hover:text-blue-600 transition" />
            <span 
              v-if="cartStore.itemCount > 0" 
              class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-black rounded-full h-5 w-5 flex items-center justify-center shadow-lg shadow-blue-600/30 border-2 border-white animate-in zoom-in"
            >
              {{ cartStore.itemCount }}
            </span>
          </router-link>

          <!-- Mobile Menu Toggle -->
          <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="md:hidden p-2 rounded-xl hover:bg-gray-100 transition">
            <Bars3Icon v-if="!isMobileMenuOpen" class="w-6 h-6 text-gray-700" />
            <XMarkIcon v-else class="w-6 h-6 text-gray-700" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="transform -translate-y-4 opacity-0"
      enter-to-class="transform translate-y-0 opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="transform translate-y-0 opacity-100"
      leave-to-class="transform -translate-y-4 opacity-0"
    >
      <div v-if="isMobileMenuOpen" class="md:hidden bg-white border-t border-gray-100 p-4 space-y-4 shadow-xl">
        <form @submit.prevent="handleSearch" class="relative">
          <MagnifyingGlassIcon class="absolute left-3.5 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Search..." 
            class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 outline-none"
          >
        </form>
        <div class="grid grid-cols-2 gap-2">
          <router-link to="/" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gray-50 text-gray-700 font-bold text-sm">
            Shop
          </router-link>
          <router-link to="/admin/login" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-blue-50 text-blue-600 font-bold text-sm">
            Sign In
          </router-link>
        </div>
      </div>
    </transition>
  </nav>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import { useCartStore } from '../stores/cart';
import { 
  ShoppingBagIcon, 
  CameraIcon, 
  MagnifyingGlassIcon, 
  UserIcon,
  Bars3Icon,
  XMarkIcon
} from '@heroicons/vue/24/outline';

const router = useRouter();
const route = useRoute();
const cartStore = useCartStore();
const isMobileMenuOpen = ref(false);
const searchQuery = ref(route.query.search || '');

// Live search states
const searchResults = ref([]);
const isSearching = ref(false);
const isSearchFocused = ref(false);
let debounceTimeout = null;

// v-click-away directive
const vClickAway = {
  mounted(el, binding) {
    el.clickAwayEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value(event);
      }
    };
    document.addEventListener('click', el.clickAwayEvent);
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickAwayEvent);
  },
};

// Sync search query with URL changes
watch(() => route.query.search, (newVal) => {
  searchQuery.value = newVal || '';
});

async function onSearchInput() {
  if (debounceTimeout) clearTimeout(debounceTimeout);
  
  if (searchQuery.value.trim().length < 2) {
    searchResults.value = [];
    isSearching.value = false;
    return;
  }

  isSearching.value = true;
  debounceTimeout = setTimeout(async () => {
    try {
      const response = await axios.get('/api/products', {
        params: { search: searchQuery.value }
      });
      // Limit to top 5 results for the dropdown
      searchResults.value = response.data.slice(0, 5);
    } catch (error) {
      console.error('Live search error:', error);
    } finally {
      isSearching.value = false;
    }
  }, 300);
}

function handleSearch() {
  if (searchQuery.value.trim()) {
    router.push({
      path: '/',
      query: { ...route.query, search: searchQuery.value }
    });
  }
  isSearchFocused.value = false;
  isMobileMenuOpen.value = false;
}
</script>