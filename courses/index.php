<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

$stmt = $connection->prepare("select * from courses ORDER BY name");
$stmt->execute();
$courses = $stmt->fetchAll();
?>

<body class="bg-gray-50" x-data="{ sidebarOpen: false, deleteModal: false, deleteId: null }">
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
                    <h1 class="text-2xl font-extrabold text-gray-900">Courses</h1>
                </div>
                <a href="create.php" class="px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                    Add Course
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
            <div class="bg-white border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs uppercase bg-gray-50 text-gray-700 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-medium">Course ID</th>
                                <th scope="col" class="px-6 py-3 font-medium">Code</th>
                                <th scope="col" class="px-6 py-3 font-medium">Name</th>
                                <th scope="col" class="px-6 py-3 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($courses as $course): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        <?= htmlspecialchars($course['course_id']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-900">
                                        <?= htmlspecialchars($course['code']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-900">
                                        <?= htmlspecialchars($course['name']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="edit.php?id=<?= $course['course_id'] ?>"
                                                class="px-3 py-1 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                                Edit
                                            </a>
                                            <button @click="deleteModal = true; deleteId = <?= $course['course_id'] ?>"
                                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium border border-red-600 focus:ring-2 focus:ring-red-600 focus:ring-offset-2">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <?php require('delete.php'); ?>

</body>

<?php
require('../partials/footer.php')
?>