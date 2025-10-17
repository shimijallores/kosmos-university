<?php
require('../functions.php');
require('../partials/head.php');

// No auth barrier for enrollment - this is for new students

# Get available courses and semesters for the form
$stmt = $connection->prepare("SELECT course_id, code, name FROM courses ORDER BY name");
$stmt->execute();
$courses = $stmt->fetchAll();

$stmt = $connection->prepare("SELECT id, code FROM semesters ORDER BY code DESC");
$stmt->execute();
$semesters = $stmt->fetchAll();


if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}
?>

<body class="flex justify-center items-center min-h-screen bg-gray-50">
    <div class="w-full max-w-md mx-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <img src="../images/logo.png" alt="University Logo" class="w-20 h-20 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-900">DINO UNIVERSITY</h1>
            <p class="text-gray-600 mt-2">Student Enrollment Portal</p>
        </div>

        <!-- Enrollment Form -->
        <div class="bg-white rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Enroll as New Student</h2>

            <form action="store.php" method="POST" class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="student_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>
                    <input type="text" id="student_name" name="student_name"
                        class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter your full name" required>
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                        Gender
                    </label>
                    <select id="gender" name="gender"
                        class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                        <option value="">Select Gender</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>

                <!-- Course -->
                <div>
                    <label for="course" class="block text-sm font-medium text-gray-700 mb-2">
                        Course
                    </label>
                    <select id="course" name="course"
                        class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['course_id'] ?>">
                            <?= htmlspecialchars($course['code'] . ' - ' . $course['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Semester -->
                <div>
                    <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                        Semester
                    </label>
                    <select id="semester" name="semester"
                        class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                        <option value="">Select Semester</option>
                        <?php foreach ($semesters as $semester): ?>
                        <option value="<?= $semester['id'] ?>">
                            <?= htmlspecialchars($semester['code']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Enroll Now
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    <a href="../login.php" class=" bg-blue-500 p-2 text-white hover:text-neutral-700 font-medium">
                        Back to menu
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>

<?php require('../partials/footer.php'); ?>