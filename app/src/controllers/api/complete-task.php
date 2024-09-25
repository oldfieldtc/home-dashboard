<?php
use \kiosk\models\Tasks;
use \kiosk\models\Cache;

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'application/json' ) {
    $tasks = new Tasks();
    $cache = new Cache();
    $requestBody = json_decode(file_get_contents('php://input'), true);

    $updateTaskData = $tasks->updateTask($requestBody['id'], $requestBody['frequency']);

    header('Content-type: application/json');
    echo json_encode($updateTaskData);
} else {
    http_response_code(405);
}
