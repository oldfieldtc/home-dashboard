<?php

use \kiosk\models\Tasks;

$tasks = new Tasks();
$taskHTML = $tasks->outputTasks( $tasks->formatData( $tasks->getTasks() ) );

$title = "Tasks";

require __ROOT__ . '/src/views/tasks.view.php';
