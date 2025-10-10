<?php
require('../functions.php');
require('../partials/head.php');

// Fetch Students
$stmt = $connection->prepare("
    select s.student_number, s.name as student_name, s.gender, c.name as course_name
    from students s
    join courses c on s.course_id = c.course_id
    WHERE s.student_id = (?);
");

$stmt->execute([$_GET['id']]);

$student = $stmt->fetch();

// Fetch Courses
$stmt = $connection->prepare("select * from courses");

$stmt->execute();

$courses = $stmt->fetchAll();
?>

<body class="flex justify-content flex-col items-center gap-y-6">
    <button class="bg-blue-500 mt-6 w-50 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="index.php">Back to Students</a>
    </button>
    <div class="w-3/4 flex justify-between items-center">
        <h1 class="text-3xl font-bold mt-6">Edit <?= $student['student_name'] ?> Information</h1>
    </div>

    <!-- Add Student Form -->
    <form action="update.php" method="POST" class="w-3/4 mx-auto">
        <!-- Current student id -->
        <input type="hidden" name="student_id" value="<?= $_GET['id'] ?>">

        <!-- Form Input -->
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="student_number" value="<?= $student['student_number'] ?>" id="student_number"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="Student Number" required />
        </div>

        <!-- Form Input -->
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="student_name" value="<?= $student['student_name'] ?>" id="student_name"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="Name" required />
        </div>

        <!-- Form Select -->
        <div class="relative z-0 w-full mb-5 group">
            <label for="gender">Gender</label>
            <select name="gender" id="gender"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="M" <?= $student['gender'] === 'M' ? 'selected' : '' ?>>Male</option>
                <option value="F" <?= $student['gender'] === 'F' ? 'selected' : '' ?>>Female</option>
            </select>
        </div>

        <!-- Form Select -->
        <div class="relative z-0 w-full mb-5 group">
            <label for="course">Course</label>
            <select name="course" id="course"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                <?php foreach ($courses as $course): ?>
                <option value="<?= $course['course_id'] ?>"
                    <?= $student['course_name'] === $course['name'] ? 'selected' : '' ?>><?= $course['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit"
            class="bg-blue-700 hover:bg-blue-800 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
            Edit Student
        </button>
    </form>

</body>

<?php
require('../partials/footer.php')
?>