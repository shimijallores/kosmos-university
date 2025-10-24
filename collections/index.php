<?php
require('../functions.php');
require('../partials/head.php');

# Auth Barrier
if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}
?>

<body x-data="" class="flex justify-content flex-col items-center">
    <a href="/index.php"
        class="bg-blue-500 text-center mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Back</a>
    <form method="POST" action=""
        class="w-full md:max-w-3/4 mx-auto gap-y-4 mt-6 px-4 flex flex-col gap-2 items-start sm:items-left">
        <p class="text-2xl font-bold ">OR#
            <input type="text" name="or_number"
                class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
        </p>
        <p class="text-2xl font-bold ">Date
            <input type="date" name="date" class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
        </p>
        <p class="text-2xl font-bold ">Student
            <input type="text" name="student_name"
                class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
        </p>

        <div class="flex gap-x-4">
            <p class="text-2xl font-bold ">Semester
                <select name="semester" class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
                    <option value="" selected></option>
                </select>
            </p>
            <button type="button" class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm">
                Show Ledger</button>
        </div>
        <p class="text-2xl font-bold ">Cash
            <input type="text" name="student_name"
                class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
        </p>
        <div class="flex gap-x-4">
            <p class="text-2xl font-bold ">Gcash
                <input type="text" name="student_name"
                    class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
            </p>
            <p class="text-2xl font-bold ">Reference #
                <input type="text" name="student_name"
                    class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
            </p>
        </div>
    </form>

</body>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('search', () => ({
        open: false,
        searchOpen: false,
        message: [],
        studentNumber: null,
        selectedSubjectId: null,
        selectedMidtermGrade: '',
        selectedFinalGrade: '',

        toggle() {
            this.open = !this.open
        },

        openGradeModal(subjectId, midtermGrade, finalGrade) {
            this.selectedSubjectId = subjectId;
            this.selectedMidtermGrade = midtermGrade;
            this.selectedFinalGrade = finalGrade;
            this.open = true;
        },

        fetchStudent: async function(name) {
            let response = await fetch(`api.php?name=${name}`);

            this.message = await response.json();
        }
    }))
})
</script>

<?php
require('../partials/footer.php')
?>