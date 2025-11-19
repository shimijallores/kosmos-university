<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

$success = $_SESSION['enrollment_success'] ?? null;
$error = $_SESSION['enrollment_error'] ?? null;

// Clear the session messages
unset($_SESSION['enrollment_success'], $_SESSION['enrollment_error']);

// If no success or error, redirect to enrollment
if (!$success && !$error) {
    header("Location: index.php");
    exit();
}
?>

<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
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
                    <h1 class="text-2xl font-extrabold text-gray-900">Enrollment Status</h1>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
            <div class="max-w-2xl mx-auto">
                <?php if ($success): ?>
                    <!-- Success Card -->
                    <div class="bg-white border border-gray-300 p-8">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-green-50 border-2 border-green-500 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Enrollment Successful!</h2>
                            <p class="text-gray-600">Student has been successfully enrolled in the system.</p>
                        </div>

                        <!-- Student Details -->
                        <div class="bg-gray-50 border border-gray-200 p-6 mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Student Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Student Number:</span>
                                    <span class="text-sm font-medium text-gray-900 font-mono"><?= htmlspecialchars($success['student_number']) ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Full Name:</span>
                                    <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($success['student_name']) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Info Alert -->
                        <div class="bg-blue-50 border border-blue-200 p-4 mb-6">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-900">
                                        A user account has been automatically created with the student number as both username and password. The student should change their password after first login.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-4">
                            <a href="index.php"
                                class="flex-1 px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2 text-center">
                                Enroll Another Student
                            </a>
                            <a href="/index.php"
                                class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Error Card -->
                    <div class="bg-white border border-gray-300 p-8">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-red-50 border-2 border-red-500 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Enrollment Failed</h2>
                            <p class="text-gray-600">There was an error processing the enrollment.</p>
                        </div>

                        <!-- Error Message -->
                        <div class="bg-red-50 border border-red-200 p-4 mb-6">
                            <p class="text-sm text-red-900"><?= htmlspecialchars($error) ?></p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-4">
                            <a href="index.php"
                                class="flex-1 px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2 text-center">
                                Try Again
                            </a>
                            <a href="/index.php"
                                class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

<?php require('../partials/footer.php'); ?>