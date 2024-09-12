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

$meals = new Meals();
//var_dump($meals->getMeals());
//$dateUtil->formatWeekCalendarData($meals->getMeals());

//var_dump($dateUtil->sortByDateAsc($binData));

$calendarHtml = $dateUtil->outputWeekCalendar();

$title = "Calendar";
require __ROOT__ . '/src/views/index.view.php';
