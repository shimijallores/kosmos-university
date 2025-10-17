<?php
require('../functions.php');
require('../partials/head.php');

$stmt = $connection->prepare("
    select * from courses;
");

if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}

$stmt->execute();

$courses = $stmt->fetchAll();
?>

<body x-data="{deleteModal: false, deleteId: null}" class="flex justify-content flex-col items-center gap-y-6">
    <!-- Delete Modal -->
    <?php require('delete.php') ?>

    <a href="/index.php"
        class="bg-blue-500 text-center mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Back to
        Menu</a>
    <div class="w-full md:max-w-3/4 mx-auto px-4 md:px-0 gap-x-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold mt-6">COURSES</h1>
        <a href="create.php" class="bg-neutral-900 text-center text-xs sm:text-lg mt-6 w-32 sm:w-40 cursor-pointer text-white font-bold py-2 px-4
        rounded">Add a course</a>
    </div>

    <!-- courses table -->
    <div class="w-full flex md:justify-center overflow-x-auto">
        <table
            class="w-full min-w-[500px] max-w-3/4 text-xs sm:text-sm border border-blue-500 text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-white uppercase bg-blue-500">
                <tr>
                    <th scope="col" class="px-2 sm:px-6 py-3">
                        Course Id
                    </th>
                    <th scope="col" class="px-2 sm:px-6 py-3">
                        Code
                    </th>
                    <th scope="col" class="px-2 sm:px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-2 sm:px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($courses as $course) {
                    echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">';
                    echo '<th scope="row" class="px-2 sm:px-6 py-4 font-medium text-gray-900 dark:text-white break-words">';
                    echo $course['course_id'];
                    echo '</th>';

                    echo '<th scope="row" class="px-2 sm:px-6 py-4 font-medium text-gray-900 dark:text-white break-words">';
                    echo $course['code'];
                    echo '</th>';

                    echo '<th scope="row" class="px-2 sm:px-6 py-4 font-medium text-gray-900 dark:text-white break-words">';
                    echo $course['name'];
                    echo '</th>';

                    echo '<td class="px-2 sm:px-6 py-4">';
                    echo '<div class="flex flex-col sm:flex-row gap-1">';
                    echo '<button class="bg-blue-500 text-xs hover:bg-blue-700 cursor-pointer text-white font-bold py-1 px-2 sm:py-2 sm:px-4 rounded">';
                    echo "<a href='edit.php?id={$course["course_id"]}'>Edit</a>";
                    echo '</button>';

                    echo '<button @click="deleteModal = true; deleteId = ' . $course["course_id"] . '; console.log(deleteId)"';
                    echo ' class="bg-red-500 text-xs hover:bg-red-700 cursor-pointer text-white font-bold py-1 px-2 sm:py-2 sm:px-4 rounded">';
                    echo 'Delete';
                    echo '</button>';
                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

<?php
require('../partials/footer.php')
?>