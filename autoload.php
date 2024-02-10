<?php

spl_autoload_register(function ($class) {
    $class = str_replace("App", "src", $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    // Check if the file exists
    if (!file_exists($file)) {
        echo("class {$class} not found in location {$file}".PHP_EOL);
        die();
    }
    require_once $file;
});