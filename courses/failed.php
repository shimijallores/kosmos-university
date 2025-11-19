<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}
?>

<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <?php require('../partials/admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <!-- Content Area -->
        <div class="p-6">
            <div class="max-w-2xl mx-auto">
                <!-- Error Card -->
                <div class="bg-white border border-red-300 p-6">
                    <div class="flex items-start space-x-3 mb-6">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-xl font-medium text-red-700 mb-1">Failed to delete course</h1>
                            <p class="text-sm text-gray-600 mb-4">This course is linked to existing students and cannot be deleted.</p>

                            <div class="bg-gray-50 border border-gray-200 p-4 mt-4">
                                <h2 class="text-sm font-medium text-gray-900 mb-3">Linked Students</h2>
                                <ul class="space-y-2">
                                    <?php foreach ($_SESSION['linked_students'] as $student) : ?>
                                        <li class="text-sm text-gray-700">• <?= htmlspecialchars($student['name']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <a href="<?= $_SESSION['redirect'] ?>"
                        class="inline-block px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                        Return Back
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>

<?php
require('../partials/footer.php')
?>