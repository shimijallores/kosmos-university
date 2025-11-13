<?php
require('../functions.php');
require('../partials/head.php');

// Auth Barrier
if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}

// Fetch semesters
$stmt = $connection->prepare("select * from semesters");
$stmt->execute();
$semesters = $stmt->fetchAll();

# Current Day
$today = date('Y-m-d');
?>

<body x-data="search()" class="flex justify-content flex-col items-center">
    <!-- Search Modal -->
    <?php require('search.php') ?>

    <a href="/index.php"
        class="bg-blue-500 text-center mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Back</a>
    <form method="POST" action="store.php"
        class="w-full md:max-w-3/4 mx-auto gap-y-4 mt-6 px-4 flex flex-col gap-2 items-start sm:items-left">
        <p class="font-bold self-end">Current User: <?= ucfirst($_SESSION['user']['name'] ?? 'N/A') ?></p>
        <p class="text-2xl font-bold w-full">Date
            <input type="date" name="date" value="<?= $today; ?>"
                class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
        </p>
        <!-- OR Number -->
        <p class="text-2xl font-bold ">OR #
            <input autofocus type="text" x-ref="or_input" name="or_number" :readonly="fetchedStudent[0] ? true : false"
                class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto" :class="fetchedStudent[0] ? 'opacity-80' : ''" :value="fetchedStudent[0]">
            <button type="button" @click="findORNumber()" :class="studentNumber ? 'hidden' : ''"
                class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm">
                🔎</button>
        </p>
        <!-- Student Name -->
        <div class="flex gap-x-4 items-center">
            <p class="text-2xl font-bold ">Student
                <input type="hidden" name="student_number"
                    class="border font-medium border-black rounded-sm px-2 w-3/4 sm:w-auto" :value="studentNumber">
                <input type="text" name="student_name" readonly
                    class="border font-medium border-black rounded-sm px-2 w-3/4 sm:w-auto" :value="studentName">
                <button type="button" @click="searchOpen = true" x-ref="search_button"
                    class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm">
                    🔎</button>
                <button type="button" @click="clearInputs()"
                    class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm">
                    Clear</button>
            </p>
        </div>
        <!-- Semester -->
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
        <!-- Payment Methods -->
        <p class="text-2xl font-bold ">Cash
            <input type="number" name="cash" :value="cash"
                class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
        </p>
        <span class="text-red-600" x-show="fetchedStudent[1]" x-text="'Remaining Balance:' + fetchedStudent[1] "></span>
        <div class="flex gap-x-4">
            <p class="text-2xl font-bold ">Gcash
                <input type="number" name="gcash" :value="gcash"
                    class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
            </p>
            <p class="text-2xl font-bold ">Reference #
                <input type="number" name="gcash_refno" :value="gcashRef"
                    class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto">
            </p>
        </div>
        <button type="submit" @click='alert("Collection succesfully submitted!")' class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm">
            Submit Collection</button>
    </form>

</body>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('search', () => ({
            searchOpen: false,
            message: [],
            studentNumber: '',
            studentName: '',
            currentSem: '1st25-26',
            fetchedStudent: [],
            collectionData: [],
            cash: '',
            gcash: '',
            gcashRef: '',

            fetchStudent: async function(name) {
                let response = await fetch(`api.php?name=${name}`);

                this.message = await response.json();
            },

            fetchORNumber: async function(studentNumber, semester) {
                let response = await fetch(
                    `or_api.php?studentNumber=${studentNumber}&semester=${semester}`);

                this.fetchedStudent = await response.json();

                this.cash = this.fetchedStudent[1];
            },

            findORNumber: async function() {
                const orNumber = this.$refs.or_input.value;

                if (!orNumber) {
                    return;
                }

                let response = await fetch(`collection_api.php?or=${orNumber}`);

                this.collectionData = await response.json();

                const collection = this.collectionData[0];
                const student = this.collectionData[1];
                const semester = this.collectionData[2];

                this.studentNumber = student.student_number;
                this.studentName = student.name;
                this.fetchedStudent = [collection.or_number, this.collectionData[3]];
                this.cash = collection.cash;
                this.gcash = collection.gcash;
                this.gcashRef = collection.gcash_refno;
            },

            clearInputs() {
                this.studentNumber = '';
                this.studentName = '';
                this.fetchedStudent = [];
                this.message = [];
                this.fetchStudent('');
            }
        }))
    })
</script>

<?php
require('../partials/footer.php')
?>