<?php
require('../functions.php');
require('../partials/head.php');

# Auth Barrier
if (empty($_SESSION['user'])) {
  header('location: /login.php');
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
  header('location: /login.php');
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

<body class="flex justify-content flex-col items-center">
    <div class="w-full md:max-w-3/4 mx-auto px-4">
        <button class="mt-4">
            <a href="index.php" class="bg-blue-500 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Back
                to Menu</a>
        </button>

        <h1 class="text-3xl font-bold mt-6 text-center">My Grades</h1>

        <!-- Student Info -->
        <div class="flex flex-col gap-4 mb-6 mt-6">
            <h1 class="text-2xl font-bold">Student Number: <span
                    class="font-normal"><?= $student['student_number'] ?></span></h1>
            <h1 class="text-2xl font-bold">Name: <span class="font-normal"><?= $student['student_name'] ?></span></h1>
            <h1 class="text-2xl font-bold">Course: <span class="font-normal"><?= $student['course_name'] ?></span></h1>
            <h1 class="text-2xl font-bold">GPA: <span class="font-normal"><?= $gpa ?></span></h1>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:justify-end w-full">
                <h1 class="text-lg sm:text-xl">Semester</h1>
                <select name="semester" id="semester_select" onchange="changeSemester(this.value)"
                    class="py-1 px-2 border border-black w-full sm:w-auto">
                    <?php foreach ($semesters as $semester) : ?>
                    <option value="<?= $semester['code'] ?>"
                        <?= $semester['code'] === $current_semester ? 'selected' : '' ?>>
                        <?= $semester['code'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Student Subjects table -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="px-2 py-2 text-left min-w-[150px]">Subject Code</th>
                        <th class="px-2 py-2 text-left min-w-[300px]">Description</th>
                        <th class="px-2 py-2 text-left min-w-[100px]">Units</th>
                        <th class="px-2 py-2 text-left min-w-[100px]">Midterm</th>
                        <th class="px-2 py-2 text-left min-w-[100px]">Final</th>
                        <th class="px-2 py-2 text-left min-w-[100px]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($student_subjects)) : ?>
                    <tr>
                        <td colspan="6" class="px-2 py-8 text-center text-gray-500">
                            No subjects enrolled for this semester.
                        </td>
                    </tr>
                    <?php else : ?>
                    <?php foreach ($student_subjects as $key => $subject) : ?>
                    <tr class="hover:bg-gray-100">
                        <td class="px-2 py-2"><?= ($key + 1) . ". " . $subject['code'] ?? '' ?></td>
                        <td class="px-2 py-2"><?= substr($subject['description'] ?? '', 0, 100) . '...' ?></td>
                        <td class="px-2 py-2 text-center"><?= $subject['units'] ?? '' ?></td>
                        <td class="px-2 py-2 text-center">
                            <?= $subject['midterm_grade'] != '0.00' ? number_format((float)$subject['midterm_grade'], 2) : '-' ?>
                        </td>
                        <td class="px-2 py-2 text-center">
                            <?= $subject['final_course_grade'] != '0.00' ? number_format((float)$subject['final_course_grade'], 2) : '-' ?>
                        </td>
                        <td class="px-2 py-2 text-center">
                            <?php
                  $final_grade = (float)$subject['final_course_grade'];
                  if ($final_grade >= 1.0 && $final_grade <= 3.0) {
                    echo '<span class="text-green-600 font-bold">Passed</span>';
                  } elseif ($final_grade > 3.0) {
                    echo '<span class="text-red-600 font-bold">Failed</span>';
                  } else {
                    echo '<span class="text-gray-500">Pending</span>';
                  }
                  ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 items-center justify-center md:justify-start mt-6">
            <a target="_blank"
                class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded w-full sm:w-auto"
                href="../grades/print.php?semester=<?= $current_semester ?>&student=<?= $student['student_number'] ?>">Print
                Grades</a>
        </div>
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