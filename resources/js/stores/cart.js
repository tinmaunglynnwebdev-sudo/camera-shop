import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useCartStore = defineStore('cart', () => {
    const items = ref([]);

    const itemCount = computed(() => items.value.reduce((sum, item) => sum + item.quantity, 0));
    const totalPrice = computed(() => items.value.reduce((sum, item) => sum + (item.price * item.quantity), 0));

    function addToCart(product) {
        const existing = items.value.find(i => i.id === product.id);
        if (existing) {
            existing.quantity++;
        } else {
            items.value.push({ ...product, quantity: 1 });
        }
    }

    function removeFromCart(productId) {
        items.value = items.value.filter(i => i.id !== productId);
    }

    function clearCart() {
        items.value = [];
    }

    return { items, itemCount, totalPrice, addToCart, removeFromCart, clearCart };
});