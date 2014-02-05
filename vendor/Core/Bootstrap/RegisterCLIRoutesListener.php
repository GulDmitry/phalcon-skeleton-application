<?php

namespace Core\Bootstrap;

use Phalcon\CLI\Router,
    Core\Exception\DomainException;

class RegisterCLIRoutesListener
{
//    public function init($event, $application)
//    {
//        $di = $application->getDI();
//        $di->setShared('router', new Router());
//    }

    public function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        if (isset($config['router']['routes'])) {
            $routes = $config['router']['routes'];
            foreach ($routes as $routeName => $routeOptions) {
                if (!isset($routeOptions->defaults->namespace)) {
                    throw new DomainException(sprintf(
                        "Missing default option 'namespace' for the route '%s'",
                        $routeName
                    ));
                }
                if (!isset($routeOptions->defaults->task)) {
                    throw new DomainException(sprintf(
                        "Missing default option 'task' for the route '%s'",
                        $routeName
                    ));
                }
                if (!isset($routeOptions->defaults->action)) {
                    throw new DomainException(sprintf(
                        "Missing default option 'action' for the route '%s'",
                        $routeName
                    ));
                }
            }
        }
    }
}
