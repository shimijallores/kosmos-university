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
    <div class="w-3/4 flex justify-between items-center">
        <h1 class="text-3xl font-bold mt-6">STUDENTS</h1>
        <div class="flex items-center justify-center gap-x-3">
            <h1 class="text-xl">Report Order</h1>
            <select name="sort" id="sort" @change="window.location.href = `list.php?sort=${$event.target.value}`"
                class="border border-black py-1 px-2">
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
                    echo '</tr>';
                }
                ?>
            </tr>
            <tr>
                <th scope="row" colspan=4
                    class="px-6 py-4 text-end text-gray-900 text-xl font-bold whitespace-nowrap dark:text-white">
                    Total
                    Records: <?= count($students) ?></th>
            </tr>
        </tbody>
    </table>

</body>

<?php
require('partials/footer.php')
?>