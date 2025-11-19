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

<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
  <?php require('../partials/student_sidebar.php'); ?>

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
          <h1 class="text-2xl font-extrabold text-gray-900">Account Ledger</h1>
        </div>
      </div>
    </div>

    <!-- Content Area -->
    <div class="p-6 space-y-6">
      <!-- Student Info Card -->
      <div class="bg-white border border-gray-300 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <p class="text-sm text-gray-600 mb-1">Student Number</p>
            <p class="text-lg font-medium text-gray-900"><?= $student['student_number'] ?></p>
          </div>
          <div>
            <p class="text-sm text-gray-600 mb-1">Name</p>
            <p class="text-lg font-medium text-gray-900"><?= $student['student_name'] ?></p>
          </div>
          <div>
            <p class="text-sm text-gray-600 mb-1">Course</p>
            <p class="text-lg font-medium text-gray-900"><?= $student['course_name'] ?></p>
          </div>
          <div>
            <label class="text-sm text-gray-600 mb-1 block">Semester</label>
            <select name="semester" id="semester_select" onchange="changeSemester(this.value)"
              class="w-full px-3 py-2 border border-gray-300 bg-white focus:ring-neutral-800 focus:border-neutral-800">
              <?php foreach ($semesters as $semester) : ?>
                <option value="<?= $semester['code'] ?>"
                  <?= $semester['code'] === $current_semester ? 'selected' : '' ?>>
                  <?= $semester['code'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-300 p-6">
          <p class="text-sm text-gray-600 mb-1">Total Tuition</p>
          <p class="text-2xl font-extrabold text-gray-900">₱<?= number_format($total_tuition, 2) ?></p>
        </div>
        <div class="bg-white border border-gray-300 p-6">
          <p class="text-sm text-gray-600 mb-1">Total Paid</p>
          <p class="text-2xl font-extrabold text-green-600">₱<?= number_format($total_paid, 2) ?></p>
        </div>
        <div class="bg-white border border-gray-300 p-6">
          <p class="text-sm text-gray-600 mb-1">Balance</p>
          <p class="text-2xl font-extrabold <?= ($total_tuition - $total_paid) > 0 ? 'text-red-600' : 'text-gray-900' ?>">
            ₱<?= number_format($total_tuition - $total_paid, 2) ?>
          </p>
        </div>
      </div>

      <!-- Payments Table -->
      <div class="bg-white border border-gray-300">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
          <h2 class="text-lg font-medium text-gray-900">Payment History</h2>
          <a target="_blank"
            class="inline-flex items-center px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2"
            href="ledger.php?student=<?= $student['student_number'] ?>&semester=<?= $current_semester ?>">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Print Ledger
          </a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">OR Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Payment Method</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Amount</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php if (empty($collections)) : ?>
                <tr>
                  <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-sm">No payments found for this semester.</p>
                  </td>
                </tr>
              <?php else : ?>
                <?php foreach ($collections as $collection) : ?>
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900"><?= $collection['or_number'] ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= date('M d, Y', strtotime($collection['or_date'])) ?></td>
                    <td class="px-6 py-4 text-sm">
                      <span class="inline-flex px-2 py-1 text-xs font-medium <?= $collection['cash'] == 0 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                        <?= $collection['cash'] == 0 ? 'GCash' : 'Cash' ?>
                      </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                      ₱<?= number_format($collection['cash'] == 0 ? floatval($collection['gcash']) : floatval($collection['cash']), 2) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
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