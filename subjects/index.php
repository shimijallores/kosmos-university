<?php
require('../functions.php');
require('../partials/head.php');


$student;

if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}

if (!empty($_POST['student_number'])) {
    $_SESSION['student_number'] = $_POST['student_number'];
}

if (isset($_SESSION['student_number'])) {
    $stmt = $connection->prepare("
    select s.student_id, s.student_number, s.name as student_name, c.code as course_name 
    from students s 
    left join courses c on s.course_id = c.course_id
    where s.student_number = ?;
    ");

    $stmt->execute([$_SESSION['student_number']]);

    $student = $stmt->fetch();

    $stmt = $connection->prepare("
    select sub.id, sub.code, sub.description, sub.days, sub.time, r.name as room_name, t.name as teacher_name, sub.price_unit, sub.units
    from students s
    join student_subjects ss on ss.student_id = s.student_id 
    join subjects sub on ss.subject_id = sub.id
    join rooms r on r.id = sub.room_id
    join teachers t on t.id = sub.teacher_id
    where s.student_number = ?;
    ");

    $stmt->execute([$_SESSION['student_number']]);

    $student_subjects = $stmt->fetchAll();

    $subjects;
    if ($student) {
        $student['subjects'] = $student_subjects;

        $stmt = $connection->prepare("
            select sub.id, sub.code, sub.description, sub.days, sub.time, r.name as room_name, t.name as teacher_name, sub.price_unit, sub.units
            from subjects sub
            join rooms r on r.id = sub.room_id
            join teachers t on t.id = sub.teacher_id
        ");

        $stmt->execute();

        $subjects = $stmt->fetchAll();

        $string_subjects = array_map('serialize', $subjects);
        $string_student_subjects = array_map('serialize', $student['subjects']);

        $subjects_diff = array_diff($string_subjects, $string_student_subjects);

        $subjects = array_map('unserialize', $subjects_diff);

        $_SESSION['student'] = $student;
    }
}
?>

<body x-data="search(true)" class="flex justify-content flex-col items-center">
    <!-- Search Modal -->
    <?php require('search.php'); ?>

    <!-- Delete Modal -->
    <?php require('delete.php') ?>

    <button class="bg-blue-500 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="/index.php">Back to Menu</a>
    </button>
    <form method="POST" action="index.php" class="w-3/4 flex gap-x-2 items-center">
        <template x-if="studentNumber">
            <h1 class="text-3xl font-bold mt-6">Input# <input type="text" name="student_number"
                    class="border font-medium border-black rounded-sm px-2" placeholder="" :value="studentNumber"></h1>
        </template>
        <template x-if="!studentNumber">
            <h1 class="text-3xl font-bold mt-6">Input# <input type="text" name="student_number"
                    class="border font-medium border-black rounded-sm px-2" placeholder=""
                    value="<?= $_SESSION['student_number'] ?? '' ?>"></h1>
        </template>

        <button type="submit"
            class="bg-neutral-900 mt-6 cursor-pointer text-white font-bold py-2 px-4 rounded">Search</button>
        <button type="button" @click="open = true"
            class="bg-neutral-900 mt-6 cursor-pointer text-white font-bold py-2 px-4 rounded">🔍</button>
    </form>

    <?php if (!empty($_SESSION['student_number']) && $student) : ?>
    <div class="w-3/4 flex gap-x-2 flex-col mb-6">
        <h1 class="text-3xl font-bold mt-6">Name: <input type="text"
                class="border font-medium border-black rounded-sm px-2" value="<?= $student['student_name'] ?? '' ?>"
                readonly>
        </h1>
        <h1 class="text-3xl font-bold mt-6">Course: <input type="text"
                class="border font-medium border-black rounded-sm px-2" value="<?= $student['course_name'] ?? '' ?>"
                readonly>
        </h1>
    </div>
    <?php endif; ?>


    <!-- Student Subject table -->
    <?php if (!empty($_SESSION['student_number']) && $student) : ?>
    <br class="w-3/4 border border-black my-4">
    <table class="w-3/4 text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-white uppercase bg-blue-500 ">

            <tr>
                <th scope="col" class="px-6 py-3">
                    Subject Code
                </th>
                <th scope="col" class="px-6 py-3">
                    Description
                </th>
                <th scope="col" class="px-6 py-3">
                    Days
                </th>
                <th scope="col" class="px-6 py-3">
                    Time
                </th>
                <th scope="col" class="px-6 py-3">
                    Room
                </th>
                <th scope="col" class="px-6 py-3">
                    Teacher
                </th>
                <th scope="col" class="px-6 py-3">
                    Price Per Unit
                </th>
                <th scope="col" class="px-6 py-3">
                    Units
                </th>
                <th scope="col" class="px-6 py-3">
                    Actions
                </th>
            </tr>

        </thead>
        <tbody>
            <?php foreach ($student['subjects'] as $key => $subject) : ?>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= ($key + 1) . ". " . $subject['code'] ?? '' ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= substr($subject['description'] ?? '', 0, 100) . '...'  ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $subject['days'] ?? '' ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $subject['time'] ?? '' ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $subject['room_name'] ?? '' ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $subject['teacher_name'] ?? '' ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $subject['price_unit'] ?? '' ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $subject['units'] ?? '' ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <button @click='deleteModal = true; deleteId = <?= $subject["id"] ?>; console.log(deleteId)'
                        class='bg-red-500 hover:bg-red-700 w-25 cursor-pointer text-white font-bold py-2 px-4 rounded'>
                        Delete
                    </button>
                </th>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br class="w-3/4 border border-black my-4">

    <form method="POST" action="create.php" class="w-3/4 flex gap-x-2 mt-6 text-center">
        <h1 class="text-3xl font-bold mt-6">Subject:</h1>
        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
        <select name="subject" id="subject" name="subject" class="font-medium text-xl border border-black px-2">
            <?php foreach ($subjects as $subject): ?>
            <option value="<?= $subject['id'] ?>">
                <?= "{$subject['code']} - {$subject['days']} - {$subject['time']} - {$subject['room_name']} - {$subject['teacher_name']} - {$subject['price_unit']} - {$subject['units']}" ?>
            </option>
            <?php endforeach; ?>
        </select>

        <button type="submit"
            class="bg-neutral-900 mt-6 cursor-pointer text-white font-bold py-2 px-4 rounded">Add</button>
    </form>

    <div class="w-3/4 flex gap-x-2 items-center">
        <button type="submit" class="bg-neutral-900 mt-6 cursor-pointer text-white font-bold py-2 px-4 rounded"><a
                target="_blank" href="print.php">Print</a></button>
        <button type="submit" class="bg-neutral-900 mt-6 cursor-pointer text-white font-bold py-2 px-4 rounded"><a
                href="/index.php">Close</a></button>
    </div>

    <?php endif; ?>
</body>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('search', () => ({
        open: false,
        message: [],
        studentNumber: null,
        deleteModal: false,
        deleteId: null,

        toggle() {
            this.open = !this.open
        },

        fetchStudent: async function(name) {
            let response = await fetch(`api.php?name=${name}`);

            this.message = await response.json();
        },

    }))
})
</script>

<?php
require('../partials/footer.php')
?>