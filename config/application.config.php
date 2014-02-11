<?php

return [
    'modulePaths' => [
        './module/',
        './vendor/',
    ],
    'modules' => [
        'Application',
        'Admin',
    ],
    'modulesCLI' => [
        'CLI',
    ],
    'application' => [
        'defaultModule' => 'Application',
        'libraryDir' => APPLICATION_PATH . '/library/',
    ],
    'configGlobPaths' => [
        'config/autoload/{,*.}{global,local}.php',
    ],
    'templateOptions' => [
        'compiledPath' => APPLICATION_PATH . '/data/cache/template/',
        'compiledSeparator' => '_',
        'compileAlways' => false,
    ],
    'viewStrategyClass' => '\Core\Mvc\DefaultViewStrategy',
];
