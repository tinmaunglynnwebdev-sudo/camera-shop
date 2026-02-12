<template>
  <div>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl overflow-hidden mb-12 shadow-2xl relative">
      <div class="absolute inset-0 bg-black/20"></div>
      <div class="relative z-10 px-8 py-16 md:py-24 md:px-16 text-center md:text-left">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
          Capture Your <br class="hidden md:block" />
          <span class="text-blue-400">Perfect Moment</span>
        </h1>
        <p class="text-gray-300 text-lg md:text-xl mb-8 max-w-xl">
          Discover professional-grade cameras and lenses for every photographer.
        </p>
        <div class="flex flex-wrap gap-4">
          <button @click="scrollToProducts" class="bg-blue-600 text-white px-8 py-3.5 rounded-full font-bold hover:bg-blue-700 transition shadow-lg hover:shadow-blue-600/30 transform hover:-translate-y-1">
            Shop Now
          </button>
          <router-link 
            v-if="!authStore.user" 
            to="/login" 
            class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-8 py-3.5 rounded-full font-bold hover:bg-white/20 transition shadow-lg transform hover:-translate-y-1"
          >
            Sign In
          </router-link>
          <router-link 
            v-else 
            to="/profile" 
            class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-8 py-3.5 rounded-full font-bold hover:bg-white/20 transition shadow-lg transform hover:-translate-y-1"
          >
            View Profile
          </router-link>
        </div>
      </div>
    </div>

    <!-- Most Viewed Products -->
    <div v-if="mostViewedProducts.length > 0 && !loading" class="mb-16">
      <div class="flex items-center gap-3 mb-8">
        <div class="p-2 bg-orange-50 rounded-xl">
          <FireIcon class="w-6 h-6 text-orange-500" />
        </div>
        <div>
          <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Popular Right Now</h2>
          <p class="text-sm text-gray-500 font-medium">The most viewed cameras this week</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="product in mostViewedProducts" :key="product.id" class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
          <router-link :to="'/product/' + product.slug" class="relative overflow-hidden aspect-[4/5] block">
            <img :src="product.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur px-2.5 py-1 rounded-lg text-[10px] font-black text-gray-600 flex items-center gap-1.5 shadow-sm">
              <EyeIcon class="w-3.5 h-3.5 text-blue-500" />
              {{ (product.views_count || 0).toLocaleString() }} VIEWS
            </div>
          </router-link>
          
          <div class="relative">
            <!-- Wishlist Button - Absolute positioned relative to card container or moved here -->
            <button 
              @click.stop="wishlistStore.toggleWishlist(product)"
              class="absolute -top-12 right-4 p-2 bg-white/90 backdrop-blur rounded-full shadow-lg transition-all duration-300 transform active:scale-90 z-10"
              :class="wishlistStore.isInWishlist(product.id) ? 'text-red-500' : 'text-gray-400 hover:text-red-500'"
            >
              <HeartIcon class="w-5 h-5" :class="{ 'fill-current': wishlistStore.isInWishlist(product.id) }" />
            </button>
          </div>

          <div class="p-4">
            <router-link :to="'/product/' + product.slug" class="font-bold text-gray-900 hover:text-blue-600 transition block mb-2 line-clamp-1">
              {{ product.name }}
            </router-link>
            <div class="flex items-center justify-between">
              <span class="font-black text-blue-600">${{ parseFloat(product.sale_price || product.price).toLocaleString() }}</span>
              <button @click="cartStore.addToCart(product)" class="text-xs font-bold text-gray-500 hover:text-blue-600 transition flex items-center gap-1">
                <ShoppingBagIcon class="w-4 h-4" /> Add
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col md:flex-row gap-8">
      <!-- Sidebar Filters -->
      <aside class="w-full md:w-64 shrink-0">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-24">
          <div class="mb-8">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
              <FunnelIcon class="w-5 h-5 text-blue-600" /> Filters
            </h3>
            
            <div class="mb-6">
              <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Categories</h4>
              <div class="space-y-2">
                <label v-for="cat in categories" :key="cat.id" class="flex items-center gap-3 cursor-pointer group">
                  <div class="relative flex items-center">
                    <input 
                      type="checkbox" 
                      :value="cat.slug" 
                      v-model="selectedCategories"
                      class="peer h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition"
                    >
                  </div>
                  <span class="text-gray-600 group-hover:text-blue-600 transition">{{ cat.name }}</span>
                </label>
              </div>
            </div>

            <div>
              <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Brands</h4>
               <!-- Hardcoded brands for demo if API doesn't support yet, ideally fetch from API -->
               <div class="space-y-2">
                 <label v-for="brand in brands" :key="brand" class="flex items-center gap-3 cursor-pointer group">
                  <input type="checkbox" :value="brand.toLowerCase()" v-model="selectedBrands" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                  <span class="text-gray-600 group-hover:text-blue-600 transition">{{ brand }}</span>
                 </label>
               </div>
            </div>
          </div>
        </div>
      </aside>

      <!-- Product Grid -->
      <div id="products-grid" class="flex-1">
        <div class="flex flex-col mb-6">
          <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">
              {{ $route.query.search ? 'Search Results' : 'Latest Arrivals' }}
            </h2>
            <span class="text-gray-500 text-sm">{{ filteredProducts.length }} products found</span>
          </div>
          <p v-if="$route.query.search" class="text-gray-500 mt-1">
            Showing results for "<span class="text-blue-600 font-medium">{{ $route.query.search }}</span>"
          </p>
        </div>

        <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
           <!-- Skeletons -->
           <div v-for="i in 6" :key="i" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-96 animate-pulse">
             <div class="bg-gray-200 h-48 w-full"></div>
             <div class="p-5 space-y-3">
               <div class="h-4 bg-gray-200 rounded w-1/4"></div>
               <div class="h-6 bg-gray-200 rounded w-3/4"></div>
               <div class="h-4 bg-gray-200 rounded w-full"></div>
             </div>
           </div>
        </div>

        <div v-else-if="filteredProducts.length === 0" class="text-center py-20 bg-white rounded-xl border border-gray-100">
            <p class="text-gray-500 text-lg">No products found matching your filters.</p>
            <button @click="clearFilters" class="mt-4 text-blue-600 font-medium hover:underline">Clear all filters</button>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="product in filteredProducts" :key="product.id" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
            <div class="relative h-48 overflow-hidden bg-gray-100">
              <img :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
              
              <div v-if="product.sale_price && product.original_price && product.sale_price < product.original_price" class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-black px-2 py-1 rounded-full uppercase shadow-lg z-10">
                Sale {{ Math.round((1 - product.sale_price / product.original_price) * 100) }}% Off
              </div>

              <!-- Wishlist Button -->
              <button 
                @click.stop="wishlistStore.toggleWishlist(product)"
                class="absolute top-3 right-3 p-2 bg-white/90 backdrop-blur rounded-full shadow-lg transition-all duration-300 transform active:scale-90 z-20"
                :class="wishlistStore.isInWishlist(product.id) ? 'text-red-500' : 'text-gray-400 hover:text-red-500'"
              >
                <HeartIcon class="w-5 h-5" :class="{ 'fill-current': wishlistStore.isInWishlist(product.id) }" />
              </button>
            </div>
            
            <div class="p-5 flex flex-col flex-1">
              <div class="text-xs font-bold text-blue-600 mb-1 uppercase tracking-wide">{{ product.brand?.name }}</div>
              <router-link :to="'/product/' + product.slug" class="block">
                <h2 class="text-lg font-bold text-gray-900 mb-2 leading-tight hover:text-blue-600 transition">{{ product.name }}</h2>
              </router-link>
              <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ product.description }}</p>
              
              <div class="mt-auto flex justify-between items-end pt-4 border-t border-gray-50">
                <div class="flex flex-col">
                  <span v-if="product.original_price && product.sale_price && product.sale_price < product.original_price" class="text-xs text-gray-400 line-through mb-0.5">
                    ${{ parseFloat(product.original_price).toLocaleString() }}
                  </span>
                  <span class="text-xl font-bold text-gray-900">
                    ${{ parseFloat(product.sale_price || product.price).toLocaleString() }}
                  </span>
                </div>
                <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full mb-1">In Stock</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';
import { useCartStore } from '../stores/cart';
import { useToastStore } from '../stores/toast';
import { useWishlistStore } from '../stores/wishlist';
import { 
  FunnelIcon, 
  PlusIcon, 
  HeartIcon, 
  FireIcon, 
  EyeIcon, 
  ShoppingBagIcon 
} from '@heroicons/vue/24/outline';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const cartStore = useCartStore();
const toastStore = useToastStore();
const wishlistStore = useWishlistStore();

const products = ref([]);
const mostViewedProducts = ref([]);
const categories = ref([]);
const loading = ref(true);

const selectedCategories = ref(route.query.category ? route.query.category.split(',') : []);
const selectedBrands = ref(route.query.brand ? route.query.brand.split(',') : []);
const brands = ['Canon', 'Nikon', 'Sony', 'Fujifilm', 'Panasonic']; // Mock brands for filter

function scrollToProducts() {
  const el = document.getElementById('products-grid');
  if (el) el.scrollIntoView({ behavior: 'smooth' });
}

// Sync filters with URL
watch(() => [selectedCategories.value, selectedBrands.value], () => {
  const query = { ...route.query };
  
  if (selectedCategories.value.length > 0) {
    query.category = selectedCategories.value.join(',');
  } else {
    delete query.category;
  }
  
  if (selectedBrands.value.length > 0) {
    query.brand = selectedBrands.value.join(',');
  } else {
    delete query.brand;
  }
  
  // Use replace instead of push to avoid cluttering history
  router.replace({ query });
}, { deep: true });

async function fetchMostViewed() {
  try {
    const response = await axios.get('/api/products/most-viewed');
    mostViewedProducts.value = response.data;
  } catch (error) {
    console.error('Failed to fetch most viewed products', error);
  }
}

async function fetchProducts() {
  loading.value = true;
  try {
    const params = {};
    if (route.query.search) params.search = route.query.search;
    if (route.query.category) params.category = route.query.category;
    if (route.query.brand) params.brand = route.query.brand;
    
    const [prodRes, catRes] = await Promise.all([
      axios.get('/api/products', { params }),
      axios.get('/api/categories')
    ]);
    products.value = prodRes.data;
    categories.value = catRes.data;
  } catch (error) {
    console.error('Error fetching data:', error);
    toastStore.addToast('Failed to load products', 'error');
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchProducts();
  fetchMostViewed();
  if (authStore.token || authStore.user) {
    wishlistStore.fetchWishlist();
  }
});

// Re-fetch products when search or filters change in URL
watch(() => [route.query.search, route.query.category, route.query.brand], () => {
  fetchProducts();
});

const filteredProducts = computed(() => {
  // Since we now filter on backend, we just return products.value
  return products.value;
});

function addToCart(product) {
  cartStore.addToCart(product);
  toastStore.addToast(`Added ${product.name} to cart`);
}

function clearFilters() {
  selectedCategories.value = [];
  selectedBrands.value = [];
  router.push({ path: '/', query: {} });
}
</script>