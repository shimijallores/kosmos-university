<?php
require('../functions.php');
require('../partials/head.php');
?>

<body class="flex justify-content flex-col items-center gap-y-6">
    <h1 class="text-2xl m-10 text-red-700">Failed to perform action.</h1>
    <button><a href="<?= $_SESSION['redirect'] ?>" class="underline text-blue-500">Return Back</a></button>
</body>

<?php
require('../partials/footer.php')
?>