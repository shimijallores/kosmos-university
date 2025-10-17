<?php
require('../functions.php');
require('../partials/head.php');
?>

<body class="flex justify-content flex-col items-center gap-y-6">
    <a href="index.php"
        class="bg-blue-500 text-center mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">Back</a>
    <div class="w-3/4 flex justify-between items-center">
        <h1 class="text-3xl font-bold mt-6">CREATE NEW COURSE</h1>
    </div>

    <!-- Add Student Form -->
    <form action="store.php" method="POST" class="w-3/4 mx-auto">
        <!-- Form Input -->
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="code" id="code"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="Course Code" required />
        </div>

        <!-- Form Input -->
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="name" id="name"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="Course Name" required />
        </div>

        <button type="submit"
            class="bg-blue-700 hover:bg-blue-800 mt-6 w-40 cursor-pointer text-white font-bold py-2 px-4 rounded">
            Add Course
        </button>
    </form>

</body>

<?php
require('../partials/footer.php')
?>