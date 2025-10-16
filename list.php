<?php
require('functions.php');
require('partials/head.php');


$query = "
    select s.student_id, s.student_number, s.name as student_name, s.gender, c.name as course_name
    from students s 
    join courses c on s.course_id = c.course_id
";

if (isset($_GET['sort'])) {
    if ($_GET['sort'] === 'name') {
        $query .= 'ORDER BY s.name';
    } elseif ($_GET['sort'] === 'course') {
        $query .= 'ORDER BY c.name';
    }
}

$stmt = $connection->prepare($query);

$stmt->execute();

$students = $stmt->fetchAll();
?>

<body x-data="{sort: null}" class="flex justify-content flex-col items-center gap-y-6">
    <button class="bg-blue-500 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
        <a href="/index.php">Back to Menu</a>
    </button>
    <div class="w-full md:w-3/4 mx-auto px-4 gap-x-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <h1 class="text-2xl font-bold mt-6">STUDENTS</h1>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-x-3 mt-4 sm:mt-6">
            <h1 class="text-lg sm:text-xl">Report Order</h1>
            <select name="sort" id="sort" @change="window.location.href = `list.php?sort=${$event.target.value}`"
                class="border border-black py-1 px-2 text-sm">
                <option value="">Default</option>
                <option value="name"
                    :selected="new URLSearchParams(window.location.search).get('sort') === 'name' ? true : false">Name
                </option>
                <option value="course"
                    :selected="new URLSearchParams(window.location.search).get('sort') === 'course' ? true : false">
                    Course
                </option>
            </select>
        </div>

    </div>

    <!-- Students table -->
    <div class="w-full flex  md:justify-center overflow-x-auto">
        <table class="w-full min-w-[500px] md:max-w-3/4 text-xs sm:text-sm border border-blue-500 text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-white uppercase bg-blue-500">
                <tr>
                    <th scope="col" class="px-2 sm:px-6 py-3">
                        Student Number
                    </th>
                    <th scope="col" class="px-2 sm:px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-2 sm:px-6 py-3">
                        Gender
                    </th>
                    <th scope="col" class="px-2 sm:px-6 py-3">
                        Course
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($students as $student) {
                    echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">';
                    echo '<th scope="row" class="px-2 sm:px-6 py-4 font-medium text-gray-900 dark:text-white break-words">';
                    echo $student['student_number'];
                    echo '</th>';

                    echo '<th scope="row" class="px-2 sm:px-6 py-4 font-medium text-gray-900 dark:text-white break-words">';
                    echo $student['student_name'];
                    echo '</th>';

                    echo '<th scope="row" class="px-2 sm:px-6 py-4 font-medium text-gray-900 dark:text-white break-words">';
                    echo $student['gender'];
                    echo '</th>';

                    echo '<th scope="row" class="px-2 sm:px-6 py-4 font-medium text-gray-900 dark:text-white break-words">';
                    echo $student['course_name'];
                    echo '</th>';
                    echo '</tr>';
                }
                ?>
                <tr>
                    <th scope="row" colspan=4
                        class="px-2 sm:px-6 py-4 text-end text-gray-900 text-lg sm:text-xl font-bold dark:text-white break-words">
                        Total
                        Records: <?= count($students) ?></th>
                </tr>
            </tbody>
        </table>
    </div>

</body>

<?php
require('partials/footer.php')
?>