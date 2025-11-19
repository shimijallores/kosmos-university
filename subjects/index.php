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
$stmt = $connection->prepare("select * from semesters ORDER BY start_date DESC");
$stmt->execute();
$semesters = $stmt->fetchAll();
?>

<body class="bg-gray-50" x-data="search(true)">
    <?php require('../partials/admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-2xl font-extrabold text-gray-900">Subject Management</h1>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
            <!-- Search Section -->
            <!-- Search Section -->
            <div class="bg-white border border-gray-200 p-6 mb-6">
                <form method="POST" action="index.php?semester=<?= $_GET['semester'] ?? '1st25-26' ?>"
                    class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-end">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student Number</label>
                            <template x-if="studentNumber">
                                <input type="text" name="student_number"
                                    class="w-full px-3 py-2 border border-gray-300 focus:ring-neutral-800 focus:border-neutral-800"
                                    :value="studentNumber">
                            </template>
                            <template x-if="!studentNumber">
                                <input autofocus type="text" name="student_number"
                                    class="w-full px-3 py-2 border border-gray-300 focus:ring-neutral-800 focus:border-neutral-800"
                                    value="<?= $_SESSION['student_number'] ?? '' ?>">
                            </template>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" x-ref="search_button"
                                class="px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                Search
                            </button>
                            <button type="button" @click="open = true; $nextTick(() => $refs.student_name.focus())"
                                class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                🔍
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (!empty($_SESSION['student_number']) && $student) : ?>
                <!-- Student Info Card -->
                <div class="bg-white border border-gray-200 p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" readonly
                                class="w-full px-3 py-2 border border-gray-300 bg-gray-50 text-gray-700"
                                value="<?= htmlspecialchars($student['student_name'] ?? '') ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                            <input type="text" readonly
                                class="w-full px-3 py-2 border border-gray-300 bg-gray-50 text-gray-700"
                                value="<?= htmlspecialchars($student['course_name'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700">Semester</label>
                        <select @change="window.location.href = `index.php?semester=${$event.target.value}`"
                            class="px-3 py-2 border border-gray-300 bg-white focus:ring-neutral-800 focus:border-neutral-800">
                            <?php foreach ($semesters as $semester) : ?>
                                <option value="<?= $semester['code'] ?>"
                                    <?= $semester['code'] === ($_GET['semester'] ?? '1st25-26') ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($semester['code']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Student Subjects Table -->
                <div class="bg-white border border-gray-200 mb-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs uppercase bg-gray-50 text-gray-700 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-medium">#</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Code</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Description</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Days</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Time</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Room</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Teacher</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Price/Unit</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Units</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($student['subjects'] as $key => $subject) : ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900"><?= $key + 1 ?></td>
                                        <td class="px-6 py-4 text-gray-900"><?= htmlspecialchars($subject['code'] ?? '') ?></td>
                                        <td class="px-6 py-4 text-gray-900"><?= htmlspecialchars(substr($subject['description'] ?? '', 0, 50)) . '...' ?></td>
                                        <td class="px-6 py-4 text-gray-900"><?= htmlspecialchars($subject['days'] ?? '') ?></td>
                                        <td class="px-6 py-4 text-gray-900"><?= htmlspecialchars($subject['time'] ?? '') ?></td>
                                        <td class="px-6 py-4 text-gray-900"><?= htmlspecialchars($subject['room_name'] ?? '') ?></td>
                                        <td class="px-6 py-4 text-gray-900"><?= htmlspecialchars($subject['teacher_name'] ?? '') ?></td>
                                        <td class="px-6 py-4 text-gray-900"><?= htmlspecialchars($subject['price_unit'] ?? '') ?></td>
                                        <td class="px-6 py-4 text-gray-900"><?= htmlspecialchars($subject['units'] ?? '') ?></td>
                                        <td class="px-6 py-4">
                                            <button @click='deleteModal = true; deleteId = <?= $subject["id"] ?>'
                                                class='px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium border border-red-600 focus:ring-2 focus:ring-red-600 focus:ring-offset-2'>
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add Subject Form -->
                <div class="bg-white border border-gray-200 p-6 mb-6">
                    <form method="POST" action="create.php" class="space-y-4">
                        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                        <input type="hidden" name="semester_code" value="<?= $_GET['semester'] ?? '1st25-26' ?>">

                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-end">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Subject</label>
                                <select name="subject"
                                    class="w-full px-3 py-2 border border-gray-300 bg-white focus:ring-neutral-800 focus:border-neutral-800">
                                    <?php foreach ($subjects as $subject): ?>
                                        <option value="<?= $subject['id'] ?>">
                                            <?= htmlspecialchars("{$subject['code']} - {$subject['days']} - {$subject['time']} - {$subject['room_name']} - {$subject['teacher_name']} - {$subject['price_unit']} - {$subject['units']}") ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2 whitespace-nowrap">
                                Add Subject
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a target="_blank" href="print.php?semester=<?= $_GET['semester'] ?? '1st25-26' ?>"
                        class="px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2 text-center">
                        Print
                    </a>
                    <a href="/index.php"
                        class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2 text-center">
                        Close
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search Modal -->
    <?php require('search.php'); ?>

    <!-- Delete Modal -->
    <?php require('delete.php'); ?>

</body>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('search', () => ({
            open: false,
            message: [],
            sidebarOpen: true,
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