 <div x-cloak x-show="open"
     x-transition.opacity.duration.200ms
     x-on:keydown.esc.window="open = false"
     x-on:click.self="open = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4"
     role="dialog"
     aria-modal="true"
     aria-labelledby="searchModalTitle">
     <!-- Modal Dialog -->
     <form action="index.php?semester=<?= $_GET['semester'] ?>" method="GET" x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="w-full max-w-lg bg-white border border-gray-300"
         @submit.prevent="if ($refs.student_name.value.trim() === '') { return; }">
         <!-- Dialog Header -->
         <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
             <h3 id="searchModalTitle" class="text-lg font-medium text-gray-900">
                 Search Student
             </h3>
             <button type="button" x-on:click="open = false" aria-label="close modal" class="text-gray-400 hover:text-gray-600">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>
         </div>

         <!-- Dialog Body -->
         <div class="px-6 py-6">
             <label for="student_name" class="block text-sm font-medium text-gray-700 mb-2">Student Name</label>
             <input x-ref="student_name"
                 @input.debounce.750="fetchStudent($el.value)"
                 @focus.once="fetchStudent($el.value)"
                 type="text"
                 id="student_name"
                 class="w-full px-3 py-2 border border-gray-300 focus:ring-neutral-800 focus:border-neutral-800"
                 autofocus
                 name="student_name">

             <template x-if="message.length">
                 <div class="mt-4 max-h-64 overflow-y-auto">
                     <ol class="space-y-2">
                         <template x-for="(student, index) in message" :key="student.student_id">
                             <li>
                                 <button type="button"
                                     @click="studentNumber = student.student_number; open = false; $nextTick(() => $refs.search_button.click())"
                                     class="w-full text-left px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2"
                                     x-text="student.name">
                                 </button>
                             </li>
                         </template>
                     </ol>
                 </div>
             </template>
         </div>

         <!-- Dialog Footer -->
         <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
             <button type="button"
                 x-on:click="open = false"
                 class="w-full px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                 Close
             </button>
         </div>
     </form>
 </div>