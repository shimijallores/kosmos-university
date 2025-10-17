<?php
require('../functions.php');
require('../partials/head.php');
?>

<body class="flex justify-content flex-col items-center gap-y-2">
    <h1 class="text-2xl m-10 text-red-700">Failed to delete subject! Subject linked to an existing grade!</h1>
    <button class="mt-6"><a href="index.php?semester=1st25-26" class="underline mt-6 text-blue-500">Return
            Back</a></button>
</body>

<?php
require('../partials/footer.php')
?>