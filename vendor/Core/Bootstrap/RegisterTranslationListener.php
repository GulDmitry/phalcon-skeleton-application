<?php

namespace Core\Bootstrap;

use Phalcon\Translate\Adapter\NativeArray as Adapter;

class RegisterTranslationListener
{
    public function init($event, $application)
    {
        $di = $application->getDI();

        /**
         * Setting up the translation component.
         */
        $di->setShared('translation', new Adapter());
    }

    public function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $view = $di->getShared('view');

        // Ask browser what is the best language
        $language = $this->request->getBestLanguage();

        // TODO: check cookie and replace
        $language = 'en';
        // TODO: define in config.
        $defaultLang = 'en';

        $path = DATA_PATH . '/messages/' . $language . '.php';

        //Check if we have a translation file for that lang
        if (file_exists($path)) {
            $translations = require 'app/messages/' . $language . '.php';
        } else {
            // Fallback to default.
            $translations = require DATA_PATH . '/messages/' . $defaultLang . '.php';
        }

        $view->setVar('t', new Adapter([
            'content' => $translations,
        ]));

    }
}
