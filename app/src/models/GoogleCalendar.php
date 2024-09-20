<?php

namespace kiosk\models;

class GoogleCalendar {
    private $client;
    public function __construct() {
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __ROOT__ . '/config/calendar-private-key.json');

        $this->client = new \Google\Client();
        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(\Google\Service\Calendar::CALENDAR);
    }

    public function getEvents() {
        $cache = new Cache();
        if ( $cache->fetchCache('googleCalendar') ) {
            //echo "Google calendar data is from cache!\n";
            return $cache->fetchCache('googleCalendar');
        } else {
            // https://developers.google.com/calendar/api/v3/reference/events/list#php
            $calendar = new \Google\Service\Calendar($this->client);
            $dateUtil = new DateUtil();
            $dateUpcomingWeek = $dateUtil->getDateArray(7);
            $dateStart = new \DateTimeImmutable($dateUpcomingWeek[0]);
            $dateEnd = new \DateTimeImmutable($dateUpcomingWeek[7]);
            $upcomingEvents = [];

            $events = $calendar->events->listEvents(getenv('GCAL_CALENDAR_ID'), array(
                'maxResults' => 10,
                'timeMin' => $dateStart->format(DATE_ATOM),
                'timeMax' => $dateEnd->format(DATE_ATOM),
                'singleEvents' => true,
                'orderBy' => 'startTime'
            ));

            if ( !empty( $events->getItems() ) ) {
                foreach ( $events->getItems() as $event ) {
                    $allDay = $event->getStart()->getDateTime() === null;

                    if ($allDay) {
                        $startDate = $event->getStart()->date;
                        $startTime = '00:00:00';
                        $endDate = $event->getEnd()->date;
                        $endTime = '00:00:00';
                    } else {
                        $startDate = date('Y-m-d', strtotime($event->getStart()->getDateTime()));
                        $startTime = date('G:i:s', strtotime($event->getStart()->getDateTime()));
                        $endDate = date('Y-m-d', strtotime($event->getEnd()->getDateTime()));
                        $endTime = date('G:i:s', strtotime($event->getEnd()->getDateTime()));
                    }

                    $upcomingEvents[] = array(
                        'title' => trim( $event->getSummary() ),
                        'location' => $event->getLocation(),
                        'allDay' => $allDay,
                        'startDate' => $startDate,
                        'startTime' => $startTime,
                        'endDate' => $endDate,
                        'endTime' => $endTime,
                        'class' => 'google-event'
                    );
                }
            }
            $cache->cacheData($upcomingEvents, 'googleCalendar');
            return $upcomingEvents;
        }
    }
}
