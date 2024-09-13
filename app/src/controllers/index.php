<?php

use kiosk\models\Bins;
use kiosk\models\DateUtil;
use kiosk\models\Meals;
use kiosk\models\GoogleCalendar;

$dateUtil = new DateUtil();
$bins = new Bins( getenv('BIN_DOMAIN') . "?rn=" .getenv('BIN_RN') );
$dateUtil->formatWeekCalendarData( $bins->formatData( $bins->fetch() ) );

$meals = new Meals();
$dateUtil->formatWeekCalendarData( $meals->formatData( $meals->getMeals() ) );

$googleCalendar = new GoogleCalendar();
$googleCalendar->getEvents();

$calendarHtml = $dateUtil->outputWeekCalendar();

$title = "Calendar";
require __ROOT__ . '/src/views/index.view.php';
