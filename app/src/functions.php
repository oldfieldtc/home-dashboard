<?php
define('__ROOT__', dirname(__DIR__));

$env = file_get_contents(__ROOT__ . "/config/.env");
$lines = explode("\n",$env);

foreach($lines as $line){
    preg_match("/([^#]+)=(.*)/",$line,$matches);
    if(isset($matches[2])){
        putenv(trim($line));
    }
}
