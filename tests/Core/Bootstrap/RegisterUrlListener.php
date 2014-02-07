<?php

namespace Core\Bootstrap;

use Phalcon\Mvc\Url;

class RegisterUrlListener
{
    public function init($event, $application)
    {
        $di = $application->getDI();
        $di->setShared('url', new Url());
    }

    public function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');
        $url = $di->getShared('url');

        $url->setBaseUri($config->application->baseUri);
    }
}
