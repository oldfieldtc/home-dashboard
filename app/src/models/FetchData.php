<?php
namespace kiosk\models;

class FetchData {
    public string $fetchUrl;
    public function __construct($fetchUrl) {
        $this->fetchUrl = $fetchUrl;
    }

    public function fetch(string $cacheKey, array $options = []) : array {
        $cache = new Cache();
        if ( $cache->fetchCache($cacheKey) ) {
            //echo "{$cacheKey} is from cache!\n";
            return $cache->fetchCache($cacheKey);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $this->fetchUrl);
            if (!empty($options)) {
                foreach ($options as $option => $value) {
                    curl_setopt($ch, $option, $value);
                }
            }

            $result = curl_exec($ch);
            curl_close($ch);

            $cache->cacheData(json_decode($result, true), $cacheKey);

            //var_dump(json_decode($result, true));
            return json_decode($result, true);
        }
    }
}
