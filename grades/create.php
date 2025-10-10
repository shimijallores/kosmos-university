 <div x-cloak x-show="open" x-transition.opacity.duration.200ms x-on:keydown.esc.window="open = false"
     x-on:click.self="open = false"
     class="fixed inset-0 z-30 flex items-end justify-center bg-black/20 p-4 pb-8 backdrop-blur-md sm:items-center lg:p-8"
     role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
     <!-- Modal Dialog -->
     <form action="store.php" method="POST" x-show="open"
         x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
         x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
         class="flex max-w-lg min-w-1/4 flex-col gap-4 overflow-hidden rounded-sm border border-neutral-300 bg-white text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
         <!-- Dialog Header -->
         <div
             class="flex items-center justify-between border-b border-neutral-300 bg-neutral-50/60 p-4 dark:border-neutral-700 dark:bg-neutral-950/20">
             <h3 id="defaultModalTitle" class="font-semibold tracking-wide text-neutral-900 dark:text-white">
                 <?= $student['student_name'] . ' Grades' ?></h3>
             <button x-on:click="open = false" aria-label="close modal">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                     fill="none" stroke-width="1.4" class="w-5 h-5">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>
         </div>
         <!-- Dialog Body -->
         <div class="px-4 py-8 flex flex-col gap-y-4">
             <div>
                 <label for="student_name">Midterm Grade: </label>
                 <select name="midterm_grade" class="border w-full border-neutral-200 px-2 py-1 rounded">
                     <option value="">null</option>
                     <option value="">1.00</option>
                     <option value="">1.25</option>
                     <option value="">1.50</option>
                     <option value="">1.75</option>
                     <option value="">2.00</option>
                     <option value="">2.25</option>
                     <option value="">2.50</option>
                     <option value="">2.75</option>
                     <option value="">3.00</option>
                     <option value="">4.00</option>
                     <option value="">5.00</option>
                 </select>
             </div>
             <div>
                 <label for="student_name">Final Course Grade: </label>
                 <select name="final_course_grade" class="border w-full border-neutral-200 px-2 py-1 rounded">
                     <option value="">null</option>
                     <option value="">1.00</option>
                     <option value="">1.25</option>
                     <option value="">1.50</option>
                     <option value="">1.75</option>
                     <option value="">2.00</option>
                     <option value="">2.25</option>
                     <option value="">2.50</option>
                     <option value="">2.75</option>
                     <option value="">3.00</option>
                     <option value="">4.00</option>
                     <option value="">5.00</option>
                 </select>
             </div>


             <button type="submit"
                 class="bg-neutral-800 mr-2 hover:bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded">Update</button>
         </div>
         <!-- Dialog Footer -->
         <div
             class="flex flex-col-reverse justify-between gap-2 border-t border-neutral-300 bg-neutral-50/60 p-4 dark:border-neutral-700 dark:bg-neutral-950/20 sm:flex-row sm:items-center md:justify-end">
         </div>
     </form>
 </div>