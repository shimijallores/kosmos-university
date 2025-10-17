<?php
require('../functions.php');
require('../partials/head.php');

// Fetch semesters
$stmt = $connection->prepare("
    select * FROM semesters
    WHERE id = (?);
");

$stmt->execute([$_GET['id']]);

$semester = $stmt->fetch();
?>

<body x-data class="flex justify-content flex-col items-center gap-y-6">
    <button class="bg-blue-500 mt-6 w-50 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="index.php">Back to Semesters</a>
    </button>
    <div class="w-3/4 flex justify-between items-center">
        <h1 class="text-3xl font-bold mt-6">Edit <?= $semester['code'] ?> Information</h1>
    </div>

    <!-- Add semester Form -->
    <form action="update.php" method="POST" class="w-3/4 mx-auto">
        <!-- Current semester id -->
        <input type="hidden" name="semester_id" value="<?= $_GET['id'] ?>">

        <!-- Form Input -->
        <div class="relative z-0 w-full mb-5 group flex flex-col gap-y-4">
            <input type="text" name="code" value="<?= $semester['code'] ?>" id="code"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="semester Number" required />
            <input type="text" value="<?= $semester['start_date'] ?>" id="start_date" name="start_date"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="Start Date" required />
            <input type="text" value="<?= $semester['end_date'] ?>" id="end_date" name="end_date"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="End Date" required />

            <div class="flex items-center gap-x-2">
                <input type="checkbox" class="scale-120" name="summer"
                    <?= $semester['summer'] == 'Y' ? 'checked' : '' ?>>
                <label for="" class="text-sm text-gray-900">Summer</label>
            </div>

        </div>

        <button type="submit"
            class="bg-blue-700 hover:bg-blue-800 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
            Edit semester
        </button>
    </form>

</body>

<?php
require('../partials/footer.php')
?>