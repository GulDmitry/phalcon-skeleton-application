<?php

namespace Core\Bootstrap;

use Phalcon\Translate\Adapter\NativeArray as Adapter;
use Phalcon\Cache\ConfigHelper as Helper;
use Phalcon\Http\Response\Cookies;

class RegisterTranslationListener
{
    public function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->getShared('config');

        if (!$config->multilanguage->enabled) {
            $this->fillDI($application, []);
            return;
        }

        $cookies = new Cookies();
        // From cookies or $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $language = $cookies->get('lang')->getValue(null, substr($application->request->getBestLanguage(), 0, 2));
        $path = $config->multilanguage->path . $language . '.php';

        $defaultTranslations = require $config->multilanguage->path . $config->multilanguage->defaultLanguage . '.php';

        $cacheHelper = new Helper($config->multilanguage->languageCache->toArray());

        $cachedLanguage = $cacheHelper->getValue('.' . $language);
        if ($cachedLanguage) {
            $this->fillDI($application, $cachedLanguage);
            return;
        }

        //Check if we have a translation file for that lang.
        if ($language != $config->multilanguage->defaultLanguage && file_exists($path)) {
            $translations = array_merge($defaultTranslations, require $path);
        } else {
            $translations = $defaultTranslations;
        }

        $cacheHelper->setValue('.' . $language, $translations);

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
