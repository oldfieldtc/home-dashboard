<?php
include(__ROOT__ . "/src/models/Bins.php");
include(__ROOT__ . "/src/models/DateUtil.php");
use kiosk\models\Bins;
use kiosk\models\DateUtil;

$bins = new Bins( getenv('BIN_DOMAIN') . "?rn=" .getenv('BIN_RN') );
$binData = $bins->formatData($bins->fetch());
var_dump($binData);

$title = "Calendar";
require __ROOT__ . '/src/views/index.view.php';
