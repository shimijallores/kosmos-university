<?php
require('../functions.php');
require('../partials/head.php');

# Auth Barrier
if (empty($_SESSION['user'])) {
    header('location: /login.php');
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
    select sub.id, sub.code, sub.description, sub.days, sem.code as semester_code, sub.time, r.name as room_name, t.name as teacher_name, sub.price_unit, sub.units, ss.midterm_grade, ss.final_course_grade
    from students s
    join student_subjects ss on ss.student_id = s.student_id 
    join subjects sub on ss.subject_id = sub.id
    join semesters sem on ss.semester_id = sem.id
    join rooms r on r.id = sub.room_id
    join teachers t on t.id = sub.teacher_id
    where s.student_number = ? and sem.code = ?;
    ");

    $stmt->execute([$_SESSION['student_number'], $_GET['semester']]);

    $student_subjects = $stmt->fetchAll();

    $subjects;
    if ($student) {
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
    <!-- Add Grade Modal -->
    <?php require('create.php'); ?>

    <button class="bg-blue-500 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="/index.php">Back to Menu</a>
    </button>
    <form method="POST" action="index.php?semester=<?= $_GET['semester'] ?>" class="w-3/4 flex gap-x-2 items-center">
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
        <h1 class="text-3xl font-bold mt-6">Semester: <input type="text"
                class="border font-medium border-black rounded-sm px-2" value="<?= $_GET['semester'] ?>" readonly>
        </h1>

        <div class="flex items-center w-full justify-end gap-x-3">
            <h1 class="text-xl">Semester</h1>
            <select name="sort" id="sort" @change="window.location.href = `index.php?semester=${$event.target.value}`"
                class="border border-black py-1 px-2">
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
    <br class=" w-3/4 border border-black my-4">
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
                    Midterm Grades
                </th>
                <th scope="col" class="px-6 py-3">
                    Final Course Grade
                </th>
                <th scope="col" class="px-6 py-3">
                    Actions
                </th>
            </tr>

        </thead>
        <tbody>
            <?php foreach ($student_subjects as $key => $subject) : ?>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= ($key + 1) . ". " . $subject['code'] ?? '' ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= substr($subject['description'] ?? '', 0, 100) . '...'  ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $subject['midterm_grade'] ?? '' ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $subject['final_course_grade'] ?? '' ?></th>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <button @click="open = true"
                        class="bg-neutral-800 mr-2 hover:bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded">
                        Input Grades
                    </button>
                </th>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br class="w-3/4 border border-black my-4">

    <div class="w-3/4 flex gap-x-2 items-center">
        <button type="submit" class="bg-neutral-900 mt-6 cursor-pointer text-white font-bold py-2 px-4 rounded"><a
                target="_blank" href="print.php?semester=<?= $_GET['semester'] ?>">Print</a></button>
        <button type="submit" class="bg-neutral-900 mt-6 cursor-pointer text-white font-bold py-2 px-4 rounded"><a
                href="/index.php?semester<?= $_GET['semester'] ?>">Close</a></button>
    </div>

    <?php endif; ?>
</body>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('search', () => ({
        open: false,
        message: [],
        studentNumber: null,

        toggle() {
            this.open = !this.open
        },
    }))
})
</script>

<?php
require('../partials/footer.php')
?>