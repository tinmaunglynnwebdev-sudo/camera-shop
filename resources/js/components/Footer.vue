<template>
  <footer class="bg-gray-900 text-white pt-12 pb-8">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
        <div>
          <h3 class="text-xl font-bold mb-4">CameraShop</h3>
          <p class="text-gray-400 text-sm">Your premium destination for professional photography gear and accessories.</p>
        </div>
        <div>
          <h4 class="font-bold mb-4">Shop</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="#" class="hover:text-white transition">Cameras</a></li>
            <li><a href="#" class="hover:text-white transition">Lenses</a></li>
            <li><a href="#" class="hover:text-white transition">Accessories</a></li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold mb-4">Support</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
            <li><a href="#" class="hover:text-white transition">Shipping Policy</a></li>
            <li><a href="#" class="hover:text-white transition">Returns</a></li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold mb-4">Newsletter</h4>
          <div class="flex gap-2">
            <input type="email" placeholder="Enter your email" class="bg-gray-800 text-white px-3 py-2 rounded text-sm w-full focus:outline-none focus:ring-1 focus:ring-blue-500">
            <button class="bg-blue-600 px-4 py-2 rounded text-sm font-bold hover:bg-blue-700 transition">Subscribe</button>
          </div>
        </div>
      </div>
      <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-500">
        &copy; {{ new Date().getFullYear() }} CameraShop. All rights reserved.
      </div>
    </div>
  </footer>
</template>