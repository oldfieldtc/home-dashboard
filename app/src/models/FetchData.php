<?php
namespace kiosk\models;

class FetchData {
    public string $fetchUrl;
    public function __construct($fetchUrl) {
        $this->fetchUrl = $fetchUrl;
    }
    public function fetch() : array {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->fetchUrl);
        $result = curl_exec($ch);
        curl_close($ch);

        //var_dump(json_decode($result, true));
        return json_decode($result, true);
    }
}
