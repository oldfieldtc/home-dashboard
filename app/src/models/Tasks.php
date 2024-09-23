<?php

namespace kiosk\models;

class Tasks extends FetchData {
    private string $authEndpoint = '/api/v1/login';
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
            //var_dump($loginData);
            $this->cache->cacheData($loginData['token'], 'vikunjaAuthToken');
            return $loginData['token'];
        }
    }
}
