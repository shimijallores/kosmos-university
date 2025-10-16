<?php
require('../functions.php');
require('../partials/head.php');

$success = $_SESSION['enrollment_success'] ?? null;
$error = $_SESSION['enrollment_error'] ?? null;

// Clear the session messages
unset($_SESSION['enrollment_success'], $_SESSION['enrollment_error']);

// If no success or error, redirect to enrollment
if (!$success && !$error) {
  header("Location: index.php");
  exit();
}
?>

<body class="flex justify-center items-center min-h-screen bg-gray-50">
  <div class="w-full max-w-md mx-4">
    <!-- Header -->
    <div class="text-center mb-8">
      <img src="../images/logo.png" alt="University Logo" class="w-20 h-20 mx-auto mb-4">
      <h1 class="text-3xl font-bold text-gray-900">DINO UNIVERSITY</h1>
      <p class="text-gray-600 mt-2">Enrollment Portal</p>
    </div>

    <!-- Success/Error Message -->
    <div class="bg-white rounded-lg p-6">
      <?php if ($success): ?>
        <!-- Success -->
        <div class="text-center">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Enrollment Successful!</h2>
          <p class="text-gray-600 mb-6">Welcome to DINO UNIVERSITY</p>

          <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="font-medium text-gray-700">Student Number:</span>
                <span class="text-gray-900 font-mono"><?= htmlspecialchars($success['student_number']) ?></span>
              </div>
              <div class="flex justify-between">
                <span class="font-medium text-gray-700">Name:</span>
                <span class="text-gray-900"><?= htmlspecialchars($success['student_name']) ?></span>
              </div>
            </div>
          </div>

          <div class="space-y-3">
            <p class="text-sm text-gray-600">
              Your subjects will be assigned by an administrator.
            </p>
            <p class="text-sm text-gray-600">
              Please save your student number for future logins.
            </p>
          </div>
        </div>
      <?php else: ?>
        <!-- Error -->
        <div class="text-center">
          <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Enrollment Failed</h2>
          <p class="text-gray-600 mb-6"><?= htmlspecialchars($error) ?></p>
        </div>
      <?php endif; ?>

      <!-- Actions -->
      <div class="mt-8 space-y-3">
        <?php if ($success): ?>
          <a href="../login.php"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 inline-block text-center">
            Proceed to Login
          </a>
        <?php endif; ?>

        <a href="index.php"
          class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-4 rounded-md transition duration-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 inline-block text-center">
          Back to Enrollment
        </a>
      </div>
    </div>
  </div>
</body>

<?php require('../partials/footer.php'); ?>