<?php

namespace Core\Bootstrap;

class RegisterViewStrategyListener
{
    public function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $dispatcher = $di->get('dispatcher');
        $eventsManager = $dispatcher->getEventsManager();
        $config = $di->get('config');
        $view = $di->get('view');

        $class = $config->viewStrategyClass;

        $strategy = new $class($config, $view);
        $eventsManager->attach('dispatch', $strategy);
    }
}
