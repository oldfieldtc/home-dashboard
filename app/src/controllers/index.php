<?php

use kiosk\models\Bins;
use kiosk\models\DateUtil;

$dateUtil = new DateUtil();
$bins = new Bins( getenv('BIN_DOMAIN') . "?rn=" .getenv('BIN_RN') );
$binData = $bins->formatData($bins->fetch());
//var_dump($binData);

var_dump($dateUtil->sortByDateAsc($binData));

$title = "Calendar";
require __ROOT__ . '/src/views/index.view.php';
