 <div x-cloak x-show="open" x-transition.opacity.duration.200ms x-on:keydown.esc.window="open = false"
     x-on:click.self="open = false"
     class="fixed inset-0 z-30 flex items-center justify-center bg-black/20 p-4 backdrop-blur-md sm:p-8" role="dialog"
     aria-modal="true" aria-labelledby="defaultModalTitle">
     <!-- Modal Dialog -->
     <form action="index.php?semester=<?= $_GET['semester'] ?>" method="GET" x-show="open"
         class="w-full max-w-sm mx-4 sm:max-w-lg flex flex-col gap-4 overflow-hidden rounded-sm border border-neutral-300 bg-white text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300"
         @submit.prevent="if ($refs.student_name.value.trim() === '') { return; }">
         <!-- Dialog Header -->
         <div
             class="flex items-center justify-between border-b border-neutral-300 bg-neutral-50/60 p-4 dark:border-neutral-700 dark:bg-neutral-950/20">
             <h3 id="defaultModalTitle" class="font-semibold tracking-wide text-neutral-900 dark:text-white">
                 Search Student</h3>
             <button x-on:click="open = false" aria-label="close modal">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                     fill="none" stroke-width="1.4" class="w-5 h-5">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>
         </div>
         <!-- Dialog Body -->
         <div class="px-4 py-8">
             <label for="student_name">Name: </label>
             <input x-ref="student_name" @input.debounce.750="fetchStudent($el.value)"
                 @focus.once="fetchStudent($el.value)" type="text" id="student_name"
                 class="border w-full border-neutral-200 px-2 py-1 rounded" autofocus name="student_name">

             <template x-if="message.length">
                 <ol class="flex flex-col mt-2 gap-y-1 justify-start w-full">
                     <template x-for="(student, index) in message" :key="student.student_id">
                         <button type="button"
                             @click="studentNumber = student.student_number; open = false; $nextTick(() => $refs.search_button.click())"
                             class="p-1 border border-neutral-200 hover:opacity-80 cursor-pointer">
                             <li x-text="student.name"></li>
                         </button>
                     </template>
                 </ol>
             </template>

         </div>
         <!-- Dialog Footer -->
         <div
             class="flex flex-col-reverse justify-between gap-2 border-t border-neutral-300 bg-neutral-50/60 p-4 dark:border-neutral-700 dark:bg-neutral-950/20 sm:flex-row sm:items-center md:justify-end">
         </div>
     </form>
 </div>