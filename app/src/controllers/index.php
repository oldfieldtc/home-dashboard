<?php

use kiosk\models\Bins;
use kiosk\models\DateUtil;
use kiosk\models\Meals;

$dateUtil = new DateUtil();
$bins = new Bins( getenv('BIN_DOMAIN') . "?rn=" .getenv('BIN_RN') );
$binData = $bins->formatData($bins->fetch());
$dateUtil->formatWeekCalendarData($binData);
//var_dump($dateUtil->weekCalendarArray);
//var_dump($binData);

$meals = new Meals( "https://www.paprikaapp.com/api/v2/account/login");
$mealsData = $meals->fetch(array(
    CURLOPT_HEADER => 0,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => "email=" . getenv('PAPRIKA_EMAIL') . "&password=" . getenv('PAPRIKA_PASSWORD')
));
var_dump($mealsData);

//var_dump($dateUtil->sortByDateAsc($binData));

$calendarHtml = $dateUtil->outputWeekCalendar();

$title = "Calendar";
require __ROOT__ . '/src/views/index.view.php';
