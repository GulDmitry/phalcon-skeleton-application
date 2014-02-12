<?php

namespace Admin;

use Phalcon\Mvc\ModuleDefinitionInterface,
    Phalcon\Loader;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders($di)
    {
        $di->get('loader')->registerNamespaces([
            'Admin' => __DIR__ . '/src',
        ], true);
    }

    public function getConfig()
    {
        return [
            'router' => [
                'routes' => [
                    'admin' => [
                        'route' => '/admin',
                        'defaults' => [
                            'module' => 'Admin',
                            'namespace' => 'Admin\Controller',
                            'controller' => 'index',
                            'action' => 'index',
                        ],
                    ],
                ],
            ],
            'viewStrategy' => [
                'admin' => [ // module name in lowercase
                    'viewDir' => __DIR__ . '/view/templates/',
                    // Must be a directory under the views directory.
                    'layoutsDir' => '../../../Application/view/layouts/',
                    'defaultLayout' => 'layout',
                ],
            ],
        ];
    }

    public function onBootstrap($application)
    {

    }

    public function registerServices($di)
    {

    }
}
