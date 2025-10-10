<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: /login.php');
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

<body x-data="{deleteModal: false, deleteId: null}" class="flex justify-content flex-col items-center gap-y-6">
    <!-- Delete Modal -->
    <?php require('delete.php') ?>

    <button class="bg-blue-500 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="/index.php">Back to Menu</a>
    </button>
    <div class="w-3/4 flex justify-between items-center">
        <h1 class="text-3xl font-bold mt-6">STUDENTS</h1>
        <button class="bg-neutral-900 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
            <a href="create.php">Add student</a>
        </button>
    </div>

    <!-- Students table -->
    <table class="w-3/4 text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-white uppercase bg-blue-500 ">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Student Number
                </th>
                <th scope="col" class="px-6 py-3">
                    Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Gender
                </th>
                <th scope="col" class="px-6 py-3">
                    Course
                </th>
                <th scope="col" class="px-6 py-3">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <?php
                foreach ($students as $student) {
                    echo '<tr>';
                    echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">';
                    echo $student['student_number'];
                    echo '</th>';

                    echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">';
                    echo $student['student_name'];
                    echo '</th>';

                    echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">';
                    echo $student['gender'];
                    echo '</th>';

                    echo '<th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">';
                    echo $student['course_name'];
                    echo '</th>';

                    echo '<td>';
                    echo '<button class="bg-blue-500 mr-2 hover:bg-blue-700 w-25 cursor-pointer text-white font-bold py-2 px-4 rounded">';
                    echo "<a href='edit.php?id={$student["student_id"]}'>Edit</a>";
                    echo '</button>';

                    echo "
                    <button @click='deleteModal = true; deleteId = {$student["student_id"]}; console.log(deleteId)'
                        class='bg-red-500 hover:bg-red-700 w-25 cursor-pointer text-white font-bold py-2 px-4 rounded'>
                        Delete
                    </button>
                    ";

                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tr>
        </tbody>
    </table>

</body>

<?php
require('../partials/footer.php')
?>