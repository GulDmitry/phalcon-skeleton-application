<?php

return [
    'modulePaths' => [
        './module/',
        './vendor/',
    ],
    'modules' => [
        'Application',
    ],
    'configGlobPaths' => [
        'config/autoload/{,*.}{global,local}.php',
    ],
    'configCache' => [
        'enabled' => true, // Enable or disable configuration caching.
        'lifetime' => 86400, // 24 hours.
        'storage' => [
            'backend' => 'Phalcon\Cache\Backend\File',
            'frontend' => 'Phalcon\Cache\Frontend\Data',
            'options' => [
                'key' => 'config.cache',
                'cacheDir' => './data/cache/config/',
            ],
        ],
    ],
    'assetsCache' => [
        'enabled' => true,
    ],
    'templateOptions' => [
        'compiledPath' => './data/cache/template/',
        'compiledSeparator' => '_',
        'compileAlways' => false,
    ],
    'viewStrategyClass' => 'Core\Mvc\DefaultViewStrategy',
    'application' => [
        'libraryDir' => APPLICATION_PATH . '/library/',
    ],
];
