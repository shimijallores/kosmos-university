<?php
require('../functions.php');
require('../partials/head.php');

# Auth Barrier
if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

# Get current user info
$user = $_SESSION['user'];


# Get student info
$stmt = $connection->prepare("
select s.student_id, s.student_number, s.name as student_name, c.code as course_name
from students s
left join courses c on s.course_id = c.course_id
where s.student_number = ?;
");

$stmt->execute([$user['name']]);
$student = $stmt->fetch();

if (!$student) {
    header('location: /menu.php');
    exit();
}

if (isset($_POST['password']) && !empty($_POST['password'])) {
    $stmt = $connection->prepare("update users set password = ? where name = ?");
    $stmt->execute([$_POST['password'], $user['name']]);
    $semesters = $stmt->fetchAll();
    header('location: /student/index.php');
    exit();
}

?>

<body class="flex justify-content flex-col items-center gap-y-10">
    <div class="w-full md:max-w-3/4 flex justify-center mx-auto px-4">
        <a href="index.php"
            class="bg-blue-500 text-center mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Back
            to Menu</a>
    </div>
    <form action="password.php" method="POST" class="flex flex-col gap-y-2 items-center">
        <input type="password" autofocus name="password" class="rounded-sm px-2 py-1 border border-blue-400"
            placeholder="New Password">
        <input type="password" name="confirm_password" class="rounded-sm px-2 py-1 border border-blue-400"
            placeholder="Confirm Password">
        <button type="submit" class="bg-blue-500 mt-6 w-20 cursor-pointer text-white font-bold py-1 px-2 rounded">
            Submit
        </button>
    </form>
</body>

<?php
require('../partials/footer.php')
?>