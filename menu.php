<?php
require('functions.php');
require('partials/head.php');

?>

<body class="flex justify-content flex-col items-center gap-y-6">
    <div class="text-center mb-8">
        <img src="images/logo.png" alt="Dino University Logo" class="w-20 mt-4 h-20 mx-auto mb-4">
        <h1 class="text-3xl font-bold mt-6">DINO UNIVERSITY</h1>
        <p class="text-gray-600">Welcome to the University Enrollment System</p>
    </div>

    <a href="login.php"
        class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-center text-white font-bold py-2 px-4 rounded">Student
        Login</a>
    <a href="login.php"
        class="bg-blue-500 text-center hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Admin
        Login</a>
    <a href="login.php"
        class="bg-blue-500 text-center hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Teacher Login</a>
</body>

<?php
require('partials/footer.php')
?>