<?php
require('functions.php');
require('partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: menu.php');
    exit();
}

$query = "
    select s.student_id, s.student_number, s.name as student_name, s.gender, c.name as course_name
    from students s 
    join courses c on s.course_id = c.course_id
";

if (isset($_GET['sort'])) {
    if ($_GET['sort'] === 'name') {
        $query .= ' ORDER BY s.name';
    } elseif ($_GET['sort'] === 'course') {
        $query .= ' ORDER BY c.name';
    }
}

$stmt = $connection->prepare($query);

$stmt->execute();

$students = $stmt->fetchAll();
?>

<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <?php require('partials/admin_sidebar.php'); ?>

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
                    <h1 class="text-2xl font-extrabold text-gray-900">Students List</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <label class="text-sm text-gray-600">Sort by:</label>
                    <select name="sort"
                        @change="window.location.href = `list.php?sort=${$event.target.value}`"
                        class="px-3 py-2 border border-gray-300 bg-white focus:ring-neutral-800 focus:border-neutral-800 text-sm">
                        <option value="">Default</option>
                        <option value="name" <?= isset($_GET['sort']) && $_GET['sort'] === 'name' ? 'selected' : '' ?>>Name</option>
                        <option value="course" <?= isset($_GET['sort']) && $_GET['sort'] === 'course' ? 'selected' : '' ?>>Course</option>
                    </select>
                </div>
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
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($students)) : ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
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
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    Total Records: <span class="font-extrabold"><?= count($students) ?></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<?php
require('partials/footer.php')
?>