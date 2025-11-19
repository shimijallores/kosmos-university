<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

// Fetch Student
$stmt = $connection->prepare("
    select s.student_id, s.student_number, s.name as student_name, s.gender, s.course_id, c.name as course_name
    from students s
    join courses c on s.course_id = c.course_id
    WHERE s.student_id = (?);
");
$stmt->execute([$_GET['id']]);
$student = $stmt->fetch();

if (!$student) {
    header('location: index.php');
    exit();
}

// Fetch Courses
$stmt = $connection->prepare("select * from courses ORDER BY name");
$stmt->execute();
$courses = $stmt->fetchAll();
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
                    <h1 class="text-2xl font-extrabold text-gray-900">Edit Student</h1>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
            <div class="max-w-2xl mx-auto">
                <!-- Student Info Card -->
                <div class="bg-white border border-gray-300 p-6 mb-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Editing: <?= htmlspecialchars($student['student_name']) ?></h2>
                    <p class="text-sm text-gray-600">Student Number: <?= htmlspecialchars($student['student_number']) ?></p>
                </div>

                <!-- Edit Form -->
                <div class="bg-white border border-gray-300 p-6">
                    <form action="update.php" method="POST" class="space-y-6">
                        <!-- Current student id -->
                        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">

                        <!-- Student Number -->
                        <div class="relative z-0 w-full group">
                            <input type="text"
                                name="student_number"
                                id="student_number"
                                value="<?= htmlspecialchars($student['student_number']) ?>"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-neutral-800 peer"
                                placeholder=" "
                                required />
                            <label for="student_number"
                                class="peer-focus:font-medium absolute text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-neutral-800 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Student Number
                            </label>
                        </div>

                        <!-- Student Name -->
                        <div class="relative z-0 w-full group">
                            <input type="text"
                                name="student_name"
                                id="student_name"
                                value="<?= htmlspecialchars($student['student_name']) ?>"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-neutral-800 peer"
                                placeholder=" "
                                required />
                            <label for="student_name"
                                class="peer-focus:font-medium absolute text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-neutral-800 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Full Name
                            </label>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select name="gender" id="gender"
                                class="w-full px-3 py-2 border border-gray-300 bg-white focus:ring-neutral-800 focus:border-neutral-800">
                                <option value="M" <?= $student['gender'] === 'M' ? 'selected' : '' ?>>Male</option>
                                <option value="F" <?= $student['gender'] === 'F' ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>

                        <!-- Course -->
                        <div>
                            <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                            <select name="course" id="course"
                                class="w-full px-3 py-2 border border-gray-300 bg-white focus:ring-neutral-800 focus:border-neutral-800">
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= $course['course_id'] ?>"
                                        <?= $student['course_id'] == $course['course_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($course['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-4">
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                Update Student
                            </button>
                            <a href="index.php"
                                class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

<?php
require('../partials/footer.php')
?>