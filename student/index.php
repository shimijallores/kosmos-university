<?php
require('../functions.php');
require('../partials/head.php');
require('../partials/database.php');

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

// Get student_id from session
$student_number = $_SESSION['user']['name'];
$stmt = $connection->prepare("SELECT student_id FROM students WHERE student_number = ?");
$stmt->execute([$student_number]);
$student = $stmt->fetch();
$student_id = $student ? $student['student_id'] : null;

// Initialize dashboard data
$gpa = 0.00;
$enrolled_subjects_count = 0;
$total_balance = 0.00;

if ($student_id) {
    // Calculate GPA
    $gpa_query = "
        SELECT 
            SUM(ss.final_course_grade * s.units) as weighted_sum,
            SUM(s.units) as total_units
        FROM student_subjects ss
        JOIN subjects s ON ss.subject_id = s.id
        WHERE ss.student_id = ? AND ss.final_course_grade > 0
    ";
    $stmt = $connection->prepare($gpa_query);
    $stmt->execute([$student_id]);
    $gpa_data = $stmt->fetch();

    if ($gpa_data && $gpa_data['total_units'] > 0) {
        $gpa = round($gpa_data['weighted_sum'] / $gpa_data['total_units'], 2);
    }

    // Count enrolled subjects
    $enrolled_query = "
        SELECT COUNT(*) as count 
        FROM student_subjects 
        WHERE student_id = ?
    ";
    $stmt = $connection->prepare($enrolled_query);
    $stmt->execute([$student_id]);
    $enrolled_data = $stmt->fetch();
    $enrolled_subjects_count = $enrolled_data['count'];

    // Calculate balance
    // Get total cost of enrolled subjects
    $cost_query = "
        SELECT SUM(s.price_unit * s.units) as total_cost
        FROM student_subjects ss
        JOIN subjects s ON ss.subject_id = s.id
        WHERE ss.student_id = ?
    ";
    $stmt = $connection->prepare($cost_query);
    $stmt->execute([$student_id]);
    $cost_data = $stmt->fetch();
    $total_cost = $cost_data['total_cost'] ?? 0;

    // Get total payments
    $payment_query = "
        SELECT SUM(cash + gcash) as total_paid
        FROM collections
        WHERE student_id = ?
    ";
    $stmt = $connection->prepare($payment_query);
    $stmt->execute([$student_id]);
    $payment_data = $stmt->fetch();
    $total_paid = $payment_data['total_paid'] ?? 0;

    $total_balance = $total_cost - $total_paid;
}
?>

<body class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: false }">
    <?php require('../partials/student_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <main class="pt-16 lg:pt-0">
            <div class="py-8 px-4 sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900">Welcome back!</h1>
                    <p class="mt-1 text-gray-600">Here's what's happening with your account today.</p>
                </div>

                <!-- Dashboard Cards -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Current GPA</h2>
                                <p class="text-2xl font-bold text-gray-900"><?= number_format($gpa, 2) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Balance</h2>
                                <p class="text-2xl font-bold text-gray-900">₱<?= number_format($total_balance, 2) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Enrolled Subjects</h2>
                                <p class="text-2xl font-bold text-gray-900"><?= $enrolled_subjects_count ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <a href="grades.php" class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-800 transition-colors">
                            View Grades
                        </a>
                        <a href="collections.php" class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-800 transition-colors">
                            View Ledger
                        </a>
                        <a href="password.php" class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-800 transition-colors">
                            Change Password
                        </a>
                        <a href="/logout.php" class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-800 transition-colors">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

<?php
require('../partials/footer.php')
?>