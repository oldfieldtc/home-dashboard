<?php
namespace kiosk\models;
include(__ROOT__ . "/src/models/FetchData.php");
include(__ROOT__ . "/src/models/DateUtil.php");

class Bins extends FetchData {
    public function formatData( array $binData ) : array {
        $dateUtil = new DateUtil();
        $weekData = $dateUtil->getDateTimeArray(7);
        $binUpcomingWeekData = [];

        foreach ( $binData as $binDay ) {
            if ( in_array($binDay["start"], $weekData) ) {
                $binUpcomingWeekData[] = array(
                    "title" => $binDay["title"],
                    "allDay" => true,
                    "start" => $binDay["start"],
                    "class" => "binday-{$binDay['className']}"
                );
            }
        }
        return $binUpcomingWeekData;
    }
}
