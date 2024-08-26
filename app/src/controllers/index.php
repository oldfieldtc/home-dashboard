<?php
include(__ROOT__ . "/src/models/Bins.php");
use kiosk\models\Bins;

$bins = new Bins( getenv('BIN_DOMAIN') . "?rn=" .getenv('BIN_RN') );
$bins->fetch();

$title = "Calendar";
require __ROOT__ . '/src/views/index.view.php';
