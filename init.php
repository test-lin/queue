<?php

spl_autoload_register(function ($class) {
    if (0 === stripos($class, 'Testlin\\Queue\\')) {
        $filename = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['\\', 'Testlin/Queue/',], ['/', 'src/'], $class) . '.php';
        file_exists($filename) && include($filename);
    }
});