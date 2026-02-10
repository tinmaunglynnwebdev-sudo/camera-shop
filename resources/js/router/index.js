import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import ProductDetail from '../views/ProductDetail.vue';
import Cart from '../views/Cart.vue';
import Checkout from '../views/Checkout.vue';

const routes = [
    { path: '/', component: Home },
    { path: '/product/:slug', component: ProductDetail, props: true },
    { path: '/cart', component: Cart },
    { path: '/checkout', component: Checkout },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;