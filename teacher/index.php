<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}
?>

<body class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: false }">
    <?php require('../partials/teacher_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <main class="pt-16 lg:pt-0">
            <div class="py-8 px-4 sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900">Welcome back, Teacher!</h1>
                    <p class="mt-1 text-gray-600">Manage your classes and student grades from here.</p>
                </div>

                <!-- Quick Actions -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <a href="grading.php" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-50 rounded-md p-3">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Grading</h3>
                                    <p class="text-sm text-gray-600">Manage student grades</p>
                                </div>
                            </div>
                        </a>

                        <a href="/logout.php" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-gray-50 rounded-md p-3">
                                    <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">Logout</h3>
                                    <p class="text-sm text-gray-600">Sign out of your account</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="bg-white border border-gray-300 p-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">Teacher Portal</h3>
                            <p class="text-sm text-gray-600">Use the navigation menu to access grading tools and manage your classes. Click on "Grading" to view and update student grades.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

<?php
require('../partials/footer.php')
?>