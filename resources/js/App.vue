<template>
  <div class="min-h-screen bg-gray-50 text-gray-900 font-sans flex flex-col">
    <Navbar />
    <main class="flex-grow">
      <router-view class="container mx-auto px-4 py-8" />
    </main>
    <Footer />
    <ToastManager />
  </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import ToastManager from './components/ToastManager.vue';
</script>