<?php
$styles = [];
require(__ROOT__ . '/src/views/partials/head.php');
?>
<h1>Tasks</h1>
<?php
echo $taskHTML;
?>
    <script src="/public/scripts/complete-task.js"></script>
<?php
require(__ROOT__ . '/src/views/partials/footer.php');
