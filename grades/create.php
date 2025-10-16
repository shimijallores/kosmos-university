 <div x-cloak x-show="open" x-transition.opacity.duration.200ms x-on:keydown.esc.window="open = false"
     x-on:click.self="open = false"
     class="fixed inset-0 z-30 flex items-center justify-center bg-black/20 p-4 backdrop-blur-md sm:p-8"
     role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
     <!-- Modal Dialog -->
     <form action="update.php" method="POST" x-show="open"
         x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
         x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
         class="w-full max-w-sm mx-4 sm:max-w-lg flex flex-col gap-4 overflow-hidden rounded-sm border border-neutral-300 bg-white text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
         <!-- Hidden Fields -->
         <input type="hidden" name="subject_id" :value="selectedSubjectId">
         <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
         <input type="hidden" name="semester" value="<?= $_GET['semester'] ?>">
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
                 <label for="midterm_grade" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Midterm Grade:</label>
                 <select name="midterm_grade" class="border w-full border-neutral-200 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                     <option value="0.0">Select Grade</option>
                     <option value="1.00" :selected="selectedMidtermGrade == '1.00'">1.00</option>
                     <option value="1.25" :selected="selectedMidtermGrade == '1.25'">1.25</option>
                     <option value="1.50" :selected="selectedMidtermGrade == '1.50'">1.50</option>
                     <option value="1.75" :selected="selectedMidtermGrade == '1.75'">1.75</option>
                     <option value="2.00" :selected="selectedMidtermGrade == '2.00'">2.00</option>
                     <option value="2.25" :selected="selectedMidtermGrade == '2.25'">2.25</option>
                     <option value="2.50" :selected="selectedMidtermGrade == '2.50'">2.50</option>
                     <option value="2.75" :selected="selectedMidtermGrade == '2.75'">2.75</option>
                     <option value="3.00" :selected="selectedMidtermGrade == '3.00'">3.00</option>
                     <option value="4.00" :selected="selectedMidtermGrade == '4.00'">4.00</option>
                     <option value="5.00" :selected="selectedMidtermGrade == '5.00'">5.00</option>
                 </select>
             </div>
             <div>
                 <label for="final_course_grade" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Final Course Grade:</label>
                 <select name="final_course_grade" class="border w-full border-neutral-200 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                     <option value="0.0">Select Grade</option>
                     <option value="1.00" :selected="selectedFinalGrade == '1.00'">1.00</option>
                     <option value="1.25" :selected="selectedFinalGrade == '1.25'">1.25</option>
                     <option value="1.50" :selected="selectedFinalGrade == '1.50'">1.50</option>
                     <option value="1.75" :selected="selectedFinalGrade == '1.75'">1.75</option>
                     <option value="2.00" :selected="selectedFinalGrade == '2.00'">2.00</option>
                     <option value="2.25" :selected="selectedFinalGrade == '2.25'">2.25</option>
                     <option value="2.50" :selected="selectedFinalGrade == '2.50'">2.50</option>
                     <option value="2.75" :selected="selectedFinalGrade == '2.75'">2.75</option>
                     <option value="3.00" :selected="selectedFinalGrade == '3.00'">3.00</option>
                     <option value="4.00" :selected="selectedFinalGrade == '4.00'">4.00</option>
                     <option value="5.00" :selected="selectedFinalGrade == '5.00'">5.00</option>
                 </select>
             </div>
         </div>
         <!-- Dialog Footer -->
         <div
             class="flex flex-col-reverse justify-between gap-2 border-t border-neutral-300 bg-neutral-50/60 p-4 dark:border-neutral-700 dark:bg-neutral-950/20 sm:flex-row sm:items-center md:justify-end">
             <button x-on:click="open = false" type="button"
                 class="whitespace-nowrap rounded-sm px-4 py-2 text-center text-sm font-medium tracking-wide text-neutral-600 transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0 dark:text-neutral-300 dark:focus-visible:outline-white">Cancel</button>
             <button type="submit"
                 class="whitespace-nowrap rounded-sm bg-neutral-800 px-4 py-2 text-center text-sm font-medium tracking-wide text-white transition hover:bg-black focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0">Update Grades</button>
         </div>
     </form>
 </div>