<?php

use \kiosk\models\Tasks;

$tasks = new Tasks();

$title = "Tasks";

require __ROOT__ . '/src/views/tasks.view.php';
