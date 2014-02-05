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
    'configGlobPaths' => [
        'config/autoload/{,*.}{global,local}.php',
    ],
    'templateOptions' => [
        'compiledPath' => './data/cache/template/',
        'compiledSeparator' => '_',
        'compileAlways' => false,
    ],
    'viewStrategyClass' => '\Core\Mvc\DefaultViewStrategy',
    'application' => [
        'libraryDir' => APPLICATION_PATH . '/library/',
    ],
];
