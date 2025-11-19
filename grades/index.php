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

    <!-- Search Modal -->
    <?php require('search.php'); ?>

    <a href="/index.php"
        class="bg-blue-500 text-center mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Back</a>
    <form method="POST" action="index.php?semester=<?= $_GET['semester'] ?>"
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
            <button type="button" @click="searchOpen = true; $nextTick(() => $refs.student_name.focus())"
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
                    class="py-1 px-2 border border-black w-full sm:w-auto">
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
        <div class="w-full md:max-w-3/4 mx-auto px-4 overflow-x-auto">
            <table class="w-full border-collapse border ">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="px-2 py-2 text-left min-w-[150px]">Subject Code</th>
                        <th class="px-2 py-2 text-left min-w-[300px]">Description</th>
                        <th class="px-2 py-2 text-left min-w-[100px]">Midterm</th>
                        <th class="px-2 py-2 text-left min-w-[100px]">Final</th>
                        <th class="px-2 py-2 text-left min-w-[100px]">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($student_subjects as $key => $subject) : ?>
                        <tr class="hover:bg-gray-100">
                            <td class="px-2 py-2"><?= ($key + 1) . ". " . $subject['code'] ?? '' ?></td>
                            <td class="px-2 py-2"><?= substr($subject['description'] ?? '', 0, 100) . '...' ?></td>
                            <td class=" px-2 py-2 text-center">
                                <?= $subject['midterm_grade'] != '0.00' ? number_format((float)$subject['midterm_grade'], 2) : '-' ?>
                            </td>
                            <td class=" px-2 py-2 text-center">
                                <?= $subject['final_course_grade'] != '0.00' ? number_format((float)$subject['final_course_grade'], 2) : '-' ?>
                            </td>
                            <td class=" px-2 py-2">
                                <button
                                    @click="openGradeModal(<?= $subject['id'] ?>, '<?= $subject['midterm_grade'] ? number_format((float)$subject['midterm_grade'], 2) : '' ?>', '<?= $subject['final_course_grade'] ? number_format((float)$subject['final_course_grade'], 2) : '' ?>')"
                                    class="bg-neutral-800 hover:bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded w-full sm:w-auto">
                                    Input Grades
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div
            class="w-full md:max-w-3/4 mx-auto px-4 flex flex-col sm:flex-row gap-2 items-center justify-center md:justify-start mt-6">
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