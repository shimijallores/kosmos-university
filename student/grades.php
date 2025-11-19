<?php
require('../functions.php');
require('../partials/head.php');

# Auth Barrier
if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

# Get current user info
$user = $_SESSION['user'];

# Get student info
$stmt = $connection->prepare("
select s.student_id, s.student_number, s.name as student_name, c.code as course_name
from students s
left join courses c on s.course_id = c.course_id
where s.student_number = ?;
");

$stmt->execute([$user['name']]);
$student = $stmt->fetch();

if (!$student) {
    header('location: /menu.php');
    exit();
}

# Fetch semesters
$stmt = $connection->prepare("select * from semesters order by id");
$stmt->execute();
$semesters = $stmt->fetchAll();

# Get current semester or default to first available semester
$current_semester = $_GET['semester'] ?? ($semesters[0]['code'] ?? '1st25-26');

# Get student subjects for current semester
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

$stmt->execute([$user['name'], $current_semester]);
$student_subjects = $stmt->fetchAll();

# Calculate GPA
$total_points = 0;
$total_units = 0;
foreach ($student_subjects as $subject) {
    if ($subject['final_course_grade'] > 0) {
        $total_points += $subject['final_course_grade'] * $subject['units'];
        $total_units += $subject['units'];
    }
}
$gpa = $total_units > 0 ? number_format($total_points / $total_units, 2) : '0.00';

?>

<body class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: false }">
    <?php require('../partials/student_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <main class="pt-16 lg:pt-0">
            <div class="py-8 px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900">My Grades</h1>
                    <p class="mt-1 text-gray-600">View your academic performance and grades</p>
                </div>

                <!-- Student Info Card -->
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Student Number</p>
                            <p class="text-lg font-semibold text-gray-900"><?= $student['student_number'] ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Name</p>
                            <p class="text-lg font-semibold text-gray-900"><?= $student['student_name'] ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Course</p>
                            <p class="text-lg font-semibold text-gray-900"><?= $student['course_name'] ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">GPA</p>
                            <p class="text-lg font-semibold text-gray-900"><?= $gpa ?></p>
                        </div>
                    </div>
                </div>

                <!-- Semester Filter & Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <label for="semester_select" class="text-sm font-medium text-gray-700">Semester:</label>
                        <select name="semester" id="semester_select" onchange="changeSemester(this.value)"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800 bg-white">
                            <?php foreach ($semesters as $semester) : ?>
                                <option value="<?= $semester['code'] ?>"
                                    <?= $semester['code'] === $current_semester ? 'selected' : '' ?>>
                                    <?= $semester['code'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <a target="_blank"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-neutral-800 hover:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-800 transition-colors"
                        href="../grades/print.php?semester=<?= $current_semester ?>&student=<?= $student['student_number'] ?>">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print Grades
                    </a>
                </div>

                <!-- Grades Table -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Midterm</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Final</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($student_subjects)) : ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500">No subjects enrolled for this semester.</p>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($student_subjects as $key => $subject) : ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <span class="text-xs text-gray-500 mr-2"><?= $key + 1 ?>.</span>
                                                    <span class="text-sm font-medium text-gray-900"><?= $subject['code'] ?? '' ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900"><?= $subject['description'] ?? '' ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-sm text-gray-900"><?= $subject['units'] ?? '' ?></span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-sm font-medium text-gray-900">
                                                    <?= $subject['midterm_grade'] != '0.00' ? number_format((float)$subject['midterm_grade'], 2) : '-' ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-sm font-medium text-gray-900">
                                                    <?= $subject['final_course_grade'] != '0.00' ? number_format((float)$subject['final_course_grade'], 2) : '-' ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <?php
                                                $final_grade = (float)$subject['final_course_grade'];
                                                if ($final_grade >= 1.0 && $final_grade <= 3.0) {
                                                    echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Passed</span>';
                                                } elseif ($final_grade > 3.0) {
                                                    echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>';
                                                } else {
                                                    echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Pending</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

<script>
    function changeSemester(semester) {
        window.location.href = `grades.php?semester=${semester}`;
    }
</script>

<?php
require('../partials/footer.php')
?>