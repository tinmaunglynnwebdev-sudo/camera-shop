<template>
  <div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>
    
    <div v-if="cartStore.items.length === 0" class="bg-white p-12 rounded-2xl shadow-sm border border-gray-100 text-center">
      <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
        <ShoppingBagIcon class="w-10 h-10 text-gray-400" />
      </div>
      <p class="text-gray-500 mb-6 text-lg">Your cart is currently empty.</p>
      <router-link to="/" class="inline-flex items-center gap-2 text-blue-600 font-bold hover:underline">
        <ArrowLeftIcon class="w-4 h-4" /> Start Shopping
      </router-link>
    </div>

    <div v-else class="flex flex-col lg:flex-row gap-8">
      <!-- Cart Items -->
      <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
          <div v-for="item in cartStore.items" :key="item.id" class="flex items-center justify-between py-6 border-b border-gray-100 last:border-0">
            <div class="flex items-center gap-4">
              <div class="w-24 h-24 bg-gray-50 rounded-lg overflow-hidden shrink-0">
                <img :src="item.image" class="w-full h-full object-cover" />
              </div>
              <div>
                <h3 class="font-bold text-gray-900 text-lg mb-1">{{ item.name }}</h3>
                <p class="text-gray-500 text-sm mb-2">{{ item.brand?.name }}</p>
                <p class="text-blue-600 font-bold">${{ parseFloat(item.price).toLocaleString() }}</p>
              </div>
            </div>
            
            <div class="flex items-center gap-6">
              <div class="flex items-center bg-gray-50 rounded-lg border border-gray-200">
                <button class="px-3 py-1 text-gray-600 hover:text-blue-600 font-bold" @click="item.quantity > 1 ? item.quantity-- : null">-</button>
                <input type="number" v-model.number="item.quantity" min="1" class="w-12 bg-transparent text-center text-sm font-semibold focus:outline-none" readonly>
                <button class="px-3 py-1 text-gray-600 hover:text-blue-600 font-bold" @click="item.quantity++">+</button>
              </div>
              
              <button @click="removeItem(item)" class="text-gray-400 hover:text-red-500 transition p-2 rounded-full hover:bg-red-50">
                <TrashIcon class="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Summary -->
      <div class="w-full lg:w-96">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
          <h2 class="text-xl font-bold mb-6">Order Summary</h2>
          
          <div class="space-y-4 mb-6">
            <div class="flex justify-between text-gray-600">
              <span>Subtotal</span>
              <span>${{ cartStore.totalPrice.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Shipping</span>
              <span class="text-green-600 font-medium">Free</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Tax (Estimated)</span>
              <span>${{ (cartStore.totalPrice * 0.08).toFixed(2) }}</span>
            </div>
          </div>
          
          <div class="border-t border-gray-100 pt-6 mb-8">
            <div class="flex justify-between font-bold text-xl text-gray-900">
              <span>Total</span>
              <span>${{ (cartStore.totalPrice * 1.08).toFixed(2) }}</span>
            </div>
          </div>
          
          <router-link to="/checkout" class="block w-full bg-blue-600 text-white text-center py-4 rounded-xl hover:bg-blue-700 transition font-bold shadow-lg hover:shadow-blue-600/30">
            Proceed to Checkout
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '../stores/cart';
import { useToastStore } from '../stores/toast';
import { TrashIcon, ShoppingBagIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline';

const cartStore = useCartStore();
const toastStore = useToastStore();

function removeItem(item) {
  cartStore.removeFromCart(item.id);
  toastStore.addToast(`Removed ${item.name} from cart`, 'info');
}
</script>