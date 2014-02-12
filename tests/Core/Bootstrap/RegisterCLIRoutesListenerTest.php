<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase;
use Phalcon\Config;
use Core\Bootstrap\RegisterCLIRoutesListener as CoreRegisterCLIRoutesListener;

class RegisterCLIRoutesListener extends UnitTestCase
{
    /**
     * @var \Core\Mvc\Application
     */
    public $app;

    protected function setUp()
    {
        $this->app = $this->initMvcApplication();
        $eventsManager = $this->app->getEventsManager();
        $eventsManager->detachAll('bootstrap');
        $eventsManager->attach('bootstrap', new CoreRegisterCLIRoutesListener());

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @dataProvider providerRouteData
     * @expectedException Core\Exception\DomainException
     */
    public function testCLIRoutesExceptionsModules($route)
    {
        $config = $this->app->getDI()->get('config');
        $config->merge(
            new Config([
                'router' => [
                    'routes' => [
                        'testing_exception' => $route,
                    ]
                ]

            ])
        );
        $this->app->handle();
    }

    public static function providerRouteData()
    {
        return [
            [
                [
                    'defaults' => [
                        // Namespace omitted
                        'task' => 'main',
                        'action' => 'main',
                        'description' => 'The main route',
                    ],
                ],
            ],
            [
                [
                    'defaults' => [
                        // Task omitted
                        'namespace' => 'CLI\Task',
                        'action' => 'main',
                        'description' => 'The main route',
                    ],
                ],
            ],
            [
                [
                    'defaults' => [
                        // Action omitted
                        'namespace' => 'CLI\Task',
                        'task' => 'main',
                        'description' => 'The main route',
                    ],
                ],
            ],
            [
                [
                    'defaults' => [
                        // Description omitted
                        'namespace' => 'CLI\Task',
                        'task' => 'main',
                        'action' => 'main',
                    ],
                ],
            ],
        ];
    }
}

