<?php
require('../functions.php');
require('../partials/head.php');

# Auth Barrier
if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}

# Fetch semesters
$stmt = $connection->prepare("
select * from semesters
");

$stmt->execute();

$semesters = $stmt->fetchAll();

# Current Day
$today = date('Y-m-d');
?>

<body x-data="search()" class="flex justify-content flex-col items-center">
    <!-- Search Modal -->
    <?= require('search.php') ?>

    <a href="/index.php"
        class="bg-blue-500 text-center mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Back</a>
    <form method="POST" action="store.php"
        class="w-full md:max-w-3/4 mx-auto gap-y-4 mt-6 px-4 flex flex-col gap-2 items-start sm:items-left">
        <p class="font-bold self-end">Current User: <?= ucfirst($_SESSION['user']['name'] ?? 'N/A') ?></p>
        <p class="text-2xl font-bold w-full">Date
            <input type="date" name="date" value="<?= $today; ?>"
                class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
        </p>
        <p class="text-2xl font-bold ">OR #
            <input autofocus type="text" name="or_number"
                class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto" :value="fetchedStudent[0]">
        </p>
        <div class="flex gap-x-4 items-center">
            <p class="text-2xl font-bold ">Student #
                <input type="text" name="student_number"
                    class="border font-medium border-black rounded-sm px-2 w-3/4 sm:w-auto" :value="studentNumber">
                <button type="button" @click="searchOpen = true" x-ref="search_button"
                    class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm">
                    🔎</button>
            </p>
        </div>
        <div class="flex gap-x-4 w-full">
            <p class="text-2xl font-bold w-full">Semester
                <select name="semester" class="border font-medium border-black rounded-sm px-2 w-3/4 sm:w-auto">
                    <?php foreach ($semesters as $semester): ?>
                    <option value="<?= $semester['code'] ?>"
                        @click="currentSem = $el.value; fetchORNumber(studentNumber, currentSem)">
                        <?= $semester['code'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <a :href="'ledger.php?student=' + studentNumber + '&semester=' + currentSem" target="_blank"
                    :class="studentNumber.length < 1 ? 'hidden' : ''"
                    class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm">Ledger
                </a>
            </p>
        </div>
        <p class="text-2xl font-bold ">Cash
            <input type="number" name="cash" :value="fetchedStudent[1]"
                class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
        </p>
        <span x-show="fetchedStudent[1]" x-text="'Remaining Balance:' + fetchedStudent[1] "></span>
        <div class="flex gap-x-4">
            <p class="text-2xl font-bold ">Gcash
                <input type="number" name="gcash"
                    class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
            </p>
            <p class="text-2xl font-bold ">Reference #
                <input type="number" name="gcash_refno"
                    class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
            </p>
        </div>
        <button type="submit" class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm">
            Submit Collection</button>
    </form>

</body>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('search', () => ({
        searchOpen: false,
        message: [],
        studentNumber: '',
        currentSem: '1st25-26',
        fetchedStudent: null,

        fetchStudent: async function(name) {
            let response = await fetch(`api.php?name=${name}`);

            this.message = await response.json();
        },

        fetchORNumber: async function(studentNumber, semester) {
            let response = await fetch(
                `or_api.php?studentNumber=${studentNumber}&semester=${semester}`);

            this.fetchedStudent = await response.json();
        }
    }))
})
</script>

<?php
require('../partials/footer.php')
?>