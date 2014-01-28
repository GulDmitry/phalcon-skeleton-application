<?php

namespace Core\Bootstrap;

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Tag;

class RegisterViewListener
{
    public function init($event, $application)
    {
        $di = $application->getDI();

        /**
         * Setting up the view component.
         */
        $di->setShared('view', new View());
    }

    public function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        /**
         * Setting up the view component.
         */
        $view = $di->getShared('view');
        $view->registerEngines(array(
            '.volt' => function ($view, $di) use ($config) {
                    $volt = new Volt($view, $di);
                    $volt->setOptions((array)$config->templateOptions);
                    return $volt;
                },
            '.phtml' => 'Phalcon\Mvc\View\Engine\Volt',
        ));

        Tag::setDoctype($config->application->doctype);
        Tag::setTitle($config->application->title);
    }
}
