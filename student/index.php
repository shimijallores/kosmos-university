<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
  header('location: login.php');
  exit();
}
?>

<body class="flex justify-content flex-col items-center gap-y-6">
  <h1 class="text-3xl font-bold mt-6">Student Portal</h1>
  <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
    <a href="grades.php">My Grades</a>
  </button>
  <form method="post" action="/logout.php" type="submit">
    <button type="submit"
      class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Logout</button>
  </form>
</body>

<?php
require('../partials/footer.php')
?>