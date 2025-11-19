 <div x-cloak x-show="searchOpen" x-transition.opacity.duration.200ms x-on:keydown.esc.window="searchOpen = false"
     x-on:click.self="searchOpen = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 p-4" role="dialog"
     aria-modal="true" aria-labelledby="defaultModalTitle">
     <!-- Modal Dialog -->
     <form action="index.php?semester=<?= $_GET['semester'] ?>" method="GET" x-show="searchOpen"
         x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
         x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
         class="w-full max-w-sm mx-4 sm:max-w-lg flex flex-col gap-4 overflow-hidden border border-gray-300 bg-white text-neutral-600"
         @submit.prevent="if ($refs.student_name.value.trim() === '') { return; }">
         <!-- Dialog Header -->
         <div
             class="flex items-center justify-between border-b border-gray-200 bg-gray-50 p-4">
             <h3 id="defaultModalTitle" class="font-semibold tracking-wide text-gray-900">
                 Search Student</h3>
             <button x-on:click="searchOpen = false" aria-label="close modal">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                     fill="none" stroke-width="1.4" class="w-5 h-5">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>
         </div>
         <!-- Dialog Body -->
         <div class="px-4 py-8">
             <label for="student_name" class="block text-sm font-medium text-gray-700 mb-2">Name:</label>
             <input x-ref="student_name" @focus.once="fetchStudent($el.value)"
                 @input.debounce.750="fetchStudent($el.value)" type="text" id="student_name"
                 class="border w-full border-gray-300 px-3 py-2 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800" name="student_name">

             <template x-if="message.length">
                 <ol class="flex flex-col mt-4 gap-y-2 justify-start w-full">
                     <template x-for="(student, index) in message" :key="student.student_id">
                         <button type="button"
                             @click="studentNumber = student.student_number; searchOpen = false; $nextTick(() => $refs.search_button.click())"
                             class="p-2 border border-gray-300 bg-white hover:bg-gray-50 text-left cursor-pointer">
                             <li x-text="student.name"></li>
                         </button>
                     </template>
                 </ol>
             </template>

         </div>
         <!-- Dialog Footer -->
         <div
             class="flex flex-col-reverse justify-between gap-2 border-t border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center md:justify-end">
         </div>
     </form>
 </div>