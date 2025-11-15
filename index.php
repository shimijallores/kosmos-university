<?php
require('functions.php');
require('partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: menu.php');
    exit();
}
?>

<body class="flex justify-content flex-col items-center gap-y-6">
    <h1 class="text-3xl font-bold mt-6">Admin Portal</h1>
    <a href="enrollment/index.php"
        class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded block text-center">
        Enrollment
    </a>
    <a href="students/index.php"
        class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded block text-center">
        Students
    </a>
    <a href="collections/index.php"
        class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded block text-center">
        Collections
    </a>
    <a href="courses/index.php"
        class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded block text-center">
        Courses
    </a>
    <a href="subjects/index.php?semester=1st25-26"
        class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded block text-center">
        Subject
    </a>
    <a href="grades/index.php?semester=1st25-26"
        class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded block text-center">
        Grades
    </a>
    <a href="semesters/index.php?semester=1st25-26"
        class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded block text-center">
        Semesters
    </a>
    <a href="list.php"
        class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded block text-center">
        List
    </a>
    <a href="report.php"
        class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded block text-center">
        Report
    </a>

    <form method="post" action="logout.php" type="submit">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Logout</button>
    </form>
</body>

<?php
require('partials/footer.php')
?>