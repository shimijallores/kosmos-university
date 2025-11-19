<?php
require('functions.php');
require('partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: menu.php');
    exit();
}
?>

<body class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: false }">
    <?php require('partials/admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <main class="pt-16 lg:pt-0">
            <div class="py-8 px-4 sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900">Admin Dashboard</h1>
                    <p class="mt-1 text-gray-600">Manage students, courses, collections, and more.</p>
                </div>

                <!-- Quick Actions Grid -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Enrollment Card -->
                    <a href="enrollment/index.php" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Enrollment</h3>
                                <p class="text-sm text-gray-600">Enroll new students</p>
                            </div>
                        </div>
                    </a>

                    <!-- Students Card -->
                    <a href="students/index.php" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Students</h3>
                                <p class="text-sm text-gray-600">Manage student records</p>
                            </div>
                        </div>
                    </a>

                    <!-- Collections Card -->
                    <a href="collections/index.php" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Collections</h3>
                                <p class="text-sm text-gray-600">Payment management</p>
                            </div>
                        </div>
                    </a>

                    <!-- Courses Card -->
                    <a href="courses/index.php" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Courses</h3>
                                <p class="text-sm text-gray-600">Manage courses</p>
                            </div>
                        </div>
                    </a>

                    <!-- Subjects Card -->
                    <a href="subjects/index.php?semester=1st25-26" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Subjects</h3>
                                <p class="text-sm text-gray-600">Manage subjects</p>
                            </div>
                        </div>
                    </a>

                    <!-- Grades Card -->
                    <a href="grades/index.php?semester=1st25-26" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Grades</h3>
                                <p class="text-sm text-gray-600">View and manage grades</p>
                            </div>
                        </div>
                    </a>

                    <!-- Semesters Card -->
                    <a href="semesters/index.php" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-pink-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Semesters</h3>
                                <p class="text-sm text-gray-600">Manage semesters</p>
                            </div>
                        </div>
                    </a>

                    <!-- List Card -->
                    <a href="list.php" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gray-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">List</h3>
                                <p class="text-sm text-gray-600">View lists</p>
                            </div>
                        </div>
                    </a>

                    <!-- Report Card -->
                    <a href="report.php" class="bg-white border border-gray-300 p-6 hover:border-neutral-800 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-teal-50 rounded-md p-3">
                                <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Report</h3>
                                <p class="text-sm text-gray-600">Generate reports</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>

<?php
require('partials/footer.php')
?>