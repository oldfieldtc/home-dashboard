<?php

namespace kiosk\models;

class Cache {
    public function cacheData(array|string $data, string $keyName) : bool {
        return apcu_store($keyName, $data, 432000);
    }

    public function fetchCache(string $keyName) {
        return apcu_fetch($keyName);
    }

    public function clearCache(string $keyName) : bool {
        return apcu_delete($keyName);
    }
}
