<template>
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>
    
    <div v-if="success" class="bg-green-50 border border-green-200 text-green-800 p-8 rounded-2xl text-center">
      <CheckCircleIcon class="w-16 h-16 mx-auto mb-4 text-green-600" />
      <h2 class="text-2xl font-bold mb-2">Order Placed Successfully!</h2>
      <p class="text-green-700 mb-6">Thank you for your purchase. We've sent a confirmation email to you.</p>
      <router-link to="/" class="inline-block bg-green-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-green-700 transition">
        Back to Home
      </router-link>
    </div>

    <form v-else @submit.prevent="submitOrder" class="flex flex-col md:flex-row gap-8">
      <div class="flex-1 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
            <MapPinIcon class="w-6 h-6 text-blue-600" /> Shipping Information
        </h2>
        
        <div class="mb-6">
          <label class="block text-gray-700 font-bold mb-2">Full Name</label>
          <input type="text" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="John Doe">
        </div>

        <div class="mb-6">
          <label class="block text-gray-700 font-bold mb-2">Email Address</label>
          <input type="email" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="john@example.com">
        </div>

        <div class="mb-6">
          <label class="block text-gray-700 font-bold mb-2">Shipping Address</label>
          <textarea v-model="form.address" required class="w-full border border-gray-300 rounded-lg p-3 h-32 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Enter your full address here..."></textarea>
        </div>
      </div>

      <div class="w-full md:w-96">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
            <h3 class="font-bold text-xl mb-4">Order Summary</h3>
            <div class="space-y-3 mb-6">
                <div v-for="item in cartStore.items" :key="item.id" class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ item.name }} <span class="text-xs text-gray-400">x{{ item.quantity }}</span></span>
                    <span class="font-medium">${{ (item.price * item.quantity).toFixed(2) }}</span>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-4 mb-6">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>${{ (cartStore.totalPrice * 1.08).toFixed(2) }}</span>
                </div>
            </div>

            <button type="submit" :disabled="loading" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition disabled:opacity-70 disabled:cursor-not-allowed shadow-lg hover:shadow-blue-600/30 flex justify-center items-center gap-2">
                <span v-if="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></span>
                <span>{{ loading ? 'Processing...' : 'Place Order' }}</span>
            </button>
            
            <p v-if="error" class="text-red-500 mt-4 text-center text-sm bg-red-50 p-2 rounded">{{ error }}</p>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useCartStore } from '../stores/cart';
import { useToastStore } from '../stores/toast';
import axios from 'axios';
import { CheckCircleIcon, MapPinIcon } from '@heroicons/vue/24/solid';

const cartStore = useCartStore();
const toastStore = useToastStore();
const form = ref({ address: '' });
const loading = ref(false);
const success = ref(false);
const error = ref(null);

async function submitOrder() {
  if (cartStore.items.length === 0) {
    toastStore.addToast('Your cart is empty', 'error');
    return;
  }

  loading.value = true;
  error.value = null;

  try {
    await axios.post('/api/orders', {
      items: cartStore.items.map(i => ({ product_id: i.id, quantity: i.quantity })),
      total_price: cartStore.totalPrice * 1.08, // Including tax logic from frontend
      shipping_address: form.value.address
    });
    
    cartStore.clearCart();
    success.value = true;
    toastStore.addToast('Order placed successfully!', 'success');
  } catch (e) {
    error.value = e.response?.data?.message || 'Order failed. Please try again.';
    toastStore.addToast('Failed to place order', 'error');
  } finally {
    loading.value = false;
  }
}
</script>