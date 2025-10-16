<?php
require('functions.php');
require('partials/database.php');

session_start();

if (isset($_SESSION['user'])) {
  if ($_SESSION['user']['role'] === 'admin') {
    header('location: index.php');
  } elseif ($_SESSION['user']['role'] === 'student') {
    header('location: student/index.php');
  } else {
    header('location: index.php');
  }
  exit();
}

if (!empty($_POST['name'])) {
  $username = $_POST['name'];
  $password = $_POST['password'];

  $stmt = $connection->prepare("select * from users where name = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch();

  if (!empty($user)) {
    if ($password === $user['password']) {
      $_SESSION['user'] = $user;

      // Redirect based on user role
      if ($user['role'] === 'admin') {
        header('location: index.php');
      } elseif ($user['role'] === 'student') {
        header('location: student/index.php');
      }
      exit();
    }
  }
}

$_SESSION['message'] = 'Invalid credentials';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="flex justify-center p-20">
  <form action="login.php" method="POST" class="">
    <h1 class="text-2xl font-bold">Login</h1>
    <div class="flex flex-col justify-start gap-y-2 mt-2 border border-blue-300 p-10 rounded">
      <div>
        <label>Username</label>
        <input type="text" name="name" class="px-2 py-1 rounded border border-neutral-200">
      </div>


      <div>
        <label>Password</label>
        <input type="password" name="password" class="px-2 py-1 rounded border border-neutral-200">
      </div>

      <?php if (isset($_SESSION['message'])): ?>
        <p class="text-red-600 text-xs"><?= $_SESSION['message'] ?></p>
        <?php $_SESSION['message'] = ""; ?>
      <?php endif; ?>

      <button type="submit"
        class="bg bg-blue-600 px-2 py-1 text-white w-full text-center rounded-md hover:opacity-80 cursor-pointer transition duration-200">Login</button>

      <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">
          New student?
          <a href="enrollment/index.php" class="text-blue-600 hover:text-blue-800 font-medium">
            Enroll here
          </a>
        </p>
      </div>
    </div>
  </form>
</body>

</html>

<?php
require('partials/footer.php')
?>