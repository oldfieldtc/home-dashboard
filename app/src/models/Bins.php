<?php
namespace kiosk\models;

class Bins extends FetchData {
    public function formatData( array $binData ) : array {
        $dateUtil = new DateUtil();
        $weekData = $dateUtil->getDateArray(7);
        $binUpcomingWeekData = [];

        foreach ( $binData as $binDay ) {
            if ( in_array($binDay["start"], $weekData) ) {
                $binUpcomingWeekData[] = array(
                    "title" => $binDay["title"],
                    "allDay" => true,
                    "startDate" => $binDay["start"],
                    "class" => "binday-{$binDay['className']}"
                );
            }
        }
        return $binUpcomingWeekData;
    }
}
