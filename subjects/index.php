<?php
require('../functions.php');
require('../partials/head.php');

# Auth Barrier
if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

# Retain student info when page is reloaded
if (!empty($_POST['student_number'])) {
    $_SESSION['student_number'] = $_POST['student_number'];
}

# Search Feature
$student;
if (isset($_SESSION['student_number'])) {
    $stmt = $connection->prepare("
    select s.student_id, s.student_number, s.name as student_name, c.code as course_name 
    from students s 
    left join courses c on s.course_id = c.course_id
    where s.student_number = ?;
    ");

    $stmt->execute([$_SESSION['student_number']]);

    $student = $stmt->fetch();

    # Get student per sem
    $stmt = $connection->prepare("
    select sub.id, sub.code, sub.description, sub.days, sem.code as semester_code, sub.time, r.name as room_name, t.name as teacher_name, sub.price_unit, sub.units
    from students s
    join student_subjects ss on ss.student_id = s.student_id 
    join subjects sub on ss.subject_id = sub.id
    join semesters sem on ss.semester_id = sem.id
    join rooms r on r.id = sub.room_id
    join teachers t on t.id = sub.teacher_id
    where s.student_number = ? and sem.code = ?;
    ");

    $stmt->execute([$_SESSION['student_number'], $_GET['semester'] ?? '1st25-26']);

    $student_subjects = $stmt->fetchAll();

    $subjects;
    if ($student) {
        $student['subjects'] = $student_subjects;

        # Get all subjects
        $stmt = $connection->prepare("
            select sub.id, sub.code, sub.description, sub.days, sub.time, r.name as room_name, t.name as teacher_name, sub.price_unit, sub.units
            from subjects sub
            join rooms r on r.id = sub.room_id
            join teachers t on t.id = sub.teacher_id
        ");

        $stmt->execute();

        $subjects = $stmt->fetchAll();

        foreach ($student['subjects'] as $key => $subject) {
            unset($student['subjects'][$key]['semester_code']);;
        }

        $string_subjects = array_map('serialize', $subjects);
        $string_student_subjects = array_map('serialize', $student['subjects']);

        $subjects_diff = array_diff($string_subjects, $string_student_subjects);

        $subjects = array_map('unserialize', $subjects_diff);

        $_SESSION['student'] = $student;
    }
}

# Fetch semesters
$stmt = $connection->prepare("
select * from semesters
");

$stmt->execute();

$semesters = $stmt->fetchAll();
?>

<body x-data="search(true)" class="flex justify-content flex-col items-center">
    <!-- Search Modal -->
    <?php require('search.php'); ?>

    <!-- Delete Modal -->
    <?php require('delete.php') ?>

    <a href="/index.php"
        class="bg-blue-500 text-center mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Back</a>
    <form method="POST" action="index.php?semester=<?= $_GET['semester'] ?? '1st25-26' ?>"
        class="w-full md:max-w-3/4 mx-auto px-4 flex flex-col sm:flex-row gap-2 items-start sm:items-center">
        <template x-if="studentNumber">
            <h1 class="text-2xl font-bold mt-6">Input# <input type="text" name="student_number"
                    class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto" placeholder=""
                    :value="studentNumber"></h1>
        </template>
        <template x-if="!studentNumber">
            <h1 class="text-2xl font-bold mt-6">Input# <input autofocus type="text" name="student_number"
                    class="border font-medium border-black rounded-sm px-2 w-full sm:w-auto" placeholder=""
                    value="<?= $_SESSION['student_number'] ?? '' ?>"></h1>
        </template>

        <div class="flex gap-2 mt-4 sm:mt-6">
            <button type="submit" x-ref="search_button"
                class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm">Search</button>
            <button type="button" @click="open = true; $nextTick(() => $refs.student_name.focus())"
                class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm">🔍</button>
        </div>
    </form>

    <?php if (!empty($_SESSION['student_number']) && $student) : ?>
        <div class="w-full md:max-w-3/4 mx-auto px-4 flex flex-col gap-4 mb-6">
            <h1 class="text-2xl font-bold mt-6">Name: <input type="text"
                    class="border font-medium border-black rounded-sm px-2 w-full md:w-auto"
                    value="<?= $student['student_name'] ?? '' ?>" readonly>
            </h1>
            <h1 class="text-2xl font-bold">Course: <input type="text"
                    class="border font-medium border-black rounded-sm px-2 w-full md:w-auto"
                    value="<?= $student['course_name'] ?? '' ?>" readonly>
            </h1>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:justify-end w-full">
                <h1 class="text-lg sm:text-xl">Semester</h1>
                <select name="sort" id="sort" @change="window.location.href = `index.php?semester=${$event.target.value}`"
                    class="border border-black py-1 px-2 w-full sm:w-auto">
                    <?php foreach ($semesters as $semester) : ?>
                        <option value="<?= $semester['code'] ?>"
                            <?= $semester['code'] === $_GET['semester'] ? 'selected' : '' ?>>
                            <?= $semester['code'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>


    <!-- Student Subject table -->
    <?php if (!empty($_SESSION['student_number']) && $student) : ?>
        <br class=" w-full mx-auto border border-black my-4">
        <div class="w-full flex md:justify-center min-h-50 overflow-x-auto">
            <table
                class="w-full min-w-[500px] max-w-3/4 text-xs sm:text-sm border border-blue-500 text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-white uppercase bg-blue-500">
                    <tr>
                        <th scope="col" class="px-2 sm:px-6 py-4 sm:py-3">
                            Subject Code
                        </th>
                        <th scope="col" class="px-2 sm:px-6 py-4 sm:py-3">
                            Description
                        </th>
                        <th scope="col" class="px-2 sm:px-6 py-4 sm:py-3">
                            Days
                        </th>
                        <th scope="col" class="px-2 sm:px-6 py-4 sm:py-3">
                            Time
                        </th>
                        <th scope="col" class="px-2 sm:px-6 py-4 sm:py-3">
                            Room
                        </th>
                        <th scope="col" class="px-2 sm:px-6 py-4 sm:py-3">
                            Teacher
                        </th>
                        <th scope="col" class="px-2 sm:px-6 py-4 sm:py-3">
                            Price Per Unit
                        </th>
                        <th scope="col" class="px-2 sm:px-6 py-4 sm:py-3">
                            Units
                        </th>
                        <th scope="col" class="px-2 sm:px-6 py-4 sm:py-3">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($student['subjects'] as $key => $subject) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row"
                                class="px-2 sm:px-6 py-6 sm:py-4 font-medium text-gray-900 dark:text-white break-words">
                                <?= ($key + 1) . ". " . $subject['code'] ?? '' ?></th>
                            <th scope="row"
                                class="px-2 sm:px-6 py-6 sm:py-4 font-medium text-gray-900 dark:text-white break-words">
                                <?= substr($subject['description'] ?? '', 0, 100) . '...'  ?></th>
                            <th scope="row"
                                class="px-2 sm:px-6 py-6 sm:py-4 font-medium text-gray-900 dark:text-white break-words">
                                <?= $subject['days'] ?? '' ?></th>
                            <th scope="row"
                                class="px-2 sm:px-6 py-6 sm:py-4 font-medium text-gray-900 dark:text-white break-words">
                                <?= $subject['time'] ?? '' ?></th>
                            <th scope="row"
                                class="px-2 sm:px-6 py-6 sm:py-4 font-medium text-gray-900 dark:text-white break-words">
                                <?= $subject['room_name'] ?? '' ?></th>
                            <th scope="row"
                                class="px-2 sm:px-6 py-6 sm:py-4 font-medium text-gray-900 dark:text-white break-words">
                                <?= $subject['teacher_name'] ?? '' ?></th>
                            <th scope="row"
                                class="px-2 sm:px-6 py-6 sm:py-4 font-medium text-gray-900 dark:text-white break-words">
                                <?= $subject['price_unit'] ?? '' ?></th>
                            <th scope="row"
                                class="px-2 sm:px-6 py-6 sm:py-4 font-medium text-gray-900 dark:text-white break-words">
                                <?= $subject['units'] ?? '' ?></th>
                            <th scope="row" class="px-2 sm:px-6 py-6 sm:py-4">
                                <button @click='deleteModal = true; deleteId = <?= $subject["id"] ?>; console.log(deleteId)'
                                    class='bg-red-500 hover:bg-red-700 text-xs cursor-pointer text-white font-bold py-2 px-3 sm:py-2 sm:px-4 rounded'>
                                    Delete
                                </button>
                            </th>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <br class="w-full max-w-3/4 mx-auto border border-black my-4">

        <form method="POST" action="create.php"
            class="w-full md:max-w-3/4 mx-auto px-4 flex flex-col sm:flex-row gap-4 mt-6 items-start sm:items-center">
            <h1 class="text-2xl font-bold">Subject:</h1>
            <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
            <input type="hidden" name="semester_code" value="<?= $_GET['semester'] ?>">
            <select name="subject" id="subject" name="subject"
                class="font-medium text-sm sm:text-xl border border-black px-2 flex-1 md:max-w-3/4">
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= $subject['id'] ?>">
                        <?= "{$subject['code']} - {$subject['days']} - {$subject['time']} - {$subject['room_name']} - {$subject['teacher_name']} - {$subject['price_unit']} - {$subject['units']}" ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit"
                class="bg-neutral-900 w-full md:w-20 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm whitespace-nowrap">Add</button>
        </form>

        <div class="w-full md:max-w-3/4 mx-auto px-4 flex flex-col sm:flex-row gap-4 md:items-center mt-6">
            <a target="_blank" class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm"
                href="print.php?semester=<?= $_GET['semester'] ?>">Print</a>
            <a class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded text-sm"
                href="/index.php">Close</a>
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