<?php

namespace kiosk\models;

class DateUtil {
    public array $weekCalendarArray;

    public function __construct() {
        date_default_timezone_set('Europe/London');
        $this->weekCalendarArray = $this->createWeekCalendarArray();
    }

    public function getDate( int $offset ) : string {
        return date("Y-m-d", strtotime("+$offset day"));
    }

    public function getDateArray( int $days ) : array {
        $dateArray = [];
        for ($i = 0; $i <= $days; $i++) {
            $dateArray[] = $this->getDate($i);
        }
        return $dateArray;
    }

    public function getLocalDateTime(string $dateTime) {
        return date('Y-m-d H:i:s', strtotime($dateTime));
    }

    public function sortByDateDesc( array $dataArray ) : array {
        array_multisort(array_column($dataArray, 'startDate'), SORT_DESC, $dataArray);
        return $dataArray;
    }

    public function sortByDateAsc( array $dataArray ) : array {
        array_multisort(array_column($dataArray, 'startDate'), SORT_ASC, $dataArray);
        return $dataArray;
    }

    private function createWeekCalendarArray() : array {
        return array_fill_keys($this->getDateArray(7), []);
    }

    public function formatWeekCalendarData( array $dataArray ) : void {
        foreach ($dataArray as $data) {
            if ( array_key_exists($data['startDate'], $this->weekCalendarArray) ) {
                $this->weekCalendarArray[$data['startDate']][] = $data;
            }
        }
    }

    public function outputWeekCalendar() {
        ob_start();
        ?>
        <div class="calendar">
        <?php
        foreach ($this->weekCalendarArray as $day => $index) {
            //var_dump($day);
            //var_dump($index);
        ?>
            <h2 class="DateHeading"><?php echo Date('l j F o', strtotime($day)); ?></h2>
            <ul>
            <?php
                foreach ( $index as $item ) {
                ?>
                    <li class="<?php echo $item['class']; ?>">
                        <?php
                            echo $item['title'];
                            if (!$item['allDay']) {
                            ?>
                                <span class="subData">
                                <?php
                                    echo Date('H:i', strtotime($item['startTime'])) . ' - ' . Date('H:i', strtotime($item['endTime']));
                                    if ( $item['location'] ) {
                                        echo "</br>{$item['location']}";
                                    }
                                ?>
                                </span>
                            <?php
                            }
                        ?>
                    </li>
                <?php
                }
            ?>
            </ul>

        <?php
        }
        ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
