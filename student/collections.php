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

# Fetch Collections related to the student for current semester
$stmt = $connection->prepare("
select * from collections where student_id = ? and semester_id = (select id from semesters where code = ?);
");
$stmt->execute([$student['student_id'], $current_semester]);
$collections = $stmt->fetchAll();

# Fetch students total tuition for current semester
$stmt = $connection->prepare("
select * from student_subjects ss join subjects s where ss.student_id = ? and ss.semester_id = (select id from semesters where code = ?) and ss.subject_id = s.id
");
$stmt->execute([$student['student_id'], $current_semester]);
$tuitions = $stmt->fetchAll();

$total_tuition = 0;
foreach ($tuitions as $tuition) {
  $total_tuition += floatval($tuition['price_unit']) * intval($tuition['units']);
}

# Calculate total paid
$total_paid = 0;
foreach ($collections as $collection) {
  $total_paid += $collection['cash'] == 0 ? floatval($collection['gcash']) : floatval($collection['cash']);
}
?>

<body class="flex justify-content flex-col items-center">
  <div class="w-full md:max-w-3/4 mx-auto px-4">
    <button class="mt-4">
      <a href="index.php" class="bg-blue-500 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Back
        to Menu</a>
    </button>

    <h1 class="text-3xl font-bold mt-6 text-center">Account Ledger</h1>

    <!-- Student Info -->
    <div class="flex flex-col gap-4 mb-6 mt-6">
      <h1 class="text-2xl font-bold">Student Number: <span
          class="font-normal"><?= $student['student_number'] ?></span></h1>
      <h1 class="text-2xl font-bold">Name: <span class="font-normal"><?= $student['student_name'] ?></span></h1>
      <h1 class="text-2xl font-bold">Course: <span class="font-normal"><?= $student['course_name'] ?></span></h1>

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

    <!-- Account Ledger Table -->
    <div class="overflow-x-auto">
      <table class="w-full border-collapse border">
        <thead>
          <tr class="bg-blue-500 text-white">
            <th class="px-2 py-2 text-left min-w-[150px]">OR #</th>
            <th class="px-2 py-2 text-left min-w-[150px]">Date</th>
            <th class="px-2 py-2 text-left min-w-[150px]">Payment</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($collections)) : ?>
            <tr>
              <td colspan="3" class="px-2 py-8 text-center text-gray-500">
                No payments found for this semester.
              </td>
            </tr>
          <?php else : ?>
            <?php foreach ($collections as $collection) : ?>
              <tr class="hover:bg-gray-100">
                <td class="px-2 py-2"><?= $collection['or_number'] ?></td>
                <td class="px-2 py-2"><?= date('M d, Y', strtotime($collection['or_date'])) ?></td>
                <td class="px-2 py-2 text-right">
                  ₱<?= number_format($collection['cash'] == 0 ? floatval($collection['gcash']) : floatval($collection['cash']), 2) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
        <tfoot>
          <tr class="bg-gray-100 font-bold">
            <td colspan="2" class="px-2 py-2 text-right">Total Tuition:</td>
            <td class="px-2 py-2 text-right">₱<?= number_format($total_tuition, 2) ?></td>
          </tr>
          <tr class="bg-gray-100 font-bold">
            <td colspan="2" class="px-2 py-2 text-right">Total Paid:</td>
            <td class="px-2 py-2 text-right">₱<?= number_format($total_paid, 2) ?></td>
          </tr>
          <tr class="bg-gray-100 font-bold">
            <td colspan="2" class="px-2 py-2 text-right">Balance:</td>
            <td class="px-2 py-2 text-right">₱<?= number_format($total_tuition - $total_paid, 2) ?></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="flex flex-col sm:flex-row gap-2 items-center justify-center md:justify-start mt-6">
      <a target="_blank"
        class="bg-neutral-900 cursor-pointer text-white font-bold py-2 px-4 rounded w-full sm:w-auto"
        href="ledger.php?student=<?= $student['student_number'] ?>&semester=<?= $current_semester ?>">Print
        Ledger</a>
    </div>
  </div>
</body>

<script>
  function changeSemester(semester) {
    window.location.href = `collections.php?semester=${semester}`;
  }
</script>

<?php
require('../partials/footer.php')
?>