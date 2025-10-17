<?php
require('functions.php');
require('partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: menu.php');
    exit();
}
?>

<body class="flex justify-content flex-col items-center gap-y-6">
    <h1 class="text-3xl font-bold mt-6">Menu</h1>
    <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="students/index.php">Students</a>
    </button>
    <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="courses/index.php">Courses</a>
    </button>
    <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="subjects/index.php?semester=1st25-26">Subject</a>
    </button>
    <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="grades/index.php?semester=1st25-26">Grades</a>
    </button>
    <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="semesters/index.php?semester=1st25-26">Semesters</a>
    </button>
    <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="list.php">List</a>
    </button>
    <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="report.php">Report</a>
    </button>
    <form method="post" action="logout.php" type="submit">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Logout</button>
    </form>
</body>

<?php
require('partials/footer.php')
?>