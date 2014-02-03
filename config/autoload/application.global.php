<?php

return [
    'application' => [
        'defaultModule' => 'Application',
        'title' => 'Phalcon Application',
        'doctype' => Phalcon\Tag::HTML5,
    ],
    'assetsCache' => [
        'enabled' => true,
    ],
    'languages' => [
        'en' => 'English',
        'ru' => 'Русский',
    ],
    'languageCache' => [
        'enabled' => true,
        'lifetime' => 86400,
        'storage' => [
            'backend' => 'Phalcon\Cache\Backend\File',
            'frontend' => 'Phalcon\Cache\Frontend\Data',
            'options' => [
                'key' => 'language.cache',
                'cacheDir' => './data/cache/language/',
            ],
        ],
    ],
    'defaultLanguage' => 'en',
];
