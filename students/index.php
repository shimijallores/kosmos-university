<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

$stmt = $connection->prepare("
    select s.student_id, s.student_number, s.name as student_name, s.gender, c.name as course_name
    from students s 
    join courses c on s.course_id = c.course_id
    ORDER BY s.gender, s.name;
");

$stmt->execute();

$students = $stmt->fetchAll();
?>

<body class="bg-gray-50" x-data="{ sidebarOpen: false, deleteModal: false, deleteId: null }">
    <?php require('../partials/admin_sidebar.php'); ?>

    <!-- Delete Modal -->
    <?php require('delete.php') ?>

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
                    <h1 class="text-2xl font-extrabold text-gray-900">Students</h1>
                </div>
                <a href="create.php"
                    class="inline-flex items-center px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Student
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
            <!-- Students Table -->
            <div class="bg-white border border-gray-300">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Student Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Gender</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($students)) : ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <p class="text-sm">No students found.</p>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($students as $student) : ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($student['student_number']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($student['student_name']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($student['gender']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($student['course_name']) ?></td>
                                        <td class="px-6 py-4 text-sm text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="edit.php?id=<?= $student['student_id'] ?>"
                                                    class="inline-flex items-center px-3 py-1 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-xs font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <button @click="deleteModal = true; deleteId = <?= $student['student_id'] ?>"
                                                    class="inline-flex items-center px-3 py-1 border border-red-300 bg-white hover:bg-red-50 text-red-700 text-xs font-medium focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </div>
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

<?php
require('../partials/footer.php')
?>