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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($password)) {
        $error = 'Password is required';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        $stmt = $connection->prepare("update users set password = ? where name = ?");
        $stmt->execute([$password, $user['name']]);
        $success = 'Password changed successfully';
    }
}

?>

<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <?php require('../partials/student_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-2xl font-extrabold text-gray-900">Change Password</h1>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6">
            <div class="max-w-md mx-auto">
                <!-- Info Card -->
                <div class="bg-white border border-gray-300 p-6 mb-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">Security Tip</h3>
                            <p class="text-sm text-gray-600">Choose a strong password with at least 6 characters. Avoid using easily guessable information.</p>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <?php if (!empty($success)) : ?>
                    <div class="mb-6 bg-green-50 border border-green-200 p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-green-800"><?= htmlspecialchars($success) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if (!empty($error)) : ?>
                    <div class="mb-6 bg-red-50 border border-red-200 p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-red-800"><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Password Form -->
                <form action="password.php" method="POST" class="bg-white border border-gray-300 p-6">
                    <div class="space-y-6">
                        <div class="relative z-0 w-full group">
                            <input type="password"
                                name="password"
                                id="password"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-neutral-800 peer"
                                placeholder=" "
                                required
                                autofocus />
                            <label for="password"
                                class="peer-focus:font-medium absolute text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-neutral-800 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                New Password
                            </label>
                        </div>

                        <div class="relative z-0 w-full group">
                            <input type="password"
                                name="confirm_password"
                                id="confirm_password"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-neutral-800 peer"
                                placeholder=" "
                                required />
                            <label for="confirm_password"
                                class="peer-focus:font-medium absolute text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-neutral-800 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Confirm Password
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<?php
require('../partials/footer.php')
?>