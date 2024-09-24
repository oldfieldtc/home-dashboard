<?php

use \kiosk\models\Tasks;

$tasks = new Tasks();
$tasksData = $tasks->getTasks();
var_dump($tasksData);

$title = "Tasks";

require __ROOT__ . '/src/views/tasks.view.php';
