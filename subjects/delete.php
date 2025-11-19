 <div x-cloak x-show="deleteModal"
     x-transition.opacity.duration.200ms
     x-on:keydown.esc.window="deleteModal = false"
     x-on:click.self="deleteModal = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4"
     role="dialog"
     aria-modal="true"
     aria-labelledby="deleteModalTitle">
     <!-- Modal Dialog -->
     <form action="destroy.php" method="POST" x-show="deleteModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="w-full max-w-md bg-white border border-gray-300">
         <!-- Dialog Header -->
         <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
             <h3 id="deleteModalTitle" class="text-lg font-medium text-gray-900">
                 Delete Subject
             </h3>
             <button type="button" x-on:click="deleteModal = false" aria-label="close modal" class="text-gray-400 hover:text-gray-600">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>
         </div>

         <!-- Dialog Body -->
         <div class="px-6 py-6">
             <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
             <input type="hidden" name="subject_id" :value="deleteId">
             <input type="hidden" name="semester_code" value="<?= $_GET['semester'] ?>">
             <div class="flex items-start space-x-3">
                 <div class="flex-shrink-0">
                     <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                     </svg>
                 </div>
                 <div>
                     <h4 class="text-sm font-medium text-gray-900 mb-1">Confirm Deletion</h4>
                     <p class="text-sm text-gray-600">Are you sure you want to delete this subject? This action cannot be undone.</p>
                 </div>
             </div>
         </div>

         <!-- Dialog Footer -->
         <div class="flex items-center justify-end space-x-3 border-t border-gray-200 px-6 py-4 bg-gray-50">
             <button type="button"
                 x-on:click="deleteModal = false"
                 class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                 Cancel
             </button>
             <button type="submit"
                 class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium border border-red-600 focus:ring-2 focus:ring-red-600 focus:ring-offset-2">
                 Delete Subject
             </button>
         </div>
     </form>
 </div>