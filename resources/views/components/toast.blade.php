<div x-data="{ show: false, message: '', isError: false }" x-show="show" x-cloak x-transition:enter="transition transform ease-out duration-300"
    x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @cart-updated.window="show = true; message = $event.detail.message; isError = $event.detail.message.toLowerCase().includes('failed') || $event.detail.message.toLowerCase().includes('error'); setTimeout(() => show = false, 4000)"
    class="fixed inset-x-0 top-16 z-[9999] flex items-center justify-center px-4 py-6 pointer-events-none sm:p-6 overflow-y-auto">

    <!-- Toast Notification -->
    <div x-show="show"
        class="max-w-md w-full bg-white shadow-lg rounded-xl pointer-events-auto ring-1 ring-black/10 overflow-hidden">
        <div class="p-4" :class="isError ? 'bg-red-100/90' : 'bg-green-100/90'">
            <div class="flex items-center gap-4">
                <!-- Icon Container -->
                <div class="flex-shrink-0">
                    <svg x-show="!isError" class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <svg x-show="isError" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Message -->
                <div class="flex-1">
                    <p x-text="message" :class="isError ? 'text-red-800' : 'text-green-800'"
                        class="text-sm font-semibold leading-tight">
                    </p>
                </div>

                <!-- Close Button -->
                <button @click="show = false"
                    class="flex-shrink-0 p-1 rounded-full hover:bg-black/10 transition-colors focus:outline-none focus:ring-2 focus:ring-black/20">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
