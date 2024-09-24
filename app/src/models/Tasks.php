<?php

namespace kiosk\models;

class Tasks extends FetchData {
    private string $authEndpoint = '/api/v1/login';
    private string $taskEndpoint = '/api/v1/projects/8/tasks';
    private string $authToken;
    public Cache $cache;
    public function __construct() {
        $this->cache = new Cache();
        $this->fetchUrl = getenv('VIKUNJA_DOMAIN');
        parent::__construct($this->fetchUrl);
        $this->authToken = $this->authenticate();
    }

    public function authenticate() : string {
        if ( $this->cache->fetchCache('vikunjaAuthToken') ) {
            return $this->cache->fetchCache('vikunjaAuthToken');
        } else {
            $data = array(
                "long_token" => true,
                "password" => getenv('VIKUNJA_PASSWORD'),
                "username" => getenv('VIKUNJA_USERNAME')
            );
            $loginData = $this->fetch('vikunjaAuthToken',array(
               CURLOPT_HEADER => 0,
               CURLOPT_POST => true,
               CURLOPT_URL => "{$this->fetchUrl}{$this->authEndpoint}",
               CURLOPT_POSTFIELDS => json_encode($data),
               CURLOPT_HTTPHEADER => array('Content-Type: application/json')
            ));

            $this->cache->cacheData($loginData['token'], 'vikunjaAuthToken');

            return $loginData['token'];
        }
    }

    public function getTasks() : array {
        return $this->fetch('tasks', array(
            CURLOPT_HTTPGET => 1,
            CURLOPT_URL => "{$this->fetchUrl}{$this->taskEndpoint}",
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_HTTPAUTH => CURLAUTH_BEARER,
            CURLOPT_XOAUTH2_BEARER => $this->authToken
        ));
    }

    public function formatData( array $data ) : array {
        $dateUtil = new DateUtil();
        $weekData = $dateUtil->getDateArray(7);
        $tasksUpcomingWeekData = [];

        foreach ( $data as $task ) {

        }

    }

    // Only include tasks if they are due in the next week/month
    private function includeTask( array $task ) : bool {
        $taskDueDate = $task['due_date'];
        $dateUtil = new DateUtil();

        // Task repeats every weekly or fortnightly
        if ( $task['repeat_after'] ===  604800 || $task['repeat_after'] ===  1209600 ) {
            // Might need to format $taskDueDate
            if ($taskDueDate > $dateUtil->getDate(7)) {
                return false;
            }
        }
        // TODO Check if monthly task
    }
}
