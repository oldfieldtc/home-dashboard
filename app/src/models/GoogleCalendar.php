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
        // https://developers.google.com/calendar/api/v3/reference/events/list#php
        $calendar = new \Google\Service\Calendar($this->client);
        $events = $calendar->events->listEvents(getenv('GCAL_CALENDAR_ID'));

        while(true) {
            foreach ($events->getItems() as $event) {
                echo $event->getSummary();
            }
            $pageToken = $events->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $events = $calendar->events->listEvents(getenv('GCAL_CALENDAR_ID'), $optParams);
            } else {
                break;
            }
        }
    }
}
