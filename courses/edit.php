<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

// Fetch course
$stmt = $connection->prepare("SELECT * FROM courses WHERE course_id = (?)");
$stmt->execute([$_GET['id']]);
$course = $stmt->fetch();

if (!$course) {
    header('location: index.php');
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
                    <h1 class="text-2xl font-extrabold text-gray-900">Edit Course</h1>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
            <div class="max-w-2xl mx-auto">
                <!-- Course Info Card -->
                <div class="bg-white border border-gray-300 p-6 mb-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Editing: <?= htmlspecialchars($course['name']) ?></h2>
                    <p class="text-sm text-gray-600">Course Code: <?= htmlspecialchars($course['code']) ?></p>
                </div>

                <!-- Edit Form -->
                <div class="bg-white border border-gray-300 p-6">
                    <form action="update.php" method="POST" class="space-y-6">
                        <!-- Current course id -->
                        <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">

                        <!-- Course Code -->
                        <div class="relative z-0 w-full group">
                            <input type="text"
                                name="code"
                                id="code"
                                value="<?= htmlspecialchars($course['code']) ?>"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-neutral-800 peer"
                                placeholder=" "
                                required />
                            <label for="code"
                                class="peer-focus:font-medium absolute text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-neutral-800 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Course Code
                            </label>
                        </div>

                        <!-- Course Name -->
                        <div class="relative z-0 w-full group">
                            <input type="text"
                                name="name"
                                id="name"
                                value="<?= htmlspecialchars($course['name']) ?>"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-neutral-800 peer"
                                placeholder=" "
                                required />
                            <label for="name"
                                class="peer-focus:font-medium absolute text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-neutral-800 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Course Name
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-4">
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                Update Course
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