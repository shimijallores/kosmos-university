<?php
require('functions.php');
require('partials/head.php');

// No auth barrier - this is a public menu
?>

<body class="flex justify-content flex-col items-center gap-y-6">
  <div class="text-center mb-8">
    <img src="images/logo.png" alt="Dino University Logo" class="w-20 mt-4 h-20 mx-auto mb-4">
    <h1 class="text-3xl font-bold mt-6">DINO UNIVERSITY</h1>
    <p class="text-gray-600">Welcome to the University Enrollment System</p>
  </div>

  <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
    <a href="enrollment/index.php">Enrollment</a>
  </button>
  <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
    <a href="login.php">Student Login</a>
  </button>
  <button class="bg-blue-500 hover:bg-blue-700 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
    <a href="login.php">Admin Login</a>
  </button>
</body>

<?php
require('partials/footer.php')
?>