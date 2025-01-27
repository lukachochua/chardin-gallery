<div class="fixed top-4 right-4 z-50" x-data="{ show: false, message: '' }" x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     @cart-updated.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)">
    <div class="bg-green-500 text-white px-4 py-2 rounded-md shadow-lg" x-text="message"></div>
</div>