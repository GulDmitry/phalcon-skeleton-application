<?php

namespace Core\Bootstrap;

use Phalcon\Translate\Adapter\NativeArray as Adapter,
    Phalcon\Tag,
    Phalcon\Http\Response\Cookies;

class RegisterTranslationListener
{
    public function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->getShared('config');

        $cookies = new Cookies();
        // From cookies or $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $language = $cookies->get('lang')->getValue(null, substr($application->request->getBestLanguage(), 0, 2));
        $path = DATA_PATH . '/language/' . $language . '.php';

        $defaultTranslations = require DATA_PATH . '/language/' . $config->defaultLanguage . '.php';

        // TODO: Cache interface for this part. Maybe inherit listeners from class where put cache handler.
        if ($config->languageCache->enabled) {
            $storage = $config->languageCache->storage;
            $lifetime = $config->languageCache->lifetime;

            $containerClass = $storage->frontend;
            $container = new $containerClass($lifetime);

            $storageClass = $storage->backend;
            $cache = new $storageClass($container, (array)$storage->options);

            $cachedLanguage = $cache->get($storage->options->key . '.' . $language, $lifetime);

            if ($cachedLanguage) {
                $this->fillDI($application, $cachedLanguage);
                return;
            }
        }

        //Check if we have a translation file for that lang.
        if ($language != $config->defaultLanguage && file_exists($path)) {
            $translations = array_merge($defaultTranslations, require $path);
        } else {
            $translations = $defaultTranslations;
        }

        if ($config->languageCache->enabled) {
            $cache->save($storage->options->key . '.' . $language, $translations, $lifetime);
        }

        // TODO: test on untranslated lable.
        // TODO: CLI task to display all untranslated lables.
        $this->fillDI($application, $translations);
    }

    public function fillDI($application, array $translations)
    {
        $di = $application->getDI();

        $adapterObj = new Adapter([
            'content' => $translations,
        ]);

        $di->setShared('t', function () use ($adapterObj) {
                return $adapterObj;
            }
        );

    }
}
