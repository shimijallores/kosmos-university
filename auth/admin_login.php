<?php
require('../functions.php');
require('../partials/database.php');

session_start();

if (isset($_SESSION['user'])) {
  if ($_SESSION['user']['role'] === 'admin') {
    header('location: ../index.php');
  } else {
    header('location: ../menu.php');
  }
  exit();
}

if (!empty($_POST['name'])) {
  $username = $_POST['name'];
  $password = $_POST['password'];

  $stmt = $connection->prepare("select * from users where name = ? and role = 'admin'");
  $stmt->execute([$username]);
  $user = $stmt->fetch();

  if (!empty($user)) {
    if ($password === $user['password']) {
      $_SESSION['user'] = $user;
      header('location: ../index.php');
      exit();
    }
  }
  $_SESSION['message'] = 'Invalid credentials';
}

$_SESSION['message'] = '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8">
    <div>
      <div class="mx-auto h-12 w-12 bg-neutral-800 rounded-full flex items-center justify-center">
        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
      </div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Admin Login
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Sign in to your admin account
      </p>
    </div>
    <form class="mt-8 space-y-6" action="admin_login.php" method="POST">
      <div class="bg-white py-8 px-6 rounded-lg space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
            Username
          </label>
          <input
            id="name"
            name="name"
            type="text"
            autocomplete="username"
            required
            autofocus
            class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-neutral-800 focus:border-neutral-800 focus:z-10 sm:text-sm transition-colors"
            placeholder="Enter your username">
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            Password
          </label>
          <input
            id="password"
            name="password"
            type="password"
            autocomplete="current-password"
            required
            class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-neutral-800 focus:border-neutral-800 focus:z-10 sm:text-sm transition-colors"
            placeholder="Enter your password">

          <?php if (isset($_SESSION['message']) && !empty($_SESSION['message'])): ?>
            <div class="rounded-md bg-red-50 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-red-800">
                    <?= $_SESSION['message'] ?>
                  </p>
                </div>
              </div>
            </div>
            <?php $_SESSION['message'] = ""; ?>
          <?php endif; ?>

          <div>
            <button
              type="submit"
              class="group my-4 relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-neutral-800 hover:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-800 transition-colors duration-200">
              Sign in
            </button>
          </div>

          <div class="text-center">
            <a href="../menu.php" class="text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
              ← Back to Main Menu
            </a>
          </div>
        </div>
    </form>
  </div>
</body>

</html>