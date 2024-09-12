<?php

namespace kiosk\models;

class Meals extends FetchData {
    public string $fetchUrl = "https://www.paprikaapp.com/api/v2/sync";
    private string $loginUrl = "https://www.paprikaapp.com/api/v2/account/login";
    private string $mealsEndpoint = "/meals/";
    public string $authToken;

    public function __construct() {
        $this->authToken = $this->authenticate();
    }

    public function authenticate(): string {
        $loginData = $this->fetch(array(
            CURLOPT_HEADER => 0,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => "email=" . getenv('PAPRIKA_EMAIL') . "&password=" . getenv('PAPRIKA_PASSWORD'),
            CURLOPT_URL => $this->loginUrl,
        ));
        return $loginData['result']['token'];
    }

    public function getMeals() : array {
        $mealsData = $this->fetch(array(
            CURLOPT_HTTPGET => 1,
            CURLOPT_URL => "{$this->fetchUrl}{$this->mealsEndpoint}",
            CURLOPT_HTTPAUTH => CURLAUTH_BEARER,
            CURLOPT_XOAUTH2_BEARER => $this->authToken
        ));
        return $mealsData;
    }

    public function formatData( array $data ) : array {

    }
}
