<?php

use kiosk\models\Bins;
use kiosk\models\DateUtil;
use kiosk\models\Meals;

$dateUtil = new DateUtil();
$bins = new Bins( getenv('BIN_DOMAIN') . "?rn=" .getenv('BIN_RN') );
$binData = $bins->formatData($bins->fetch());
$dateUtil->formatWeekCalendarData($binData);

$meals = new Meals();
$dateUtil->formatWeekCalendarData( $meals->formatData($meals->getMeals()) );

$calendarHtml = $dateUtil->outputWeekCalendar();

$title = "Calendar";
require __ROOT__ . '/src/views/index.view.php';
