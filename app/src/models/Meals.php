<?php

namespace kiosk\models;

class Meals extends FetchData {
    public string $fetchUrl = "https://www.paprikaapp.com/api/v2/sync";
    private string $loginUrl = "https://www.paprikaapp.com/api/v1/account/login";
    private string $mealsEndpoint = "/meals/";
    public string $authToken;
    public Cache $cache;

    public function __construct() {
        $this->cache = new Cache();
        parent::__construct($this->fetchUrl);
        $this->authToken = $this->authenticate();
    }

    public function authenticate(): string {
        if ( $this->cache->fetchCache('mealAuthToken') ) {
            return $this->cache->fetchCache('mealAuthToken');
        } else {
            $loginData = $this->fetch('mealAuthToken', array(
                CURLOPT_HEADER => 0,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => "email=" . getenv('PAPRIKA_EMAIL') . "&password=" . getenv('PAPRIKA_PASSWORD'),
                CURLOPT_URL => $this->loginUrl,
            ));
            // TODO Throw error/exception instead of echoing
            //if ( array_key_exists($loginData['error']['message']) && $loginData['error']['code'] === 0 ) {
            //    echo "<p>{$loginData['error']['message']}</p>";
            //}
            $this->cache->cacheData($loginData['result']['token'], 'mealAuthToken');
            return $loginData['result']['token'];
        }
    }

    public function getMeals() : array {
        return $this->fetch('meals', array(
            CURLOPT_HTTPGET => 1,
            CURLOPT_URL => "{$this->fetchUrl}{$this->mealsEndpoint}",
            CURLOPT_HTTPAUTH => CURLAUTH_BEARER,
            CURLOPT_XOAUTH2_BEARER => $this->authToken
        ));
    }

    public function formatData( array $data ): array {
        $dateUtil = new DateUtil();
        $weekData = $dateUtil->getDateArray(7);
        $mealsUpcomingWeekData = [];

        // The fetched data is enclosed in a 'results' key. array_shift returns the data inside 'results'
        foreach ( array_shift($data) as $meal ) {
            $mealDate = new \DateTimeImmutable($meal['date']);

            if ( in_array($mealDate->format('Y-m-d'), $weekData) ) {
                $mealsUpcomingWeekData[] = array(
                    "title" => trim($meal['name']),
                    "allDay" => true,
                    "startDate" => $mealDate->format('Y-m-d'),
                    "class" => 'paprika-meal-item'
                );
            }
        }
        return $mealsUpcomingWeekData;
    }
}
