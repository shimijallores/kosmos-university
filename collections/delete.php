 <div x-cloak x-show="deleteModal" x-transition.opacity.duration.200ms x-on:keydown.esc.window="deleteModal = false"
     x-on:click.self="deleteModal = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 p-4" role="dialog"
     aria-modal="true" aria-labelledby="defaultModalTitle">
     <!-- Modal Dialog -->
     <form action="destroy.php" method="POST" x-show="deleteModal"
         x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
         x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
         class="w-full max-w-sm mx-4 sm:max-w-lg flex flex-col gap-4 overflow-hidden border border-gray-300 bg-white text-neutral-600">
         <!-- Dialog Header -->
         <div
             class="flex items-center justify-between border-b border-gray-200 bg-gray-50 p-4">
             <h3 id="defaultModalTitle" class="font-semibold tracking-wide text-gray-900">
                 Delete Collection</h3>
             <button x-on:click="deleteModal = false" aria-label="close modal">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                     fill="none" stroke-width="1.4" class="w-5 h-5">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>
         </div>
         <!-- Dialog Body -->
         <div class="px-4 py-8">
             <input type="hidden" name="or_number" :value="deleteId">
             <div class="flex items-start gap-3">
                 <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                 </svg>
                 <div>
                     <p class="text-sm font-medium text-gray-900 mb-1">Delete Collection</p>
                     <p class="text-sm text-gray-600">Are you sure you want to delete this collection? This action cannot be undone.</p>
                 </div>
             </div>
         </div>
         <!-- Dialog Footer -->
         <div
             class="flex flex-col-reverse justify-between gap-2 border-t border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center md:justify-end">
             <button x-on:click="deleteModal = false" type="button"
                 class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">Cancel</button>
             <button x-on:click="deleteModal = false; alert('Collection Deleted Successfully!')" type="submit"
                 class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-600">Delete</button>
         </div>
     </form>
 </div>