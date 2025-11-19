<?php
require('../functions.php');
require('../partials/head.php');

// Auth Barrier
if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

// Fetch semesters
$stmt = $connection->prepare("select * from semesters");
$stmt->execute();
$semesters = $stmt->fetchAll();

# Current Day
$today = date('Y-m-d');
?>

<body x-data="search()" class="bg-gray-50">
    <!-- Admin Sidebar -->
    <?php require('../partials/admin_sidebar.php') ?>

    <!-- Search Modal -->
    <?php require('search.php') ?>

    <!-- Delete Modal -->
    <?php require('delete.php') ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-2xl font-extrabold text-gray-900">Collections Management</h1>
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Current User:</span> <?= ucfirst($_SESSION['user']['name'] ?? 'N/A') ?>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <!-- Collection Form Card -->
                <div class="bg-white border border-gray-200 p-6">
                    <form method="POST" action="store.php" class="space-y-6">
                        <!-- Date & OR Number Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date -->
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                                <input type="date" name="date" id="date" value="<?= $today; ?>"
                                    class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">
                            </div>

                            <!-- OR Number -->
                            <div>
                                <label for="or_number" class="block text-sm font-medium text-gray-700 mb-2">OR Number</label>
                                <div class="flex gap-2">
                                    <input autofocus type="text" x-ref="or_input" name="or_number" id="or_number"
                                        :readonly="fetchedStudent[0] ? true : false"
                                        class="flex-1 px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800"
                                        :class="fetchedStudent[0] ? 'bg-gray-50' : ''" :value="fetchedStudent[0]">
                                    <button type="button" @click="findORNumber()" :class="studentNumber ? 'hidden' : ''"
                                        class="px-4 py-2 text-sm font-medium text-white bg-neutral-800 hover:bg-neutral-900 focus:ring-2 focus:ring-neutral-800">
                                        Search</button>
                                </div>
                            </div>
                        </div>

                        <!-- Student Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                            <div class="flex gap-2">
                                <input type="hidden" name="student_number" :value="studentNumber">
                                <input type="text" name="student_name" readonly
                                    class="flex-1 px-3 py-2 border border-gray-300 bg-gray-50"
                                    :value="studentName" placeholder="Search for a student...">
                                <button type="button" @click="searchOpen = true" x-ref="search_button"
                                    class="px-4 py-2 text-sm font-medium text-white bg-neutral-800 hover:bg-neutral-900 focus:ring-2 focus:ring-neutral-800">
                                    Search</button>
                                <button type="button" @click="clearInputs()"
                                    class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">
                                    Clear</button>
                            </div>
                        </div>

                        <!-- Semester & Ledger -->
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                            <div class="flex gap-2">
                                <select name="semester" id="semester" x-model="currentSem"
                                    class="flex-1 px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">
                                    <?php foreach ($semesters as $semester): ?>
                                        <option value="<?= $semester['code'] ?>"
                                            @click="currentSem = $el.value; fetchORNumber(studentNumber, currentSem)">
                                            <?= $semester['code'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <a :href="'ledger.php?student=' + studentNumber + '&semester=' + currentSem" target="_blank"
                                    :class="studentNumber.length < 1 ? 'hidden' : ''"
                                    class="px-4 py-2 text-sm font-medium text-white bg-neutral-800 hover:bg-neutral-900 focus:ring-2 focus:ring-neutral-800">
                                    View Ledger
                                </a>
                            </div>
                        </div>

                        <!-- Balance Alert -->
                        <div x-show="fetchedStudent[1]" class="p-4 border border-red-200 bg-red-50">
                            <p class="text-sm font-medium text-red-800">
                                Remaining Balance: ₱<span x-text="fetchedStudent[1]"></span>
                            </p>
                        </div>

                        <!-- Payment Methods -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Cash -->
                            <div>
                                <label for="cash" class="block text-sm font-medium text-gray-700 mb-2">Cash Amount</label>
                                <input type="number" name="cash" id="cash" :value="cash" step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">
                            </div>

                            <!-- GCash -->
                            <div>
                                <label for="gcash" class="block text-sm font-medium text-gray-700 mb-2">GCash Amount</label>
                                <input type="number" name="gcash" id="gcash" :value="gcash" step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">
                            </div>
                        </div>

                        <!-- GCash Reference -->
                        <div>
                            <label for="gcash_refno" class="block text-sm font-medium text-gray-700 mb-2">GCash Reference Number</label>
                            <input type="text" name="gcash_refno" id="gcash_refno" :value="gcashRef"
                                class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-4 border-t border-gray-200">
                            <button type="button" x-show="deleteOpen" @click="deleteModal = true; deleteId = fetchedStudent[0]"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-600">
                                Delete Collection</button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-neutral-800 hover:bg-neutral-900 focus:ring-2 focus:ring-neutral-800"
                                x-text="fetchedStudent[0] && deleteOpen ? 'Update Collection' : 'Submit Collection'">
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Collection History Table -->
                <div x-show="studentNumber" class="mt-6 bg-white border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Collection History</h2>
                    <div class="overflow-x-auto">
                        <template x-if="collectionHistory.length > 0">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-900">OR Number</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-900">Date</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-900">Semester</th>
                                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-900">Cash</th>
                                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-900">GCash</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-900">GCash Ref</th>
                                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-900">Total</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-900">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <template x-for="collection in collectionHistory" :key="collection.or_number">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900" x-text="collection.or_number"></td>
                                            <td class="px-4 py-3 text-sm text-gray-600" x-text="new Date(collection.or_date).toLocaleDateString()"></td>
                                            <td class="px-4 py-3 text-sm text-gray-900" x-text="collection.semester"></td>
                                            <td class="px-4 py-3 text-sm text-gray-900 text-right" x-text="'₱' + parseFloat(collection.cash).toFixed(2)"></td>
                                            <td class="px-4 py-3 text-sm text-gray-900 text-right" x-text="'₱' + parseFloat(collection.gcash).toFixed(2)"></td>
                                            <td class="px-4 py-3 text-sm text-gray-600" x-text="collection.gcash_refno || '-'"></td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right" x-text="'₱' + parseFloat(collection.total).toFixed(2)"></td>
                                            <td class="px-4 py-3 text-sm text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button type="button" @click="editCollection(collection)"
                                                        class="px-3 py-1 text-xs font-medium text-white bg-neutral-800 hover:bg-neutral-900 focus:ring-2 focus:ring-neutral-800">
                                                        Edit
                                                    </button>
                                                    <button type="button" @click="deleteModal = true; deleteId = collection.or_number"
                                                        class="px-3 py-1 text-xs font-medium text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-600">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>
                        <template x-if="collectionHistory.length === 0">
                            <div class="text-center py-8 text-gray-500">
                                <p class="text-sm">No collection history found for this student.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('search', () => ({
            sidebarOpen: false,
            searchOpen: false,
            deleteOpen: false,
            deleteModal: false,
            deleteId: null,
            message: [],
            studentNumber: '<?= addslashes($_SESSION['last_collection']['student_number'] ?? '') ?>',
            studentName: '<?= addslashes($_SESSION['last_collection']['student_name'] ?? '') ?>',
            currentSem: '<?= addslashes($_SESSION['last_collection']['semester'] ?? '1st25-26') ?>',
            fetchedStudent: ['<?= $_SESSION['last_collection']['new_or'] ?? '' ?>', '<?= $_SESSION['last_collection']['balance'] ?? 0 ?>'],
            collectionData: [],
            collectionHistory: [],
            cash: '',
            gcash: '',
            gcashRef: '',

            init() {
                if (this.studentNumber) {
                    this.fetchStudent(this.studentNumber);
                    this.fetchORNumber(this.studentNumber, this.currentSem);
                }
            },

            fetchStudent: async function(name) {
                let response = await fetch(`api.php?name=${name}`);

                this.message = await response.json();
            },

            fetchORNumber: async function(studentNumber, semester) {
                let response = await fetch(
                    `or_api.php?studentNumber=${studentNumber}&semester=${semester}`);

                const data = await response.json();

                this.fetchedStudent = [data[0], data[1]];
                this.collectionHistory = data[2] || [];
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

                this.deleteOpen = true;
            },

            clearInputs() {
                this.studentNumber = '';
                this.studentName = '';
                this.fetchedStudent = [];
                this.collectionHistory = [];
                this.message = [];
                this.fetchStudent('');

                this.deleteOpen = false;
            },

            editCollection(collection) {
                // Populate form with collection data
                this.fetchedStudent = [collection.or_number, this.fetchedStudent[1]];
                this.cash = collection.cash;
                this.gcash = collection.gcash;
                this.gcashRef = collection.gcash_refno;
                this.currentSem = collection.semester;
                this.deleteOpen = true;

                // Scroll to form
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }))
    })
</script>

<?php
require('../partials/footer.php')
?>