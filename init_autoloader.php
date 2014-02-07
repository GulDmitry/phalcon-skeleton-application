<?php

if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
} else {
    $loader = new \Phalcon\Loader();
    // "In Phalcon 2.0 we'll adapt the current autoloader to PSR-4 as long it is approved."
    $loader->registerNamespaces(
        [
            'Core' => 'vendor/Core/',
            'Core\Bootstrap' => 'vendor/Core/Bootstrap/',
            'Core\Mvc' => 'vendor/Core/Mvc/',
            'Core\Exception' => 'vendor/Core/Exception/',
            'Core\CLI' => 'vendor/Core/CLI/',
            'Test' => 'tests/',
            'Phalcon' => 'library/Phalcon/',
        ]
    );
    $loader->register();
}
