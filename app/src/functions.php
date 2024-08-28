<?php
define('__ROOT__', dirname(__DIR__));

spl_autoload_register(function ($className) {
    // project-specific namespace prefix
    $prefix = 'kiosk\\models\\';
    // base directory for the namespace prefix
    $base_dir = __ROOT__ . '/src/models/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $className, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($className, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

$env = file_get_contents(__ROOT__ . "/config/.env");
$lines = explode("\n",$env);

foreach($lines as $line){
    preg_match("/([^#]+)=(.*)/",$line,$matches);
    if(isset($matches[2])){
        putenv(trim($line));
    }
}
