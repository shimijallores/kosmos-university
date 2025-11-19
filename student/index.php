<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}
?>

<body class="flex justify-content flex-col items-center gap-y-6">
    <h1 class="text-3xl font-bold mt-6">Student Portal</h1>
    <a href="grades.php"
        class="bg-blue-500 text-center hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">My
        Grades</a>
    <a href="collections.php"
        class="bg-blue-500 text-center hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">My Ledger</a>
    <a href="password.php"
        class="bg-blue-500  text-center hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Change
        Password</a>
    <form method="post" action="/logout.php" type="submit">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Logout</button>
    </form>
</body>

<?php
require('../partials/footer.php')
?>