<?php

namespace kiosk\models;

class DateUtil {
    public function __construct() {
        date_default_timezone_set('Europe/London');
    }

    private function getDate( int $offset ) : string {
        return date("Y-m-d", strtotime("+$offset day"));
    }

    public function getDateTimeArray( int $days ) : array {
        $dateArray = [];
        for ($i = 0; $i <= $days; $i++) {
            $dateArray[] = $this->getDate($i);
        }
        return $dateArray;
    }

    public function sortByDateDesc( array $dataArray ) : array {
        array_multisort(array_column($dataArray, 'startDate'), SORT_DESC, $dataArray);
        return $dataArray;
    }

    public function sortByDateAsc( array $dataArray ) : array {
        array_multisort(array_column($dataArray, 'startDate'), SORT_ASC, $dataArray);
        return $dataArray;
    }
}
