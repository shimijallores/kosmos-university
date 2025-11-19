<?php
require('functions.php');
require('partials/head.php');

?>

<body class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <img src="images/logo.png" alt="Dino University Logo" class="w-24 h-24 mx-auto mb-6">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">DINO UNIVERSITY</h1>
            <p class="text-gray-600 text-lg">Welcome to the Dino University System</p>
        </div>

        <div class="bg-white py-8 px-6 rounded-lg space-y-3">
            <p class="text-sm font-medium text-gray-700 mb-4">Select your role to continue</p>

            <a href="auth/student_login.php"
                class="group relative w-full flex items-center justify-center py-3 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-900 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-800 transition-colors duration-200">
                <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                Student Login
            </a>

            <a href="auth/admin_login.php"
                class="group relative w-full flex items-center justify-center py-3 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-900 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-800 transition-colors duration-200">
                <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </span>
                Admin Login
            </a>

            <a href="auth/teacher_login.php"
                class="group relative w-full flex items-center justify-center py-3 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-900 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-800 transition-colors duration-200">
                <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </span>
                Teacher Login
            </a>
        </div>
    </div>
</body>

<?php
require('partials/footer.php')
?>