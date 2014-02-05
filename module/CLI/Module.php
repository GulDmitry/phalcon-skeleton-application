<?php

namespace CLI;

use Phalcon\Mvc\ModuleDefinitionInterface,
    Phalcon\Loader;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders()
    {
        $loader = new Loader();
        $loader->registerNamespaces([
            'CLI' => __DIR__ . '/src',
        ]);
        $loader->register();
    }

    public function getConfig()
    {
        return [
            'router' => [
                'routes' => [
                    'main' => [
                        'defaults' => [
                            // No need to set a module.
                            'namespace' => 'CLI\Task',
                            'task' => 'main',
                            'action' => 'main',
                            'description' => 'The main route',
                        ],
                    ],
                    'help' => [
                        'defaults' => [
                            'namespace' => 'CLI\Task',
                            'task' => 'main',
                            'action' => 'help',
                            'description' => 'Displays all available routes',
                        ],
                    ],
                    'test' => [
                        'defaults' => [
                            'namespace' => 'CLI\Task',
                            'task' => 'main',
                            'action' => 'test',
                        ],
                    ],
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
