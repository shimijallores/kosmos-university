<?php
require('../functions.php');
require('../partials/head.php');

// Fetch courses
$stmt = $connection->prepare("
    select * FROM courses
    WHERE course_id = (?);
");

$stmt->execute([$_GET['id']]);

$course = $stmt->fetch();

// Fetch Courses
$stmt = $connection->prepare("select * from courses");

$stmt->execute();

$courses = $stmt->fetchAll();
?>

<body class="flex justify-content flex-col items-center gap-y-6">
    <button class="bg-blue-500 mt-6 w-50 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="index.php">Back to Courses</a>
    </button>
    <div class="w-3/4 flex justify-between items-center">
        <h1 class="text-3xl font-bold mt-6">Edit <?= $course['name'] ?> Information</h1>
    </div>

    <!-- Add course Form -->
    <form action="update.php" method="POST" class="w-3/4 mx-auto">
        <!-- Current course id -->
        <input type="hidden" name="course_id" value="<?= $_GET['id'] ?>">

        <!-- Form Input -->
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="code" value="<?= $course['code'] ?>" id="code"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="course Number" required />
        </div>

        <!-- Form Input -->
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="name" value="<?= $course['name'] ?>" id="name"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="Name" required />
        </div>

        <button type="submit"
            class="bg-blue-700 hover:bg-blue-800 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
            Edit course
        </button>
    </form>

</body>

<?php
require('../partials/footer.php')
?>